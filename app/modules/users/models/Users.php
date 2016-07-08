<?php
namespace App\Users\Models;

/**
 * Model da tabela 'Users'
 *
 * @package App\Users\Models
 * @author Otávio Augusto Borges Pinto <otavio@agenciasys.com.br>
 * @copyright Softers Sistemas de Gestão © 2016
 */
class Users extends \App\Models\BaseModel
{
    /**
     * @Primary
     * @Identity
     * @Column(type="integer", length=10, nullable=false)
     */
    public $iUserId;

    /**
     * @Column(type="string", length=70, nullable=false)
     */
    public $sName;

    /**
     * @Column(type="string", length=70, nullable=false)
     */
    public $sEmail;

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $dtCreated;

    /**
     * @Column(type="datetime", nullable=true)
     */
    public $dtUpdated;

    public function beforeCreate()
    {
        $this->dtCreated = (new \DateTime())->format('Y-m-d H:i:s');
        $this->dtUpdated = '0000-00-00 00:00:00';
    }

    public function beforeUpdate()
    {
        $this->dtUpdated = (new \DateTime())->format('Y-m-d H:i:s');
    }
}
