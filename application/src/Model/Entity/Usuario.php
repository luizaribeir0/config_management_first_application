<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Usuario Entity
 *
 * @property int $id
 * @property string $nome
 * @property string $login
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
        'senha' => true,
        'situacao' => true,
    ];

    /**
     * @var list<string>
     */
    protected array $_hidden = [
        'senha',
    ];
}
