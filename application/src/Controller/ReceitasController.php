<?php
declare(strict_types=1);

namespace App\Controller;

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
        $query = $this->Receitas->find();
        $receitas = $this->paginate($query);

        $this->set(compact('receitas'));
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
