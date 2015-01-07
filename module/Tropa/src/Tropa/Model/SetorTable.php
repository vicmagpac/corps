<?php

namespace Tropa\Model;

use Fgsl\Db\TableGateway\AbstractTableGateway;
use Fgsl\Model\AbstractModel;

class SetorTable extends AbstractTableGateway
{
    protected $primaryKey = 'codigo';

    protected function getData(AbstractModel $model)
    {
        $data = array(
            'codigo' => $model->codigo,
            'nome'   => $model->nome
        );

        return $data;
    }
}