<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Entrar'));
?>
<div class="usuarios form content">
    <div class="users form" style="max-width: 28rem; margin: 0 auto;">
        <h3><?= __('Entrar') ?></h3>
        <?= $this->Form->create(null, ['id' => 'form-login']) ?>
        <fieldset>
            <legend><?= __('Use seu login e senha') ?></legend>
            <?php
            echo $this->Form->control('login', [
                'label' => __('Login'),
                'required' => true,
                'autocomplete' => 'username',
                'autofocus' => true,
            ]);
            echo $this->Form->control('senha', [
                'label' => __('Senha'),
                'type' => 'password',
                'required' => true,
                'autocomplete' => 'current-password',
            ]);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Entrar')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
