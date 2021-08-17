<?php

namespace Tests\Controller\Application;

use App\Models\User;
use App\Models\Pet;
use App\Models\Customer;
use App\Http\Requests\PetRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PetControllerTest extends TestCase
{
    use RefreshDatabase;

    private $rulesMessages;

    public function setUp(): void
    {
        parent::setUp();
        $this->rulesMessages = (new PetRequest())->messages();
    }

    /**
     * Deve redirecionar para página de login quando não está autenticado.
     */
    public function testOnlyAuthenticatedUsersCanSeePets(): void
    {
        $this->get('/pets')
             ->assertRedirect('/login');
    }

    /**
     * Deve listar pets
     */
    public function testListPets(): void
    {

        $this
            ->actingAs(User::factory()->create())
            ->get('/pets')
            ->assertOk();
    }

    /**
     * Deve exibir a tela para cadastrar um novo pet
     */
    public function testPetCreate()
    {
        $this
            ->actingAs(User::factory()->create())
            ->get('/pet/create')
            ->assertOk();
    }

    /**
     * Deve salvar um novo Pet
     */
    public function testStorePet(): void
    {

        $file = UploadedFile::fake()->create('test.jpg');

        $data = [
            'name'         => 'Pluto',
            'breed'        => 'Labrador',
            'customer_id'  => 1,
            'active'       => true,
            'photo'        => $file
        ];

        $this
            ->actingAs(User::factory()->create())
            ->post('/pet/store', $data)
            ->assertRedirect('/pets');

        unset($data['photo']);

        $this->assertDatabaseHas('pet', $data);
        $this->assertDatabaseHas('media', ['file_name' => $file->name]);
    }

    /**
     * Deve exibir tela para editar um pet
     */
    public function testPetEdit()
    {
        $pet = Pet::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/pet/%s/edit', $pet->id))
            ->assertOk();
    }

    /**
     * Deve exibir erro de página não encontrada
     */
    public function testError4040PetEdit()
    {
        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/pet/%s/edit', 1))
            ->assertNotFound();
    }

    /**
     * Deve salvar as alterações de um pet
     */
    public function testUpdatePet(): void
    {

        $customer = Customer::factory()->create();
        $pet = Pet::factory()->create();
        $file = UploadedFile::fake()->create('teste.jpg');

        $data = [
            'name'         => 'Pluto',
            'breed'        => 'Labrador',
            'customer_id'  => $customer->id,
            'active'       => true,
            'photo'        => $file
        ];

        $this
            ->actingAs(User::factory()->create())
            ->put(sprintf('/pet/%s/update', $pet->id), $data)
            ->assertRedirect('/pets');

        unset($data['photo']);

        $this->assertDatabaseHas('pet', $data);
        $this->assertDatabaseHas('media', ['file_name' => $file->name]);
    }

    /**
     * Deve excluir um pet
     */
    public function testDestroyPet(): void
    {

        $pet = Pet::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->delete(sprintf('/pet/%s/destroy', $pet->id))
            ->assertRedirect('/pets');

        $this->assertDatabaseMissing('pet', ['id' => $pet->id]);
    }

    /**
     * Deve exibir tela com informações de um pet
     */
    public function testPetShow()
    {
        $customer = Customer::factory()->create();

        $pet = Pet::factory()->create([
            'customer_id' => $customer->id
        ]);

        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/pet/%s/show', $pet->id))
            ->assertOk();
    }

    /**
     * Deve exibir erro de página não encontrada
     */
    public function testError4040PetShow()
    {
        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/pet/%s/show', 1))
            ->assertNotFound();
    }

    /**
     * Deve efetuar a validação de campos obrigatórios do pet
     */
    public function testPetRequiredFields(): void
    {

        $response = $this
            ->actingAs(User::factory()->create())
            ->post('/pet/store', []);

        $response->assertSessionHasErrors([
            'name'        => $this->rulesMessages['name.required'],
            'breed'       => $this->rulesMessages['breed.required'],
            'customer_id' => $this->rulesMessages['customer_id.required']
        ]);
    }

    /**
     * Deve validar extensão da foto
     */
    public function testPetPhotoExtension(): void
    {

        $file = UploadedFile::fake()->create('test/teste.pdf');

        $data = [
            'name'         => 'Pluto',
            'breed'        => 'Labrador',
            'customer_id'  => 2,
            'active'       => true,
            'photo'        => $file
        ];

        $response = $this
            ->actingAs(User::factory()->create())
            ->post('/pet/store', $data);

        $response->assertSessionHasErrors([
            'photo' => $this->rulesMessages['photo.mimes']
        ]);
    }
}
