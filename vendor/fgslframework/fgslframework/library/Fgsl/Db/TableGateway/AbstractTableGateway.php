<?php

namespace Fgsl\Db\TableGateway;

use Fgsl\Model\AbstractModel;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

abstract class AbstractTableGateway
{
    protected $primaryKey;
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function __call($method, array $args)
    {
        return call_user_func(array($this->tableGateway, $method), $args);
    }

    abstract protected function getData(AbstractModel $model);

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function get($key)
    {
        $key = (int) $key;
        $rowset = $this->tableGateway->select(array($this->primaryKey => $key));
        $row = $rowset->current();
        return $row;
    }

    public function save(AbstractModel $model)
    {
        $primaryKey = $this->primaryKey;
        $key = $model->codigo;
        $data = $this->getData($model);

        if (!$this->get($key)) {
            $this->tableGateway->insert($data);
        } else {
            $this->tableGateway->update($data, array($this->primaryKey => $key));
        }
    }

    public function delete($key)
    {
        $this->tableGateway->delete(array($this->primaryKey => $key));
    }

    public function getSql()
    {
        return $this->tableGateway->getSql();
    }

    public function getSelect()
    {
        return new Select($this->tableGateway->getTable());
    }
}