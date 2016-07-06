<?php
namespace App\Users\Models;

/**
 * Model da tabela 'Users'
 *
 * @package App\Users\Models
 * @author Otávio Augusto Borges Pinto <otavio@agenciasys.com.br>
 * @copyright Softers Sistemas de Gestão © 2016
 */
class Users extends \App\Services\Models\BaseASClient
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
}
