<?php 

namespace Tests\Controller\Api;

use App\Http\Requests\PasswordRequest;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->rulesMessages = (new PasswordRequest())->messages();
    }

    /**
     * Deve efetuar obter o token de login de um cliente
     */
    public function testLogin(): void
    {
        $password = 'Test@1234';

        $customer = Customer::factory()->create([
            'active' => true,
            'password' => Hash::make($password)
        ]);

        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/login', [
                'email'    => $customer->email,
                'password' => $password
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);        
    }

    /**
     * Deve efetuar obter o token de login de um cliente
     */
    public function testLogout(): void
    {
        $password = 'Test@1234';

        $customer = Customer::factory()->create([
            'active' => true,
            'password' => Hash::make($password)
        ]);

        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/login', [
                'email'    => $customer->email,
                'password' => $password
            ])
            ->getContent();
        
        $response = json_decode($response, true);
        
        $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => sprintf('%s %s', $response['token_type'], $response['access_token'])
            ])
            ->post('/api/logout')
            ->assertStatus(200);              
    }

    /**
     * Deve informar que o cliente não foi encontrado
     */
    public function testLoginCustomerNotFound(): void
    {        
        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/login', [
                'email'    => 'fernando.mariano@test.com',
                'password' => 'Test@2021'
            ])
            ->assertStatus(404)
            ->assertJson(['success' => false]);
    }

    /**
     * Deve informar que o cliente não está ativo
     */
    public function testLoginCustomerNotActive(): void
    {
        $password = 'Test@1234';

        $customer = Customer::factory()->create([
            'active' => false,
            'password' => Hash::make($password)
        ]);

        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/login', [
                'email'    => $customer->email,
                'password' => $customer->password
            ])
            ->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    
    /**
     * Deve efetuar a validação de campos obrigatórios
     */
    public function testLoginCustomerRequiredFields(): void
    {        
        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('api/login', [])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [$this->rulesMessages['email.required']],
                    'password' => [$this->rulesMessages['password.required']]
                ]
            ]);
    }

    /**
     * Deve informar que o token não foi preenchido
     */
    public function testLogoutNullToken()
    {
        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/logout')
            ->assertStatus(400)
            ->assertJson(['success' => false]);
    }
}