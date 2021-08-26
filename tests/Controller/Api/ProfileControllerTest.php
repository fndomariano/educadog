<?php 

namespace Tests\Controller\Api;

use App\Http\Requests\PasswordRequest;
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

        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'    => $customer->email,
                'password' => 'Test@123'
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
        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'    => 'fernando.mariano@test.com',
                'password' => 'Test@abc'
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

        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'    => $customer->email,
                'password' => 'Test@2021'
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

        $this
            ->withHeaders(['Accept' => parent::APPLICATION_JSON])
            ->post(self::ENDPOINT_PROFILE_PASSWORD_CREATE, [
                'email'    => $customer->email,
                'password' => 'Test@2021'
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