<?php namespace r;

class Uuid extends ValuedQuery
{
    protected function getTermType() {
        return pb\Term_TermType::PB_UUID;
    }
}

?>
