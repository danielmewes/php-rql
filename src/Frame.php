<?php

namespace r;

class Frame
{
    private $isPositionalArg = false;
    private $isOptionalArg = false;
    private $optionalArgName = null;
    private $positionalArgPosition = null;
    
    public static function decodeServerResponse($frame)
    {
        $result = new Frame();
        if (is_string($frame)) {
            $result->isOptionalArg = true;
            $result->optionalArgName = $frame;
        } else {
            $result->isPositionalArg = true;
            $result->positionalArgPosition = $frame;
        }

        return $result;
    }
    public function isPositionalArg()
    {
        return $this->isPositionalArg;
    }
    public function isOptionalArg()
    {
        return $this->isOptionalArg;
    }
    public function getOptionalArgName()
    {
        return $this->optionalArgName;
    }
    public function getPositionalArgPosition()
    {
        return $this->positionalArgPosition;
    }
}
