<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ReceitasController Test Case
 *
 * @link \App\Controller\ReceitasController
 */
class ReceitasControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Receitas',
        'app.Usuarios',
    ];

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Configure::write('Receitas.notificationEmail', '');
    }

    /**
     * @return void
     */
    private function loginAsActiveUser(): void
    {
        $this->session([
            'Auth' => [
                'id' => 1,
                'nome' => 'Usuario Ativo',
                'login' => 'usuario.ativo',
                'situacao' => 'ativo',
            ],
        ]);
    }

    /**
     * Test index method
     *
     * @return void
     * @link \App\Controller\ReceitasController::index()
     */
    public function testIndex(): void
    {
        $this->loginAsActiveUser();
        $this->get('/receitas');

        $this->assertResponseOk();
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
    }

    /**
     * Test view method
     *
     * @return void
     * @link \App\Controller\ReceitasController::view()
     */
    public function testView(): void
    {
        $this->loginAsActiveUser();
        $this->get('/receitas/view/1');

        $this->assertResponseOk();
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
    }

    /**
     * Test add method
     *
     * @return void
     * @link \App\Controller\ReceitasController::add()
     */
    public function testAdd(): void
    {
        $this->loginAsActiveUser();
        $this->enableCsrfToken();

        $this->post('/receitas/add', [
            'nome' => 'Torta de Frango',
            'descricao' => 'Receita de teste',
            'data_registro' => '2026-04-26 14:00:00',
            'custo' => 32.5,
            'tipo_receita' => 'salgada',
        ]);

        $this->assertRedirect(['controller' => 'Receitas', 'action' => 'index']);
        $this->assertFlashMessage('Receita salva com sucesso.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \App\Controller\ReceitasController::edit()
     */
    public function testEdit(): void
    {
        $this->loginAsActiveUser();
        $this->enableCsrfToken();

        $this->put('/receitas/edit/1', [
            'nome' => 'Receita Editada',
            'descricao' => 'Descricao atualizada',
            'data_registro' => '2026-03-31 22:41:33',
            'custo' => 25.9,
            'tipo_receita' => 'doce',
        ]);

        $this->assertRedirect(['controller' => 'Receitas', 'action' => 'index']);
        $this->assertFlashMessage('Receita atualizada com sucesso.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \App\Controller\ReceitasController::delete()
     */
    public function testDelete(): void
    {
        $this->loginAsActiveUser();
        $this->enableCsrfToken();

        $this->post('/receitas/delete/1');

        $this->assertRedirect(['controller' => 'Receitas', 'action' => 'index']);
        $this->assertFlashMessage('Receita excluida com sucesso.');
    }
}
