<?php 

namespace Tests\Controller\Api;

use App\Http\Requests\PasswordRequest;
use App\Models\Customer;
use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PetControllerTest extends TestCase
{
    use RefreshDatabase;

    private $token;
    
    public function setUp(): void
    {
        parent::setUp();
        
        $this->token = $this->getToken();
    }

    /**
     * Deve retornar lista com pets de um cliente
     */
    public function testGetMyPets(): void
    {
        Pet::factory(5)->create(['customer_id' => 1]);
                
        $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => $this->token
            ])
            ->get('/api/my-pets')
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
                'Accept' => 'application/json',
                'Authorization' => $this->token
            ])
            ->get('/api/my-pets')
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
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/login', [
                'email'    => $customer->email,
                'password' => $password
            ])
            ->getContent();
        
        $response = json_decode($response, true);

        return sprintf('%s %s', $response['token_type'], $response['access_token']);
    }

}