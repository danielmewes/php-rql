<?php

namespace r\pb;

class Response_ResponseType
{
    const PB_SUCCESS_ATOM  = 1;
    const PB_SUCCESS_SEQUENCE  = 2;
    const PB_SUCCESS_PARTIAL  = 3;
    const PB_WAIT_COMPLETE  = 4;
    const PB_CLIENT_ERROR  = 16;
    const PB_COMPILE_ERROR  = 17;
    const PB_RUNTIME_ERROR  = 18;
}
