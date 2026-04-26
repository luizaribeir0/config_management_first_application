<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuariosTable;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\TestSuite\TestCase;

class UsuariosTableTest extends TestCase
{
    protected UsuariosTable $Usuarios;

    protected array $fixtures = [
        'app.Usuarios',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->Usuarios = $this->getTableLocator()->get('Usuarios');
    }

    protected function tearDown(): void
    {
        unset($this->Usuarios);
        parent::tearDown();
    }

    public function testCreateUserPersistsAndReturnsId(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Maria',
            'login' => 'maria.persist',
            'email' => 'maria.persist@example.com',
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $result = $this->Usuarios->save($entity);

        $this->assertNotFalse($result);
        $this->assertNotEmpty($entity->id);
    }

    public function testEmailIsOptionalButSavedWhenPresent(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Joao',
            'login' => 'joao.email',
            'email' => 'joao@email.com',
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $this->Usuarios->saveOrFail($entity);

        $this->assertSame('joao@email.com', $entity->email);
    }

    public function testInvalidEmailPreventsSave(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Email Invalido',
            'login' => 'email.invalido',
            'email' => 'invalido',
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $this->assertFalse($this->Usuarios->save($entity));
        $this->assertArrayHasKey('email', $entity->getErrors());
    }

    public function testPasswordIsHashedOnSave(): void
    {
        $plain = 'minha-senha';

        $entity = $this->Usuarios->newEntity([
            'nome' => 'Hash Test',
            'login' => 'hash.test',
            'senha' => $plain,
            'situacao' => 'ativo',
        ]);

        $this->Usuarios->saveOrFail($entity);

        $this->assertNotSame($plain, $entity->senha);

        $hasher = new DefaultPasswordHasher();
        $this->assertTrue($hasher->check($plain, $entity->senha));
    }

    public function testLoginMustBeUnique(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Duplicado',
            'login' => 'usuario.ativo',
            'senha' => '123456',
            'situacao' => 'ativo',
        ]);

        $this->assertFalse($this->Usuarios->save($entity));
        $this->assertArrayHasKey('_isUnique', $entity->getError('login'));
    }

    public function testSituacaoMustBeValid(): void
    {
        $entity = $this->Usuarios->newEntity([
            'nome' => 'Teste',
            'login' => 'situacao.teste',
            'senha' => '123456',
            'situacao' => 'invalido',
        ]);

        $this->assertFalse($this->Usuarios->save($entity));
        $this->assertArrayHasKey('situacao', $entity->getErrors());
    }

    public function testFindActiveReturnsOnlyActive(): void
    {
        $result = $this->Usuarios->find('active')->all();

        foreach ($result as $user) {
            $this->assertSame('ativo', $user->situacao);
        }
    }

    public function testInactiveUserIsNotReturnedInActiveFinder(): void
    {
        $user = $this->Usuarios
            ->find('active')
            ->where(['login' => 'usuario.inativo'])
            ->first();

        $this->assertNull($user);
    }

    public function testCanUpdateUserData(): void
    {
        $user = $this->Usuarios->find()->first();

        $user->nome = 'Nome Atualizado';
        $this->Usuarios->saveOrFail($user);

        $updated = $this->Usuarios->get($user->id);

        $this->assertSame('Nome Atualizado', $updated->nome);
    }

    public function testPasswordValidationDuringLogin(): void
    {
        $plain = 'senha-login';

        $entity = $this->Usuarios->newEntity([
            'nome' => 'Login User',
            'login' => 'login.user',
            'senha' => $plain,
            'situacao' => 'ativo',
        ]);

        $this->Usuarios->saveOrFail($entity);

        $user = $this->Usuarios
            ->find('active')
            ->where(['login' => 'login.user'])
            ->first();

        $hasher = new DefaultPasswordHasher();

        $this->assertTrue($hasher->check($plain, $user->senha));
        $this->assertFalse($hasher->check('errada', $user->senha));
    }
}
