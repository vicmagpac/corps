<?php

namespace Tropa\Model;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class LanternaTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $select = new Select();
        $select->from('lanterna')
               ->columns(array('codigo', 'nome'))
               ->join(array('s' => 'setor'), 'lanterna.codigo_setor = s.codigo', array('setor' => 'nome'));
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getLanterna($codigo)
    {
        $codigo = (int) $codigo;

        $select = new Select();
        $select->from('lanterna')
               ->columns(array('codigo', 'nome', 'codigo_setor'))
               ->join(array('s' => 'setor'), 'lanterna.codigo_setor = s.codigo', array('setor' => 'nome'))
               ->where(array('lanterna.codigo' => $codigo));
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        return $row;
    }

    public function saveLanterna(Lanterna $lanterna)
    {

        $data = array(
            'nome' => $lanterna->nome,
            'codigo_setor' => $lanterna->setor->codigo
        );

        $codigo = $lanterna->codigo;
        if (!$this->getLanterna($codigo)) {
            $this->tableGateway->insert($data);
        } else {
            $this->tableGateway->update($data, array('codigo' => $codigo));
        }

    }

    public function deleteLanterna($codigo)
    {
        $this->tableGateway->delete(array('codigo' => $codigo));
    }
}