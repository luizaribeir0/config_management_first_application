<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 */
$this->assign('title', __('Criar usuário'));
?>
<div class="usuarios form content">
    <div class="users form" style="max-width: 32rem; margin: 0 auto;">
        <h3><?= __('Criar novo usuário') ?></h3>
        <?= $this->Form->create($usuario) ?>
        <fieldset>
            <legend><?= __('Preencha os dados para cadastro') ?></legend>
            <?php
            echo $this->Form->control('nome', [
                'label' => __('Nome'),
                'required' => true,
                'maxlength' => 150,
                'autofocus' => true,
            ]);
            echo $this->Form->control('login', [
                'label' => __('Login'),
                'required' => true,
                'maxlength' => 100,
                'autocomplete' => 'username',
            ]);
            echo $this->Form->control('email', [
                'label' => __('E-mail'),
                'type' => 'email',
                'required' => false,
                'maxlength' => 255,
                'autocomplete' => 'email',
            ]);
            echo $this->Form->control('senha', [
                'label' => __('Senha'),
                'type' => 'password',
                'required' => true,
                'maxlength' => 255,
                'autocomplete' => 'new-password',
            ]);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Criar usuário')) ?>
        <?= $this->Form->end() ?>
        <p style="margin-top: 1rem;">
            <?= $this->Html->link(__('Voltar para login'), ['controller' => 'Usuarios', 'action' => 'login']) ?>
        </p>
    </div>
</div>
