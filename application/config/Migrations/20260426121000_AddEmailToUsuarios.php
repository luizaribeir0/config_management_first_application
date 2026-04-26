<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddEmailToUsuarios extends BaseMigration
{
    /**
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('usuarios');

        if (!$table->hasColumn('email')) {
            $table
                ->addColumn('email', 'string', [
                    'limit' => 255,
                    'null' => true,
                    'after' => 'login',
                ])
                ->update();
        }
    }
}
