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
            <?= $this->Form->postLink(
                __('Excluir'),
                ['action' => 'delete', $receita->id],
                ['confirm' => __('Tem certeza que deseja excluir #{0}?', $receita->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('Listar Receitas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="receitas form content">
            <?= $this->Form->create($receita) ?>
            <fieldset>
                <legend><?= __('Editar Receita') ?></legend>
                <?php
                    echo $this->Form->control('nome');
                    echo $this->Form->control('descricao');
                    echo $this->Form->control('data_registro', ['empty' => true]);
                    echo $this->Form->control('custo');
                    echo $this->Form->control('tipo_receita', [
                        'type' => 'select',
                        'options' => [
                            'doce' => __('Doce'),
                            'salgada' => __('Salgada'),
                        ],
                    ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Salvar')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
