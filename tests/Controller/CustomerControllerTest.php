<?php 

namespace Tests\Controller;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase {

    use RefreshDatabase;

    public function testOnlyAuthenticatedUsersCanSeeCustomers() {
        $this->get('/customers')             
             ->assertRedirect('/login');
    }

    public function testListCustomers() {
        
        $this->actingAs(User::factory()->create());
        
        $this->get('/customers')->assertOk();
    }

    public function testCreateCustomer() {

        $data = [
            'name'     => 'Fernando',
            'email'    => 'fernando.mariano@gmail.com',
            'active'   => true,
            'phone'    => '47989940098'
        ];

        $this
            ->actingAs(User::factory()->create())
            ->post('/customer/store', $data)
            ->assertRedirect('/customers');

        $this->assertDatabaseHas('customer', $data);
    }
}