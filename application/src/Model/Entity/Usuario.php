<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * Usuario Entity
 *
 * @property int $id
 * @property string $nome
 * @property string $login
 * @property string|null $email
 * @property string $senha
 * @property string $situacao
 */
class Usuario extends Entity
{
    /**
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'nome' => true,
        'login' => true,
        'email' => true,
        'senha' => true,
        'situacao' => true,
    ];

    /**
     * @var list<string>
     */
    protected array $_hidden = [
        'senha',
    ];

    /**
     * Hash password before persistence.
     *
     * @param string $password Plain password.
     * @return string
     */
    protected function _setSenha(string $password): string
    {
        return (new DefaultPasswordHasher())->hash($password);
    }
}
