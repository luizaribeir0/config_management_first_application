<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceitasTable;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestCase;

class ReceitasTableTest extends TestCase
{
    use EmailTrait;

    protected ReceitasTable $Receitas;

    protected array $fixtures = [
        'app.Receitas',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->Receitas = $this->getTableLocator()->get('Receitas');
    }

    protected function tearDown(): void
    {
        unset($this->Receitas);
        parent::tearDown();
    }

    public function testCreateReceitaPersistsSuccessfully(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Bolo Teste',
            'descricao' => 'Persistência',
            'custo' => 20.5,
            'tipo_receita' => 'doce',
        ]);

        $result = $this->Receitas->save($entity);

        $this->assertNotFalse($result);
        $this->assertNotEmpty($entity->id);
    }

    public function testInvalidCustoPreventsSave(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Erro Custo',
            'custo' => 'abc',
            'tipo_receita' => 'doce',
        ]);

        $this->assertFalse($this->Receitas->save($entity));
        $this->assertArrayHasKey('custo', $entity->getErrors());
    }

    public function testNomeCannotBeEmptyOrWhitespace(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => '   ',
            'custo' => 10,
            'tipo_receita' => 'doce',
        ]);

        $this->assertFalse($this->Receitas->save($entity));
        $this->assertArrayHasKey('nome', $entity->getErrors());
    }

    public function testTipoReceitaMustBeValid(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Teste Tipo',
            'custo' => 10,
            'tipo_receita' => 'invalido',
        ]);

        $this->assertFalse($this->Receitas->save($entity));
        $this->assertArrayHasKey('tipo_receita', $entity->getErrors());
    }

    public function testAcceptsDoceAndSalgadaOnly(): void
    {
        $doce = $this->Receitas->newEntity([
            'nome' => 'Doce OK',
            'custo' => 5,
            'tipo_receita' => 'doce',
        ]);

        $salgada = $this->Receitas->newEntity([
            'nome' => 'Salgada OK',
            'custo' => 7,
            'tipo_receita' => 'salgada',
        ]);

        $this->assertEmpty($doce->getErrors());
        $this->assertEmpty($salgada->getErrors());
    }

    public function testDataRegistroIsAutomaticallyHandledOrAccepted(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Sem Data',
            'custo' => 12,
            'tipo_receita' => 'doce',
        ]);

        $this->Receitas->saveOrFail($entity);

        $this->assertNotEmpty($entity->id);
    }

    public function testInvalidDataRegistroPreventsSave(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Data Errada',
            'custo' => 10,
            'tipo_receita' => 'doce',
            'data_registro' => 'invalid-date',
        ]);

        $this->assertFalse($this->Receitas->save($entity));
        $this->assertArrayHasKey('data_registro', $entity->getErrors());
    }

    public function testCanUpdateReceita(): void
    {
        $receita = $this->Receitas->find()->first();

        $receita->nome = 'Atualizado';
        $this->Receitas->saveOrFail($receita);

        $updated = $this->Receitas->get($receita->id);

        $this->assertSame('Atualizado', $updated->nome);
    }

    public function testAfterSaveCommitSendsEmail(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Email Test',
            'descricao' => 'Envio',
            'custo' => 15,
            'tipo_receita' => 'doce',
        ]);

        $this->Receitas->save($entity, [
            'actorEmail' => 'teste@email.com',
            'notificationAction' => 'criada',
        ]);

        $this->assertMailCount(1);
        $this->assertMailSentTo('teste@email.com');
        $this->assertMailSubjectContains('Email Test');
    }

    public function testAfterSaveCommitDoesNotSendEmailWithoutParams(): void
    {
        $entity = $this->Receitas->newEntity([
            'nome' => 'Sem Email',
            'custo' => 10,
            'tipo_receita' => 'doce',
        ]);

        $this->Receitas->save($entity);

        $this->assertMailCount(0);
    }
}
