<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\I18n\FrozenTime;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Throwable;

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

    /**
     * Sends notification e-mail after create/update commits.
     *
     * @param \Cake\Event\EventInterface $event The event instance.
     * @param \Cake\Datasource\EntityInterface $entity The persisted entity.
     * @param \ArrayObject<string, mixed> $options Save options.
     * @return void
     */
    public function afterSaveCommit(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $action = (string)($options['notificationAction'] ?? '');
        if (!in_array($action, ['criada', 'atualizada'], true)) {
            $action = 'atualizada';
        }
        $this->sendReceitaNotification($entity, $action, $options);
    }

    /**
     * Sends notification e-mail after delete commits.
     *
     * @param \Cake\Event\EventInterface $event The event instance.
     * @param \Cake\Datasource\EntityInterface $entity The deleted entity.
     * @param \ArrayObject<string, mixed> $options Delete options.
     * @return void
     */
    public function afterDeleteCommit(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->sendReceitaNotification($entity, 'removida', $options);
    }

    /**
     * @param \Cake\Datasource\EntityInterface $entity
     * @param string $action
     * @param \ArrayObject<string, mixed> $options
     * @return void
     */
    private function sendReceitaNotification(EntityInterface $entity, string $action, ArrayObject $options): void
    {
        $recipient = (string)($options['actorEmail'] ?? '');
        if ($recipient === '') {
            $recipient = (string)Configure::read('Receitas.notificationEmail', '');
        }
        if ($recipient === '') {
            return;
        }

        $dataRegistro = $entity->get('data_registro');
        $dataRegistroFormatada = '-';
        if ($dataRegistro instanceof FrozenTime) {
            $dataRegistroFormatada = $dataRegistro->setTimezone(date_default_timezone_get())->format('d/m/Y H:i:s');
        }

        $custoValor = $entity->get('custo');
        $custoFormatado = is_numeric($custoValor) ? 'R$ ' . number_format((float)$custoValor, 2, ',', '.') : '-';

        try {
            (new Mailer('default'))
                ->setTo($recipient)
                ->setSubject(sprintf(
                    '[Receitas] %s (%s)',
                    (string)($entity->get('nome') ?: $entity->get('id')),
                    ucfirst($action),
                ))
                ->deliver(sprintf(
                    "Notificacao de receita\n\nA receita abaixo foi %s com sucesso.\n\nID: %s\nNome: %s\nTipo: %s\nCusto: %s\nData de registro: %s\n",
                    $action,
                    (string)($entity->get('id') ?? '-'),
                    (string)($entity->get('nome') ?? '-'),
                    (string)($entity->get('tipo_receita') ?? '-'),
                    $custoFormatado,
                    $dataRegistroFormatada,
                ));
        } catch (Throwable $exception) {
            Log::warning(
                sprintf(
                    'Falha no envio de e-mail para receita ID %s: %s',
                    (string)($entity->get('id') ?? 'desconhecido'),
                    $exception->getMessage(),
                ),
            );
        }
    }
}
