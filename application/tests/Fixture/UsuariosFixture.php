<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuariosFixture
 */
class UsuariosFixture extends TestFixture
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'nome' => 'Usuario Ativo',
                'login' => 'usuario.ativo',
                'email' => 'ativo@example.com',
                'senha' => 'hash-qualquer',
                'situacao' => 'ativo',
            ],
            [
                'id' => 2,
                'nome' => 'Usuario Inativo',
                'login' => 'usuario.inativo',
                'email' => 'inativo@example.com',
                'senha' => 'hash-qualquer',
                'situacao' => 'inativo',
            ],
        ];
        parent::init();
    }
}
