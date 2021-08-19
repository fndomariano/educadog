<?php 

namespace Tests\Controller\Api;

use App\Http\Requests\PasswordRequest;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->rulesMessages = (new PasswordRequest())->messages();
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

        $data = [
            'email'    => $customer->email,
            'password' => 'Test@123'
        ];

        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/password/create', $data)
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
        $data = [
            'email'    => 'fernando.mariano@test.com',
            'password' => 'Test@2021'
        ];

        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/password/create', $data)
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

        $data = [
            'email'    => $customer->email,
            'password' => 'Test@2021'
        ];

        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/password/create', $data)
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
            'password' => 'Test@123'
        ]);

        $data = [
            'email'    => $customer->email,
            'password' => 'Test@2021'
        ];

        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/password/create', $data)
            ->assertStatus(419)
            ->assertJson(['success' => false]);
    }

    /**
     * Deve efetuar a validação de campos obrigatórios
     */
    public function testCreatePasswordRequiredFields(): void
    {        
        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post('api/password/create', [])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [$this->rulesMessages['email.required']],
                    'password' => [$this->rulesMessages['password.required']]
                ]
            ]);
    }
}
