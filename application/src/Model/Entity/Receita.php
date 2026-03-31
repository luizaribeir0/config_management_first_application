<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Receita Entity
 *
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property \Cake\I18n\DateTime|null $data_registro
 * @property string $custo
 * @property string $tipo_receita
 */
class Receita extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'nome' => true,
        'descricao' => true,
        'data_registro' => true,
        'custo' => true,
        'tipo_receita' => true,
    ];
}
