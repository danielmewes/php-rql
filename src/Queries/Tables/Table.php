<?php

namespace r\Queries\Tables;

use r\Datum\StringDatum;
use r\ProtocolBuffer\TermTermType;
use r\Queries\Dbs\Db;
use r\Queries\Geo\GetIntersecting;
use r\Queries\Geo\GetNearest;
use r\Queries\Index\IndexCreate;
use r\Queries\Index\IndexDrop;
use r\Queries\Index\IndexList;
use r\Queries\Index\IndexStatus;
use r\Queries\Index\IndexWait;
use r\Queries\Selecting\Get;
use r\Queries\Selecting\GetAll;
use r\Queries\Selecting\GetMultiple;
use r\Queries\Writing\Insert;
use r\Queries\Writing\Sync;
use r\ValuedQuery\ValuedQuery;

class Table extends ValuedQuery
{
    public function insert($document, array $opts = [])
    {
        return new Insert($this, $document, $opts);
    }

    public function get($key)
    {
        return new Get($this, $key);
    }

    public function getAll($key, array $opts = [])
    {
        return new GetAll($this, $key, $opts);
    }

    public function getMultiple($keys, array $opts = [])
    {
        return new GetMultiple($this, $keys, $opts);
    }

    public function getIntersecting($geo, array $opts = [])
    {
        return new GetIntersecting($this, $geo, $opts);
    }

    public function getNearest($center, array $opts = [])
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
        return new IndexCreate($this, $indexName, $keyFunction, ['multi' => true]);
    }

    public function indexCreateGeo($indexName, $keyFunction = null)
    {
        return new IndexCreate($this, $indexName, $keyFunction, ['geo' => true]);
    }

    public function indexCreateMultiGeo($indexName, $keyFunction = null)
    {
        return new IndexCreate($this, $indexName, $keyFunction, ['multi' => true, 'geo' => true]);
    }

    public function indexDrop($indexName)
    {
        return new IndexDrop($this, $indexName);
    }

    public function indexList()
    {
        return new IndexList($this);
    }

    public function indexStatus(...$indexNames)
    {
        return new IndexStatus($this, ...$indexNames);
    }

    public function indexWait(...$indexNames)
    {
        return new IndexWait($this, ...$indexNames);
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

    public function config()
    {
        return new Config($this);
    }

    public function status()
    {
        return new Status($this);
    }

    public function __construct(?Db $database, $tableName, $useOutdatedOrOpts = null)
    {
        $i = 0;
        if (isset($database)) {
            $this->setPositionalArg($i++, $database);
        }

        $this->setPositionalArg($i++, $this->nativeToDatum($tableName));

        if (isset($useOutdatedOrOpts)) {
            if (is_bool($useOutdatedOrOpts)) {
                if ($useOutdatedOrOpts) {
                    $this->setOptionalArg('read_mode', new StringDatum('outdated'));
                }
            } else {
                foreach ($useOutdatedOrOpts as $opt => $val) {
                    $this->setOptionalArg($opt, $this->nativeToDatum($val));
                }
            }
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_TABLE;
    }
}
