<?php

namespace Tropa\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;

class SetorTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getSetor($codigo)
    {
        $codigo = (int) $codigo;
        $rowset = $this->tableGateway->select(array('codigo' => $codigo));
        $row = $rowset->current();
        return $row;
    }

    public function saveSetor(Setor $setor)
    {
        $data = array(
            'nome' => $setor->nome
        );

        $codigo  = $setor->codigo;

        if (!$this->getSetor($codigo)) {
            $data['codigo'] = $codigo;
            $this->tableGateway->insert($data);
        } else {
            $this->tableGateway->update($data, array('codigo' => $codigo));
        }
    }

    public function deleteSetor($codigo)
    {
        $this->tableGateway->delete(array('codigo' => $codigo));
    }

    public function getSql()
    {
        return $this->tableGateway->getSql();
    }

    public function getSelect()
    {
        $select = new Select($this->tableGateway->getTable());
        return $select;
    }
}