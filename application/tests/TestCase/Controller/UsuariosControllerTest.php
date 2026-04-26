<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class UsuariosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Usuarios',
    ];

    public function testAddCreatesUserSuccessfully(): void
    {
        $this->enableCsrfToken();

        $this->post('/usuarios/add', [
            'nome' => 'Novo Usuario',
            'login' => 'novo.usuario',
            'email' => 'novo.usuario@example.com',
            'senha' => '123456',
        ]);

        $this->assertRedirect(['controller' => 'Usuarios', 'action' => 'login']);
        $this->assertFlashMessage('Usuário criado com sucesso. Faça login para continuar.');

        /** @var \App\Model\Table\UsuariosTable $usuarios */
        $usuarios = $this->getTableLocator()->get('Usuarios');
        $usuario = $usuarios->find()->where(['login' => 'novo.usuario'])->first();

        $this->assertNotNull($usuario);
        $this->assertSame('Novo Usuario', $usuario->nome);
        $this->assertSame('novo.usuario@example.com', $usuario->email);
        $this->assertSame('ativo', $usuario->situacao);
    }
}
