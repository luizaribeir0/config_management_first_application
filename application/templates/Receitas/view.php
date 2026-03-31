<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Receita $receita
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Receita'), ['action' => 'edit', $receita->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Receita'), ['action' => 'delete', $receita->id], ['confirm' => __('Are you sure you want to delete # {0}?', $receita->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Receitas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Receita'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
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
                    <th><?= __('Tipo Receita') ?></th>
                    <td><?= h($receita->tipo_receita) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($receita->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Custo') ?></th>
                    <td><?= $this->Number->format($receita->custo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Registro') ?></th>
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