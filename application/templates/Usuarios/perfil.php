<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 */
$this->assign('title', __('Meu Perfil'));
?>
<div class="usuarios form content">
    <div class="users form" style="max-width: 34rem; margin: 0 auto;">
        <h3><?= __('Meu Perfil') ?></h3>
        <?= $this->Form->create($usuario) ?>
        <fieldset>
            <legend><?= __('Atualize seus dados') ?></legend>
            <?php
            echo $this->Form->control('nome', [
                'label' => __('Nome'),
                'required' => true,
            ]);
            echo $this->Form->control('email', [
                'label' => __('E-mail'),
                'type' => 'email',
                'required' => false,
            ]);
            echo $this->Form->control('senha', [
                'label' => __('Nova senha'),
                'type' => 'password',
                'required' => false,
                'value' => '',
                'autocomplete' => 'new-password',
                'help' => __('Deixe em branco para manter a senha atual.'),
            ]);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Salvar')) ?>
        <?= $this->Html->link(__('Cancelar'), ['controller' => 'Receitas', 'action' => 'index'], ['class' => 'button button-clear']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
