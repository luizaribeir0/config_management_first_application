<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Receita> $receitas
 */
?>
<div class="receitas index content">
    <?= $this->Html->link(__('Nova Receita'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Receitas') ?></h3>
    <div class="receitas-filtros">
        <?= $this->Form->create(null, ['type' => 'get']) ?>
        <fieldset>
            <legend><?= __('Filtros') ?></legend>
            <?= $this->Form->control('nome', [
                'label' => __('Nome'),
                'value' => $filtros['nome'] ?? '',
                'required' => false,
            ]) ?>
            <?= $this->Form->control('tipo_receita', [
                'label' => __('Tipo de receita'),
                'type' => 'select',
                'empty' => __('Todos'),
                'options' => [
                    'doce' => __('Doce'),
                    'salgada' => __('Salgada'),
                ],
                'value' => $filtros['tipo_receita'] ?? '',
                'required' => false,
            ]) ?>
            <?= $this->Form->control('custo_min', [
                'label' => __('Custo minimo'),
                'type' => 'number',
                'step' => '0.01',
                'value' => $filtros['custo_min'] ?? '',
                'required' => false,
            ]) ?>
            <?= $this->Form->control('custo_max', [
                'label' => __('Custo maximo'),
                'type' => 'number',
                'step' => '0.01',
                'value' => $filtros['custo_max'] ?? '',
                'required' => false,
            ]) ?>
            <?= $this->Form->control('data_registro_inicio', [
                'label' => __('Data de registro (inicio)'),
                'type' => 'date',
                'value' => $filtros['data_registro_inicio'] ?? '',
                'required' => false,
                'empty' => true,
            ]) ?>
            <?= $this->Form->control('data_registro_fim', [
                'label' => __('Data de registro (fim)'),
                'type' => 'date',
                'value' => $filtros['data_registro_fim'] ?? '',
                'required' => false,
                'empty' => true,
            ]) ?>
        </fieldset>
        <div>
            <?= $this->Form->button(__('Aplicar')) ?>
            <?= $this->Html->link(__('Limpar'), ['action' => 'index'], ['class' => 'button']) ?>
            <?= $this->Html->link(
                __('Exportar PDF'),
                ['action' => 'exportPdf', '?' => $this->request->getQueryParams()],
                ['class' => 'button']
            ) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('nome') ?></th>
                    <th><?= $this->Paginator->sort('data_registro') ?></th>
                    <th><?= $this->Paginator->sort('custo') ?></th>
                    <th><?= $this->Paginator->sort('tipo_receita') ?></th>
                    <th class="actions"><?= __('Acoes') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($receitas as $receita): ?>
                <tr>
                    <td><?= $this->Number->format($receita->id) ?></td>
                    <td><?= h($receita->nome) ?></td>
                    <td><?= $receita->data_registro ? h($receita->data_registro->format('d/m/Y H:i:s')) : '' ?></td>
                    <td><?= 'R$ ' . h(number_format((float)$receita->custo, 2, ',', '.')) ?></td>
                    <td><?= h(ucfirst((string)$receita->tipo_receita)) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Visualizar'), ['action' => 'view', $receita->id]) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $receita->id]) ?>
                        <?= $this->Form->postLink(
                            __('Excluir'),
                            ['action' => 'delete', $receita->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Tem certeza que deseja excluir #{0}?', $receita->id),
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
            <?= $this->Paginator->first('<< ' . __('primeira')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('proxima') . ' >') ?>
            <?= $this->Paginator->last(__('ultima') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Pagina {{page}} de {{pages}}, exibindo {{current}} registro(s) de {{count}} no total')) ?></p>
    </div>
</div>