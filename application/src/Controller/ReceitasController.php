<?php
declare(strict_types=1);

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Receitas Controller
 *
 * @property \App\Model\Table\ReceitasTable $Receitas
 */
class ReceitasController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'sortableFields' => ['id', 'nome', 'data_registro', 'custo', 'tipo_receita'],
        ];

        [$query, $filtros] = $this->buildFilteredQuery();

        $receitas = $this->paginate($query);

        $this->set(compact('receitas', 'filtros'));
    }

    /**
     * Export filtered listing to PDF.
     *
     * @return \Cake\Http\Response
     */
    public function exportPdf()
    {
        [$query] = $this->buildFilteredQuery();
        $receitas = $query
            ->orderBy(['Receitas.id' => 'ASC'])
            ->all()
            ->toList();

        $html = $this->buildPdfHtml($receitas);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->withType('pdf')
            ->withDownload('receitas.pdf')
            ->withStringBody($dompdf->output());
    }

    /**
     * @return array{0: \Cake\ORM\Query\SelectQuery, 1: array<string, mixed>}
     */
    private function buildFilteredQuery(): array
    {
        $query = $this->Receitas->find();

        $nome = trim((string)$this->request->getQuery('nome', ''));
        if ($nome !== '') {
            $query->where(['Receitas.nome LIKE' => '%' . $nome . '%']);
        }

        $tipoReceita = trim((string)$this->request->getQuery('tipo_receita', ''));
        if (in_array($tipoReceita, ['doce', 'salgada'], true)) {
            $query->where(['Receitas.tipo_receita' => $tipoReceita]);
        }

        $custoMin = $this->request->getQuery('custo_min');
        if ($custoMin !== null && $custoMin !== '' && is_numeric((string)$custoMin)) {
            $query->where(['Receitas.custo >=' => (float)$custoMin]);
        }

        $custoMax = $this->request->getQuery('custo_max');
        if ($custoMax !== null && $custoMax !== '' && is_numeric((string)$custoMax)) {
            $query->where(['Receitas.custo <=' => (float)$custoMax]);
        }

        $dataRegistroInicio = trim((string)$this->request->getQuery('data_registro_inicio', ''));
        if ($dataRegistroInicio !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataRegistroInicio)) {
            $query->where(['Receitas.data_registro >=' => $dataRegistroInicio . ' 00:00:00']);
        }

        $dataRegistroFim = trim((string)$this->request->getQuery('data_registro_fim', ''));
        if ($dataRegistroFim !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataRegistroFim)) {
            $query->where(['Receitas.data_registro <=' => $dataRegistroFim . ' 23:59:59']);
        }

        $filtros = [
            'nome' => $nome,
            'tipo_receita' => $tipoReceita,
            'custo_min' => $custoMin,
            'custo_max' => $custoMax,
            'data_registro_inicio' => $dataRegistroInicio,
            'data_registro_fim' => $dataRegistroFim,
        ];

        return [$query, $filtros];
    }

    /**
     * @param array<\App\Model\Entity\Receita> $receitas
     * @return string
     */
    private function buildPdfHtml(array $receitas): string
    {
        $rows = '';
        foreach ($receitas as $receita) {
            $dataRegistro = $receita->data_registro ? $receita->data_registro->format('d/m/Y H:i:s') : '';
            $custo = number_format((float)$receita->custo, 2, ',', '.');
            $tipoReceita = ucfirst((string)$receita->tipo_receita);

            $rows .= sprintf(
                '<tr><td>%d</td><td>%s</td><td>%s</td><td>R$ %s</td><td>%s</td></tr>',
                (int)$receita->id,
                htmlspecialchars((string)$receita->nome, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($dataRegistro, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($custo, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($tipoReceita, ENT_QUOTES, 'UTF-8'),
            );
        }

        if ($rows === '') {
            $rows = '<tr><td colspan="5">Nenhum registro encontrado para os filtros aplicados.</td></tr>';
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Receitas filtradas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 16px; margin: 0 0 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Receitas filtradas</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Data registro</th>
                <th>Custo</th>
                <th>Tipo receita</th>
            </tr>
        </thead>
        <tbody>
            {$rows}
        </tbody>
    </table>
</body>
</html>
HTML;
    }

    /**
     * View method
     *
     * @param string|null $id Receita id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $receita = $this->Receitas->get($id, contain: []);
        $this->set(compact('receita'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $receita = $this->Receitas->newEmptyEntity();
        if ($this->request->is('post')) {
            $receita = $this->Receitas->patchEntity($receita, $this->request->getData());
            if ($this->Receitas->save($receita)) {
                $this->Flash->success(__('The receita has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The receita could not be saved. Please, try again.'));
        }
        $this->set(compact('receita'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Receita id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $receita = $this->Receitas->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $receita = $this->Receitas->patchEntity($receita, $this->request->getData());
            if ($this->Receitas->save($receita)) {
                $this->Flash->success(__('The receita has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The receita could not be saved. Please, try again.'));
        }
        $this->set(compact('receita'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Receita id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $receita = $this->Receitas->get($id);
        if ($this->Receitas->delete($receita)) {
            $this->Flash->success(__('The receita has been deleted.'));
        } else {
            $this->Flash->error(__('The receita could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
