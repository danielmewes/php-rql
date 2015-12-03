<?php

namespace r\Queries\Dbs;

use r\Query;
use r\Queries\Tables\Table;
use r\Queries\Tables\TableCreate;
use r\Queries\Tables\TableDrop;
use r\Queries\Tables\TableList;
use r\Queries\Tables\Reconfigure;
use r\Queries\Tables\Rebalance;
use r\Queries\Tables\Wait;
use r\ProtocolBuffer\TermTermType;

class Db extends Query
{
    public function __construct($dbName)
    {
        $dbName = $this->nativeToDatum($dbName);
        $this->setPositionalArg(0, $dbName);
    }

    protected function getTermType()
    {
        return TermTermType::PB_DB;
    }

    public function table($tableName, $useOutdatedOrOpts = null)
    {
        return new Table($this, $tableName, $useOutdatedOrOpts);
    }

    public function tableCreate($tableName, $options = null)
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

    public function wait($opts = null)
    {
        return new Wait($this, $opts);
    }

    public function reconfigure($opts = null)
    {
        return new Reconfigure($this, $opts);
    }
    
    public function rebalance()
    {
        return new Rebalance($this);
    }
}
