<?php

namespace r;

use Iterator;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\ResponseResponseType;
use r\DatumConverter;

class Cursor implements Iterator
{

    private $token;
    private $connection;
    private $notes;
    private $toNativeOptions;
    private $currentData;
    private $currentSize;
    private $currentIndex;
    private $isComplete;
    private $wasIterated;

    public function __construct(
        Connection $connection,
        $initialResponse,
        $token,
        $notes,
        $toNativeOptions
    ) {
        $this->connection = $connection;
        $this->token = $token;
        $this->notes = $notes;
        $this->toNativeOptions = $toNativeOptions;
        $this->wasIterated = false;

        $this->setBatch($initialResponse);
    }

    // PHP iterator interface
    public function rewind()
    {
        if ($this->wasIterated) {
            throw new RqlDriverError("Rewind() not supported. You can only iterate over a cursor once.");
        }
    }
    public function next()
    {
        $this->requestMoreIfNecessary();
        if (!$this->valid()) {
            throw new RqlDriverError("No more data available.");
        }
        $this->wasIterated = true;
        $this->currentIndex++;
    }
    public function valid()
    {
        $this->requestMoreIfNecessary();
        return !$this->isComplete || ($this->currentIndex < $this->currentSize);
    }
    public function key()
    {
        return null;
    }
    public function current()
    {
        $this->requestMoreIfNecessary();
        if (!$this->valid()) {
            throw new RqlDriverError("No more data available.");
        }
        return $this->currentData[$this->currentIndex]->toNative($this->toNativeOptions);
    }

    public function toArray()
    {
        $result = array();
        foreach ($this as $val) {
            $result[] = $val;
        }
        return $result;
    }

    public function close()
    {
        if (!$this->isComplete) {
            // Cancel the request
            $this->connection->stopQuery($this->token);
            $this->isComplete = true;
        }
        $this->currentIndex = 0;
        $this->currentSize = 0;
        $this->currentData = array();
    }

    public function bufferedCount()
    {
        $this->currentSize - $this->currentIndex;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function __toString()
    {
        return "Cursor";
    }



    public function __destruct()
    {
        if ($this->connection->isOpen()) {
            // Cancel the request
            $this->close();
        }
    }

    private function requestMoreIfNecessary()
    {
        while ($this->currentIndex == $this->currentSize) {
            // We are at the end of currentData. Request more if available
            if ($this->isComplete) {
                return;
            }
            $this->requestNewBatch();
        }
    }

    private function requestNewBatch()
    {
        try {
            $response = $this->connection->continueQuery($this->token);
            $this->setBatch($response);
        } catch (\Exception $e) {
            $this->isComplete = true;
            $this->close();
            throw $e;
        }
    }

    private function setBatch($response)
    {
        $dc = new DatumConverter;
        $this->isComplete = $response['t'] == ResponseResponseType::PB_SUCCESS_SEQUENCE;
        $this->currentIndex = 0;
        $this->currentSize = \count($response['r']);
        $this->currentData = array();
        foreach ($response['r'] as $row) {
            $this->currentData[] = $datum = $dc->decodedJSONToDatum($row);
        }
    }
}
