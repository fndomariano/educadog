<?php 

namespace Tests\Controller;

use Tests\TestCase;

class CustomerControllerTest extends TestCase {

    public function testOnlyAuthenticatedUsersCanSeeCustomers() {
        $this->get('/customers')
             ->assertRedirect('/login');
    }
}