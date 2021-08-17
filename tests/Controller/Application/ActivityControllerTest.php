<?php

namespace Tests\Controller\Application;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\Pet;
use App\Models\User;
use App\Http\Requests\ActivityRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class ActivityControllerTest extends TestCase
{
    use RefreshDatabase;

    private $rulesMessages;

    public function setUp(): void
    {
        parent::setUp();
        $this->rulesMessages = (new ActivityRequest())->messages();
    }

    /**
     * Deve redirecionar para página de login quando não está autenticado.
     */
    public function testOnlyAuthenticatedUsersCanSeeActivities(): void
    {
        $this->get('/activities')
             ->assertRedirect('/login');
    }

    /**
     * Deve listar atividades
     */
    public function testListActivities(): void
    {
        $this
            ->actingAs(User::factory()->create())
            ->get('/activities')
            ->assertOk();
    }

    /**
     * Deve exibir a tela para cadastrar uma nova atividade
     */
    public function testActivityCreate()
    {
        $this
            ->actingAs(User::factory()->create())
            ->get('/activity/create')
            ->assertOk();
    }

    /**
     * Deve salvar uma nova atividade
     */
    public function testStoreActivity(): void
    {
        $files = [
            UploadedFile::fake()->create('video1.mp4'),
            UploadedFile::fake()->create('video2.mp4')
        ];

        $data = [
            'activity_date' => '16/07/2021',
            'pet_id'        => 1,
            'score'         => 10,
            'description'   => 'Lorem ipsum dolor',
            'files'         => $files
        ];

        $this
            ->actingAs(User::factory()->create())
            ->post('/activity/store', $data)
            ->assertRedirect('/activities');

        $date = \DateTime::createFromFormat('d/m/Y', $data['activity_date']);
        $data['activity_date'] = $date->format('Y-m-d');

        unset($data['files']);

        $this->assertDatabaseHas('activity', $data);

        foreach ($files as $file) {
            $this->assertDatabaseHas('media', ['file_name' => $file->name]);
        }
    }

     /**
     * Deve exibir tela para editar uma atividade
     */
    public function testActivityEdit()
    {
        $activity = Activity::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/activity/%s/edit', $activity->id))
            ->assertOk();
    }

    /**
     * Deve exibir erro de página não encontrada
     */
    public function testError4040ActivityEdit()
    {
        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/activity/%s/edit', 1))
            ->assertNotFound();
    }

    /**
     * Deve salvar as alterações de uma atividade
     */
    public function testUpdateActivity(): void
    {
        $pet = Pet::factory()->create();
        $activity = Activity::factory()->create();

        $files = [
            UploadedFile::fake()->create('video1.mp4'),
            UploadedFile::fake()->create('video2.mp4')
        ];

        $data = [
            'activity_date' => '16/07/2021',
            'pet_id'        => $pet->id,
            'score'         => 10,
            'description'   => 'Lorem ipsum dolor',
            'files'         => $files
        ];

        $this
            ->actingAs(User::factory()->create())
            ->put(sprintf('/activity/%s/update', $activity->id), $data)
            ->assertRedirect('/activities');

        unset($data['files']);

        $date = \DateTime::createFromFormat('d/m/Y', $data['activity_date']);
        $data['activity_date'] = $date->format('Y-m-d');

        $this->assertDatabaseHas('activity', $data);

        foreach ($files as $file) {
            $this->assertDatabaseHas('media', ['file_name' => $file->name]);
        }
    }

    /**
     * Deve excluir uma atividade
     */
    public function testDestroyActivity(): void
    {
        $activity = Activity::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->delete(sprintf('/activity/%s/destroy', $activity->id))
            ->assertRedirect('/activities');

        $this->assertDatabaseMissing('activity', ['id' => $activity->id]);
    }

    /**
     * Deve excluir arquivo da atividade
     */
    public function testDestroyFileMedia(): void
    {
        $fileName = 'video.mp4';

        $file = UploadedFile::fake()->create($fileName);

        $activity = Activity::factory()->create();
        $activity->addMedia($file)->toMediaCollection('activity');
        $activity->save();

        $media = Media::where('file_name', '=', $fileName)->first();

        $this
            ->actingAs(User::factory()->create())
            ->delete(sprintf('/activity/destroyMedia/%s', $media->id))
            ->assertStatus(200)
            ->assertJsonFragment(['success' => true]);

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }

    /**
     * Deve exibir tela com informações de uma atividade
     */
    public function testActivityShow()
    {
        $customer = Customer::factory()->create();

        $pet = Pet::factory()->create([
            'customer_id' => $customer->id
        ]);

        $activity = Activity::factory()->create([
            'pet_id' => $pet->id
        ]);

        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/activity/%s/show', $activity->id))
            ->assertOk();
    }

    /**
     * Deve exibir erro de página não encontrada
     */
    public function testError404ActivityShow()
    {
        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/activity/%s/show', 1))
            ->assertNotFound();
    }

    /**
     * Deve efetuar a validação de campos obrigatórios da atividade
     */
    public function testActivityRequiredFields(): void
    {

        $response = $this
            ->actingAs(User::factory()->create())
            ->post('/activity/store', []);

        $response->assertSessionHasErrors([
            'activity_date' => $this->rulesMessages['activity_date.required'],
            'description'   => $this->rulesMessages['description.required'],
            'pet_id'        => $this->rulesMessages['pet_id.required'],
            'score'         => $this->rulesMessages['score.required']
        ]);
    }

    /**
     * Deve validar extensão dos arquivos da atividade
     */
    public function testActivityFilesExtension(): void
    {

        $files = [
            UploadedFile::fake()->create('video.mp4'),
            UploadedFile::fake()->create('file.pdf')
        ];

        $data = [
            'activity_date' => '16/07/2021',
            'pet_id'        => 1,
            'score'         => 10,
            'description'   => 'Lorem ipsum dolor',
            'files'         => $files
        ];

        $response = $this
            ->actingAs(User::factory()->create())
            ->post('/activity/store', $data);

        $response->assertSessionHasErrors([
            'files.1' => $this->rulesMessages['files.*.mimes']
        ]);
    }
}
