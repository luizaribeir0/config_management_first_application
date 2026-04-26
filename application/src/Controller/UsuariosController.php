<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class UsuariosController extends AppController
{
    /**
     * @param \Cake\Event\EventInterface<\Cake\Controller\Controller> $event Event.
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['login', 'logout']);
    }

    /**
     * @return \Cake\Http\Response|null|void
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        if ($this->Authentication->getIdentity()) {
            $target = $this->Authentication->getLoginRedirect([
                'controller' => 'Receitas',
                'action' => 'index',
            ]);

            return $this->redirect($target ?? ['controller' => 'Receitas', 'action' => 'index']);
        }
        if ($this->request->is('post')) {
            $this->Flash->error(__('Login ou senha incorretos, ou usuário inativo.'));
        }
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function logout()
    {
        $this->request->allowMethod(['get', 'post']);
        $this->Authentication->logout();
        $this->Flash->success(__('Sessão encerrada.'));

        return $this->redirect(['action' => 'login']);
    }

    /**
     * @return \Cake\Http\Response|null|void
     */
    public function perfil()
    {
        $identity = $this->Authentication->getIdentity();
        if ($identity === null) {
            return $this->redirect(['action' => 'login']);
        }

        $usuarios = $this->fetchTable('Usuarios');
        $usuario = $usuarios->get((int)$identity->getIdentifier());

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (empty($data['senha'])) {
                unset($data['senha']);
            }

            $usuario = $usuarios->patchEntity($usuario, $data);
            if ($usuarios->save($usuario)) {
                $this->Flash->success(__('Perfil atualizado com sucesso.'));

                return $this->redirect(['controller' => 'Receitas', 'action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível atualizar seu perfil. Tente novamente.'));
        }

        $this->set(compact('usuario'));
    }
}
