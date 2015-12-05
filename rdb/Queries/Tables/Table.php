<?php

namespace r\Queries\Tables;

use r\ValuedQuery\ValuedQuery;
use r\Queries\Selecting\Get;
use r\Queries\Selecting\GetAll;
use r\Queries\Selecting\GetMultiple;
use r\Queries\Geo\GetIntersecting;
use r\Queries\Geo\GetNearest;
use r\Queries\Writing\Sync;
use r\Queries\Writing\Insert;
use r\Queries\Index\IndexCreate;
use r\Queries\Index\IndexDrop;
use r\Queries\Index\IndexList;
use r\Queries\Index\IndexWait;
use r\Queries\Index\IndexStatus;
use r\Queries\Tables\Wait;
use r\Queries\Tables\Reconfigure;
use r\Queries\Tables\Rebalance;
use r\Queries\Tables\Status;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

class Table extends ValuedQuery
{
    public function insert($document, $opts = null)
    {
        return new Insert($this, $document, $opts);
    }
    public function get($key)
    {
        return new Get($this, $key);
    }
    public function getAll($key, $opts = null)
    {
        return new GetAll($this, $key, $opts);
    }
    public function getMultiple($keys, $opts = null)
    {
        return new GetMultiple($this, $keys, $opts);
    }
    public function getIntersecting($geo, $opts = null)
    {
        return new GetIntersecting($this, $geo, $opts);
    }
    public function getNearest($center, $opts = null)
    {
        return new GetNearest($this, $center, $opts);
    }
    public function sync()
    {
        return new Sync($this);
    }
    public function indexCreate($indexName, $keyFunction = null)
    {
        return new IndexCreate($this, $indexName, $keyFunction);
    }
    public function indexCreateMulti($indexName, $keyFunction = null)
    {
        return new IndexCreate($this, $indexName, $keyFunction, array('multi' => true));
    }
    public function indexCreateGeo($indexName, $keyFunction = null)
    {
        return new IndexCreate($this, $indexName, $keyFunction, array('geo' => true));
    }
    public function indexCreateMultiGeo($indexName, $keyFunction = null)
    {
        return new IndexCreate($this, $indexName, $keyFunction, array('multi' => true, 'geo' => true));
    }
    public function indexDrop($indexName)
    {
        return new IndexDrop($this, $indexName);
    }
    public function indexList()
    {
        return new IndexList($this);
    }
    public function indexStatus($indexNames = null)
    {
        return new IndexStatus($this, $indexNames);
    }
    public function indexWait($indexNames = null)
    {
        return new IndexWait($this, $indexNames);
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
    public function config()
    {
        return new Config($this);
    }
    public function status()
    {
        return new Status($this);
    }


    public function __construct($database, $tableName, $useOutdatedOrOpts = null)
    {
        if (isset($database) && !is_a($database, 'r\Queries\Dbs\Db')) {
            throw new RqlDriverError("Database is not a Db object.");
        }
        $tableName = $this->nativeToDatum($tableName);
        if (isset($useOutdated) && !is_bool($useOutdated)) {
            throw new RqlDriverError("Use outdated must be bool.");
        }

        $i = 0;
        if (isset($database)) {
            $this->setPositionalArg($i++, $database);
        }
        $this->setPositionalArg($i++, $tableName);
        if (isset($useOutdatedOrOpts)) {
            if (is_bool($useOutdatedOrOpts)) {
                if ($useOutdatedOrOpts) {
                    $this->setOptionalArg('read_mode', new StringDatum("outdated"));
                }
            } else {
                foreach ($useOutdatedOrOpts as $opt => $val) {
                    $this->setOptionalArg($opt, $this->nativeToDatum($val));
                }
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_TABLE;
    }
}
