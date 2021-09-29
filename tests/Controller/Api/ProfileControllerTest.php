<?php 

namespace Tests\Controller\Api;

use App\Http\Requests\PasswordRequest;
use App\Models\Activity;
use App\Models\Customer;
use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase 
{
    use RefreshDatabase;

    const ENDPOINT_PROFILE_PASSWORD_CREATE = '/api/profile/password/create';
    const ENDPOINT_PROFILE_PETS = '/api/profile/pets';

    private $rulesMessages;
    private $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->rulesMessages = (new PasswordRequest())->messages();
        $this->token = $this->getToken();
    }

    /**
     * Deve criar uma senha para um cliente
     */
    public function testCreatePassword(): void
    {
        $customer = Customer::factory()->create([
            'active' => true,
            'password' => null
        ]);

        $password = 'Test@123';

        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'                 => $customer->email,
                'password'              => $password,
                'password_confirmation' => $password
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $customerUpdated = Customer::find($customer->id);
        $this->assertNotNull($customerUpdated->password);
    }

    /**
     * Deve informar que o cliente não foi encontrado
     */
    public function testCustomerNotFound(): void
    {        
        $password = 'Test@abc';

        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'                 => 'fernando.mariano@test.com',
                'password'              => $password,
                'password_confirmation' => $password
            ])
            ->assertStatus(404)
            ->assertJson(['success' => false]);
    }

    /**
     * Deve informar que o cliente não está ativo
     */
    public function testCustomerNotActive(): void
    {
        $customer = Customer::factory()->create([
            'active' => false
        ]);

        $password = 'Test@2021';

        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'                 => $customer->email,
                'password'              => $password,
                'password_confirmation' => $password
            ])
            ->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    /**
     * Deve informar que uma senha já foi criada para um cliente
     */
    public function testPasswordAlreadyRegistered(): void
    {
        $customer = Customer::factory()->create([
            'active' => true,
            'password' => 'Test@1234'
        ]);

        $password = 'Test@2021';

        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'                 => $customer->email,
                'password'              => $password,
                'password_confirmation' => $password
            ])
            ->assertStatus(419)
            ->assertJson(['success' => false]);
    }

    /**
     * Deve efetuar a validação de campos obrigatórios
     */
    public function testCreatePasswordRequiredFields(): void
    {        
        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [$this->rulesMessages['email.required']],
                    'password' => [$this->rulesMessages['password.required']]
                ]
            ]);
    }

    /**
     * Deve efetuar a validação de e-mail válido
     */
    public function testCreatePasswordWithInvalidEmail(): void
    {        
        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'    => 'fernando.mariano',
                'password' => 'Test@1234'
            ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [$this->rulesMessages['email.email']],                    
                ]
            ]);
    }

    /**
     * Deve efetuar a validação de quantidade minima de caracteres da senha
     */
    public function testCreatePasswordWithoutMininumCaracteres(): void
    {   
        $customer = Customer::factory()->create([
            'active' => true,
            'password' => null
        ]);

        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'    => $customer->email,
                'password' => 'Test123'
            ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => [$this->rulesMessages['password.min']],                    
                ]
            ]);
    }

    /**
     * Deve efetuar a validação de confirmação de senha
     */
    public function testCreatePasswordConfirmation(): void
    {   
        $customer = Customer::factory()->create([
            'active' => true,
            'password' => null
        ]);

        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'                 => $customer->email,
                'password'              => 'Test1234',
                'password_confirmation' => 'Test@123'
            ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => [$this->rulesMessages['password.confirmed']],
                ]
            ]);
    }

    /**
     * Deve disparar um e-mail com um link para gerar uma nova senha
     */
    public function testSendEmailWithLinkToGenerateNewPassword(): void
    {
        
    }

    /**
     * Deve retornar lista com pets de um cliente
     */
    public function testGetMyPets(): void
    {
        Pet::factory(5)->create(['customer_id' => 1]);
                
        $this
            ->withHeaders([
                'Accept' => parent::APPLICATION_JSON,
                'Authorization' => $this->token
            ])
            ->get(self::ENDPOINT_PROFILE_PETS)
            ->assertStatus(200)
            ->assertJsonCount(5, 'pets')
            ->assertJsonStructure([
                'pets' => [['id', 'name', 'breed', 'active', 'photo']]
            ]);
    }

    /**
     * Deve retornar lista vazia de pets
     */
    public function testNotFoundMyPets(): void
    {                        
        $this
            ->withHeaders([
                'Accept' => parent::APPLICATION_JSON,
                'Authorization' => $this->token
            ])
            ->get(self::ENDPOINT_PROFILE_PETS)
            ->assertStatus(200)
            ->assertJsonCount(0, 'pets')
            ->assertJson([
                'pets' =>  []
            ]);
    }

    /**
     * Deve retornar lista de atividades dos pets filtrando por data
     */
    public function testGetMyPetActivities()
    {
        $pet = Pet::factory()->create(['customer_id' => 1]);
        
        foreach (['2021-07-01', '2021-08-01', '2021-08-02', '2021-08-31'] as $activity_date) {
            Activity::factory()->create([
                'pet_id'        => $pet->id,
                'activity_date' => $activity_date
            ]);
        }
        
        $url = sprintf('%s/%s/activities?startDate=%s&endDate=%s', 
            self::ENDPOINT_PROFILE_PETS, $pet->id, '2021-08-01', '2021-08-31');

        $this
            ->withHeaders([
                'Accept' => parent::APPLICATION_JSON,
                'Authorization' => $this->token
            ])
            ->get($url)
            ->assertStatus(200)
            ->assertJsonCount(3, 'activities')
            ->assertJsonStructure([
                'activities' =>  [['id', 'activity_date', 'score']]
            ]);
    }

    /**
     * Deve retornar lista de atividades dos pets sem informar parâmetros
     */
    public function testGetPetActivitiesWithoutParameters()
    {
        $pet = Pet::factory()->create(['customer_id' => 1]);

        foreach (['Y-m-01', 'Y-m-d', 'Y-m-t', 'Y-07-31'] as $activity_date) {
            Activity::factory()->create([
                'pet_id'        => $pet->id,
                'activity_date' => date($activity_date)
            ]);
        }
        
        $this
            ->withHeaders([
                'Accept' => parent::APPLICATION_JSON,
                'Authorization' => $this->token
            ])
            ->get(sprintf('%s/%s/activities', self::ENDPOINT_PROFILE_PETS, $pet->id))
            ->assertStatus(200)
            ->assertJsonCount(3, 'activities')
            ->assertJsonStructure([
                'activities' =>  [['id', 'activity_date', 'score']]
            ]);
    }

    /**
     * Deve retornar uma atividade de um pet
     */
    public function testGetMyPetActivity()
    {
        $pet = Pet::factory()->create(['customer_id' => 1]);

        $activity = Activity::factory()->create(['pet_id' => $pet->id]);

        $url = sprintf('%s/activities/%s', self::ENDPOINT_PROFILE_PETS, $activity->id);

        $this
            ->withHeaders([
                'Accept' => parent::APPLICATION_JSON,
                'Authorization' => $this->token
            ])
            ->get($url)
            ->assertStatus(200)
            ->assertJsonStructure([
                'activity' =>  ['id', 'activity_date', 'score', 'description', 'media']
            ]);
    }


    private function getToken() 
    {
        $password = 'Test@123';

        $customer = Customer::factory()->create([
            'active' => true,
            'password' => Hash::make($password)
        ]);

        $response = $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post('/api/login', [
                'email'    => $customer->email,
                'password' => $password
            ])
            ->getContent();
        
        $response = json_decode($response, true);

        return sprintf('%s %s', $response['token_type'], $response['access_token']);
    }
}