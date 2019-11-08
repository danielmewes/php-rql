<?php

namespace r\Queries\Dbs;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Tables\Rebalance;
use r\Queries\Tables\Reconfigure;
use r\Queries\Tables\Table;
use r\Queries\Tables\TableCreate;
use r\Queries\Tables\TableDrop;
use r\Queries\Tables\TableList;
use r\Queries\Tables\Wait;
use r\Query;

class Db extends Query
{
    public function __construct($dbName)
    {
        $dbName = $this->nativeToDatum($dbName);
        $this->setPositionalArg(0, $dbName);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_DB;
    }

    public function table($tableName, $useOutdatedOrOpts = null)
    {
        return new Table($this, $tableName, $useOutdatedOrOpts);
    }

    public function tableCreate($tableName, $options = [])
    {
        return new TableCreate($this, $tableName, $options);
    }

    public function tableDrop($tableName)
    {
        return new TableDrop($this, $tableName);
    }

    public function tableList()
    {
        return new TableList($this);
    }

    public function wait(array $opts = [])
    {
        return new Wait($this, $opts);
    }

    public function reconfigure(array $opts = [])
    {
        return new Reconfigure($this, $opts);
    }

    public function rebalance()
    {
        return new Rebalance($this);
    }
}
