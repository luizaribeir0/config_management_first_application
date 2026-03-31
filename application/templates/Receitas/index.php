<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Receita> $receitas
 */
?>
<div class="receitas index content">
    <?= $this->Html->link(__('New Receita'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Receitas') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('nome') ?></th>
                    <th><?= $this->Paginator->sort('data_registro') ?></th>
                    <th><?= $this->Paginator->sort('custo') ?></th>
                    <th><?= $this->Paginator->sort('tipo_receita') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($receitas as $receita): ?>
                <tr>
                    <td><?= $this->Number->format($receita->id) ?></td>
                    <td><?= h($receita->nome) ?></td>
                    <td><?= h($receita->data_registro) ?></td>
                    <td><?= $this->Number->format($receita->custo) ?></td>
                    <td><?= h($receita->tipo_receita) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $receita->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $receita->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $receita->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $receita->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>