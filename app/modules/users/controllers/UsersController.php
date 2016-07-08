<?php
namespace App\Users\Controllers;

use App\Controllers\RESTController;
use App\Users\Models\Users;

/**
 * Gerencia as requisições para o módulo admin.
 *
 * @package App\Users\Controllers
 * @author Otávio Augusto Borges Pinto <otavio@agenciasys.com.br>
 * @copyright Softers Sistemas de Gestão © 2016
 */
class UsersController extends RESTController
{
    /**
     * Retorna uma lista de Usuários.
     *
     * @access public
     * @return Array Lista de Usuários.
     */
    public function getUsers()
    {
        try {
            $usersModel = new Users();

            $users = $usersModel->find(
                [
                    'conditions' => 'true ' . $this->getConditions(),
                    'columns' => $this->partialFields,
                    'limit' => $this->limit
                ]
            );

            return $users;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());

        }
    }

    /**
     * Retorna um Usuário.
     *
     * @access public
     * @return Array Usuário.
     */
    public function getUser($iUserId)
    {
        try {
            $usersModel = new Users();

            $users = $usersModel->findFirst(
                [
                    'conditions' => "iUserId = '$iUserId'",
                    'columns' => $this->partialFields,
                ]
            );

            return $users;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());

        }
    }

    /**
     * Adiciona um Usuário.
     *
     * @access public
     * @return Array Usuário.
     */
    public function addUser()
    {
        try {
            $usersModel = new Users();
            $usersModel->sName = $this->di->get('request')->get('sName');
            $usersModel->sEmail = $this->di->get('request')->get('sEmail');

            $usersModel->saveDB();

            return $usersModel;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Editar o campo de um Usuário.
     *
     * @access public
     * @return Array.
     */
    public function editUser()
    {
    }

    /**
     * Remove um Usuário.
     *
     * @access public
     * @return boolean.
     */
    public function deleteUser()
    {
    }
}
