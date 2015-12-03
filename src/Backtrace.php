<?php

namespace r;

class Backtrace
{
    public static function decodeServerResponse($backtrace)
    {
        $result = new Backtrace();
        $result->frames = array();
        foreach ($backtrace as $frame) {
            $result->frames[] = Frame::decodeServerResponse($frame);
        }
        return $result;
    }

    // Returns true if no more frames are available
    public function consumeFrame()
    {
        if (\count($this->frames) == 0) {
            return false;
        }
        $frame = $this->frames[0];
        $this->frames = array_slice($this->frames, 1);
        return $frame;
    }

    private $frames = null;
}
