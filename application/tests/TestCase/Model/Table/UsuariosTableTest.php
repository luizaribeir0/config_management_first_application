<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuariosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsuariosTable Test Case
 */
class UsuariosTableTest extends TestCase
{
    /**
     * @var \App\Model\Table\UsuariosTable
     */
    protected UsuariosTable $Usuarios;

    /**
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Usuarios',
    ];

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Usuarios') ? [] : ['className' => UsuariosTable::class];
        /** @var \App\Model\Table\UsuariosTable $usuarios */
        $usuarios = $this->getTableLocator()->get('Usuarios', $config);
        $this->Usuarios = $usuarios;
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Usuarios);
        parent::tearDown();
    }

    public function testValidationDefaultAcceptsValidDataWithEmail(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Maria',
            'login' => 'maria',
            'email' => 'maria@example.com',
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $this->assertSame([], $entity->getErrors());
    }

    public function testValidationDefaultAcceptsValidDataWithoutEmail(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Jose',
            'login' => 'jose',
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $this->assertArrayNotHasKey('email', $entity->getErrors());
    }

    public function testValidationDefaultRejectsInvalidEmail(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Ana',
            'login' => 'ana',
            'email' => 'email-invalido',
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $this->assertArrayHasKey('email', $entity->getErrors());
    }

    public function testValidationDefaultRejectsEmailLongerThan255(): void
    {
        $email = str_repeat('a', 250) . '@x.com';
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Ana',
            'login' => 'ana2',
            'email' => $email,
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $this->assertArrayHasKey('email', $entity->getErrors());
    }

    public function testValidationDefaultRequiresNomeOnCreate(): void
    {
        $entity = $this->Usuarios->newEntity([
            'login' => 'semnome',
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $this->assertArrayHasKey('nome', $entity->getErrors());
    }

    public function testValidationDefaultRequiresLoginOnCreate(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Sem Login',
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $this->assertArrayHasKey('login', $entity->getErrors());
    }

    public function testValidationDefaultRequiresSenhaOnCreate(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Sem Senha',
            'login' => 'semsenha',
            'situacao' => 'ativo',
        ]);

        $this->assertArrayHasKey('senha', $entity->getErrors());
    }

    public function testValidationDefaultRejectsInvalidSituacao(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Situacao Invalida',
            'login' => 'situacao.invalida',
            'senha' => '123456',
            'situacao' => 'pendente',
        ]);

        $this->assertArrayHasKey('situacao', $entity->getErrors());
    }

    public function testBuildRulesRejectsDuplicateLogin(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Duplicado',
            'login' => 'usuario.ativo',
            'email' => 'duplicado@example.com',
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $result = $this->Usuarios->save($entity);

        $this->assertFalse($result);
        $this->assertArrayHasKey('_isUnique', $entity->getError('login'));
    }

    public function testFindActiveReturnsOnlyActiveUsers(): void
    {
        $result = $this->Usuarios
            ->find('active')
            ->enableHydration(false)
            ->all()
            ->toList();

        $this->assertCount(1, $result);
        $this->assertSame('ativo', $result[0]['situacao']);
        $this->assertSame('usuario.ativo', $result[0]['login']);
    }
}
