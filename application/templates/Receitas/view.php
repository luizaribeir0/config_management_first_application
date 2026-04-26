<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Receita $receita
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acoes') ?></h4>
            <?= $this->Html->link(__('Editar Receita'), ['action' => 'edit', $receita->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Excluir Receita'), ['action' => 'delete', $receita->id], ['confirm' => __('Tem certeza que deseja excluir #{0}?', $receita->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Listar Receitas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Nova Receita'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="receitas view content">
            <h3><?= h($receita->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($receita->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tipo de Receita') ?></th>
                    <td><?= h($receita->tipo_receita) ?></td>
                </tr>
                <tr>
                    <th><?= __('ID') ?></th>
                    <td><?= $this->Number->format($receita->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Custo') ?></th>
                    <td><?= $this->Number->format($receita->custo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data de Registro') ?></th>
                    <td><?= h($receita->data_registro) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Descricao') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($receita->descricao)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>