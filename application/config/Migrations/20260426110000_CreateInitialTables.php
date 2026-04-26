<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateInitialTables extends BaseMigration
{
    /**
     * @return void
     */
    public function change(): void
    {
        if (!$this->hasTable('usuarios')) {
            $this->table('usuarios')
                ->addColumn('nome', 'string', [
                    'limit' => 150,
                    'null' => false,
                ])
                ->addColumn('login', 'string', [
                    'limit' => 100,
                    'null' => false,
                ])
                ->addColumn('senha', 'string', [
                    'limit' => 255,
                    'null' => false,
                ])
                ->addColumn('situacao', 'string', [
                    'limit' => 20,
                    'default' => 'ativo',
                    'null' => false,
                ])
                ->addIndex(['login'], ['unique' => true])
                ->create();
        }

        if (!$this->hasTable('receitas')) {
            $this->table('receitas')
                ->addColumn('nome', 'string', [
                    'limit' => 150,
                    'null' => false,
                ])
                ->addColumn('descricao', 'text', [
                    'null' => true,
                ])
                ->addColumn('data_registro', 'datetime', [
                    'null' => true,
                ])
                ->addColumn('custo', 'decimal', [
                    'precision' => 10,
                    'scale' => 2,
                    'null' => false,
                ])
                ->addColumn('tipo_receita', 'string', [
                    'limit' => 20,
                    'null' => false,
                ])
                ->create();
        }
    }
}
