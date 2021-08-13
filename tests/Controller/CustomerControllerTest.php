<?php

namespace Tests\Controller;

use App\Models\User;
use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    private $rulesMessages;

    public function setUp(): void
    {
        parent::setUp();
        $this->rulesMessages = (new CustomerRequest())->messages();
    }

    /**
     * Deve redirecionar para página de login quando não está autenticado.
     */
    public function testOnlyAuthenticatedUsersCanSeeCustomers(): void
    {
        $this->get('/customers')
             ->assertRedirect('/login');
    }

    /**
     * Deve listar clientes
     */
    public function testListCustomers(): void
    {

        $this
            ->actingAs(User::factory()->create())
            ->get('/customers')
            ->assertOk();
    }

    /**
     * Deve exibir a tela para cadastrar um novo cliente
     */
    public function testCustomerCreate()
    {
        $this
            ->actingAs(User::factory()->create())
            ->get('/customer/create')
            ->assertOk();
    }

    /**
     * Deve salvar um novo cliente
     */
    public function testStoreCustomer(): void
    {

        $file = UploadedFile::fake()->create('test.pdf', 4000);

        $data = [
            'name'     => 'Fernando',
            'email'    => 'fernando.mariano@gmail.com',
            'phone'    => '47989940098',
            'contract' => $file
        ];

        $this
            ->actingAs(User::factory()->create())
            ->post('/customer/store', $data)
            ->assertRedirect('/customers');

        unset($data['contract']);

        $this->assertDatabaseHas('customer', $data);
        $this->assertDatabaseHas('media', ['file_name' => $file->name]);
    }

    /**
     * Deve exibir tela para editar um cliente
     */
    public function testCustomerEdit()
    {
        $customer = Customer::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/customer/%s/edit', $customer->id))
            ->assertOk();
    }

    /**
     * Deve exibir erro de página não encontrada
     */
    public function testError4040CustomerEdit()
    {
        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/customer/%s/edit', 1))
            ->assertNotFound();
    }

    /**
     * Deve salvar as alterações de um cliente
     */
    public function testUpdateCustomer(): void
    {

        $customer = Customer::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 4000);

        $data = [
            'name'     => 'Caroline Dirschnabel',
            'email'    => 'carol.dirsch@gmail.com',
            'active'   => true,
            'phone'    => '47986292309',
            'contract' => $file
        ];

        $this
            ->actingAs(User::factory()->create())
            ->put(sprintf('/customer/%s/update', $customer->id), $data)
            ->assertRedirect('/customers');

        unset($data['contract']);

        $this->assertDatabaseHas('customer', $data);
        $this->assertDatabaseHas('media', ['file_name' => $file->name]);
    }

    /**
     * Deve excluir um cliente
     */
    public function testDestroyCustomer(): void
    {

        $customer = Customer::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->delete(sprintf('/customer/%s/destroy', $customer->id))
            ->assertRedirect('/customers');

        $this->assertDatabaseMissing('customer', ['id' => $customer->id]);
    }

    /**
     * Deve exibir tela com informações de um cliente
     */
    public function testCustomerShow()
    {
        $customer = Customer::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/customer/%s/show', $customer->id))
            ->assertOk();
    }

    /**
     * Deve exibir erro de página não encontrada
     */
    public function testError4040CustomerShow()
    {
        $this
            ->actingAs(User::factory()->create())
            ->get(sprintf('/customer/%s/show', 1))
            ->assertNotFound();
    }

    /**
     * Deve efetuar a validação de campos obrigatórios do cliente
     */
    public function testCustomerRequiredFields(): void
    {

        $response = $this
            ->actingAs(User::factory()->create())
            ->post('/customer/store', []);

        $response->assertSessionHasErrors([
            'name' => $this->rulesMessages['name.required'],
            'email' => $this->rulesMessages['email.required'],
            'phone' => $this->rulesMessages['phone.required']
        ]);
    }

    /**
     * Deve efetuar a validação de e-mail único
     */
    public function testCustomerUniqueEmail(): void
    {

        $customer = Customer::factory()->create();

        $data = [
            'name'     => 'Caroline Dirschnabel',
            'email'    => $customer->email,
            'active'   => true,
            'phone'    => '47986292309'
        ];

        $response = $this
            ->actingAs(User::factory()->create())
            ->post('/customer/store', $data);

        $response->assertSessionHasErrors([
            'email' => $this->rulesMessages['email.unique']
        ]);
    }

    /**
     * Deve validar extensão do arquivo de contrato
     */
    public function testCustomerContractExtension(): void
    {

        $file = UploadedFile::fake()->create('tests/test.txt');

        $data = [
            'name'     => 'Caroline Dirschnabel',
            'email'    => 'carol.dirsch@gmail.com',
            'active'   => true,
            'phone'    => '47986292309',
            'contract' => $file
        ];

        $response = $this
            ->actingAs(User::factory()->create())
            ->post('/customer/store', $data);

        $response->assertSessionHasErrors([
            'contract' => $this->rulesMessages['contract.mimes']
        ]);
    }

    /**
     * Deve validar tamanho do arquivo de contrato
     */
    public function testCustomerContractSize(): void
    {

        $file = UploadedFile::fake()->create('tests/test.pdf', 6000);

        $data = [
            'name'     => 'Caroline Dirschnabel',
            'email'    => 'carol.dirsch@gmail.com',
            'active'   => true,
            'phone'    => '47986292309',
            'contract' => $file
        ];

        $response = $this
            ->actingAs(User::factory()->create())
            ->post('/customer/store', $data);

        $response->assertSessionHasErrors([
            'contract' => $this->rulesMessages['contract.max']
        ]);
    }
}
