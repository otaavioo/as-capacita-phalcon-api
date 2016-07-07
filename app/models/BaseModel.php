<?php
namespace App\Models;

use Softers\Error\Message;
use Phalcon\Db\RawValue;
use App\Redis\Redis;

/**
 * Model base
 *
 * @package App/Services/Models
 * @author Diogo Alexsander Cavilha <diogo@softers.com.br>
 * @copyright Softers Sistemas de Gestão © 2014, Diogo Alexsander Cavilha
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
        throw new \Exception(implode(' AND ', $this->getMessages()).' on model '.get_class($this), Message::DB_ERROR);
    }
}
