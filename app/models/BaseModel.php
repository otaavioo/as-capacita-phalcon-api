<?php
namespace App\Models;

/**
 * Model base
 *
 * @package App/Services/Models
 * @author Otávio Augusto Borges Pinto <otavio@agenciasys.com.br>
 * @copyright Softers Sistemas de Gestão © 2016
 */
class BaseModel extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setConnectionService('db');
    }

    public function saveDB()
    {
        if (parent::save()) {
            return true;
        }

        return $this->notSave();
    }

    public function notSave()
    {
        throw new \Exception(implode(' AND ', $this->getMessages()).' on model '.get_class($this), 100);
    }
}
