<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceitasTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceitasTable Test Case
 */
class ReceitasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ReceitasTable
     */
    protected $Receitas;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Receitas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Receitas') ? [] : ['className' => ReceitasTable::class];
        $this->Receitas = $this->getTableLocator()->get('Receitas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Receitas);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ReceitasTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
