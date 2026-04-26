<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceitasTable;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceitasTable Test Case
 */
class ReceitasTableTest extends TestCase
{
    use EmailTrait;

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
    public function testValidationDefaultAcceptsValidData(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Bolo de Cenoura',
            'descricao' => 'Com cobertura de chocolate',
            'data_registro' => '2026-04-26 10:00:00',
            'custo' => 24.90,
            'tipo_receita' => 'doce',
        ]);

        $this->assertSame([], $entity->getErrors());
    }

    public function testValidationDefaultRequiresNomeOnCreate(): void
    {
        $entity = $this->Receitas->newEntity([
            'custo' => 10,
            'tipo_receita' => 'doce',
        ]);

        $this->assertArrayHasKey('nome', $entity->getErrors());
    }

    public function testValidationDefaultRejectsEmptyNome(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => '',
            'custo' => 10,
            'tipo_receita' => 'doce',
        ]);

        $this->assertArrayHasKey('nome', $entity->getErrors());
    }

    public function testValidationDefaultRequiresCustoOnCreate(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Pao de Queijo',
            'tipo_receita' => 'salgada',
        ]);

        $this->assertArrayHasKey('custo', $entity->getErrors());
    }

    public function testValidationDefaultRejectsInvalidCusto(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Pao de Queijo',
            'custo' => 'abc',
            'tipo_receita' => 'salgada',
        ]);

        $this->assertArrayHasKey('custo', $entity->getErrors());
    }

    public function testValidationDefaultRequiresTipoReceitaOnCreate(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Empada',
            'custo' => 8,
        ]);

        $this->assertArrayHasKey('tipo_receita', $entity->getErrors());
    }

    public function testValidationDefaultRejectsInvalidTipoReceita(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Empada',
            'custo' => 8,
            'tipo_receita' => 'agridoce',
        ]);

        $this->assertArrayHasKey('tipo_receita', $entity->getErrors());
    }

    public function testValidationDefaultAcceptsTipoReceitaDoce(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Brigadeiro',
            'custo' => 3.5,
            'tipo_receita' => 'doce',
        ]);

        $this->assertArrayNotHasKey('tipo_receita', $entity->getErrors());
    }

    public function testValidationDefaultAcceptsTipoReceitaSalgada(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Esfiha',
            'custo' => 6,
            'tipo_receita' => 'salgada',
        ]);

        $this->assertArrayNotHasKey('tipo_receita', $entity->getErrors());
    }

    public function testValidationDefaultRejectsInvalidDataRegistro(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Quiche',
            'custo' => 30,
            'tipo_receita' => 'salgada',
            'data_registro' => 'data-invalida',
        ]);

        $this->assertArrayHasKey('data_registro', $entity->getErrors());
    }

    public function testAfterSaveCommitSendsNotificationEmail(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Cuscuz Paulista',
            'descricao' => 'Teste de envio',
            'data_registro' => '2026-04-26 15:00:00',
            'custo' => 18.75,
            'tipo_receita' => 'salgada',
        ]);

        $result = $this->Receitas->save($entity, [
            'actorEmail' => 'teste@example.com',
            'notificationAction' => 'criada',
        ]);

        $this->assertNotFalse($result);
        $this->assertMailCount(1);
        $this->assertMailSentTo('teste@example.com');
        $this->assertMailSubjectContains('Cuscuz Paulista');
        $this->assertMailContainsText('A receita abaixo foi criada com sucesso.');
    }
}
