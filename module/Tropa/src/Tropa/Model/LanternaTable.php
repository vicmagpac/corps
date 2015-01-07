<?php

namespace Tropa\Model;

use Fgsl\Db\TableGateway\AbstractTableGateway;
use Fgsl\Model\AbstractModel;
use Zend\Db\Sql\Select;

class LanternaTable extends AbstractTableGateway
{
    protected $primaryKey = 'codigo';

    public function get($codigo)
    {
        $codigo = (int) $codigo;

        $select = $this->getSelect()
                       ->where(array('lanterna.codigo' => $codigo));

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        return $row;
    }

    public function getData(AbstractModel $model)
    {
        $data = array(
            'nome' => $model->nome,
            'codigo_setor' => $model->setor->codigo
        );

        return $data;
    }


    public function getSelect()
    {
        $select = new Select();
        $select->from('lanterna')
               ->columns(array('codigo','nome','codigo_setor'))
               ->join(array('s'=>'setor'), 'lanterna.codigo_setor = s.codigo', array('setor'=>'nome'));

        return $select;
    }
}