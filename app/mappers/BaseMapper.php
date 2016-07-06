<?php
namespace App\Mappers;

/**
 * Cria atributo de classe com o objeto de Injeção de Dependência
 *
 * @package App\Mappers
 * @author Otávio Augusto Borges Pinto <otavio@agenciasys.com.br>
 * @copyright Softers Sistemas de Gestão © 2016
 */
class BaseMapper
{
    protected $di;

    protected $request;

    public function __construct($di)
    {
        $this->di = $di;
        $this->request = new \App\Controllers\RESTController();
    }
}
