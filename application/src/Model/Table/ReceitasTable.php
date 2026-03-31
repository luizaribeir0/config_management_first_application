<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Receitas Model
 *
 * @method \App\Model\Entity\Receita newEmptyEntity()
 * @method \App\Model\Entity\Receita newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Receita> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Receita get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Receita findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Receita patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Receita> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Receita|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Receita saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Receita>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Receita>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Receita>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Receita> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Receita>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Receita>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Receita>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Receita> deleteManyOrFail(iterable $entities, array $options = [])
 */
class ReceitasTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('receitas');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('nome')
            ->maxLength('nome', 150)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->dateTime('data_registro')
            ->allowEmptyDateTime('data_registro');

        $validator
            ->decimal('custo')
            ->requirePresence('custo', 'create')
            ->notEmptyString('custo');

        $validator
            ->scalar('tipo_receita')
            ->requirePresence('tipo_receita', 'create')
            ->notEmptyString('tipo_receita')
            ->inList('tipo_receita', ['doce', 'salgada']);

        return $validator;
    }
}
