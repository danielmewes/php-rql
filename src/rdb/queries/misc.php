<?php namespace r;

class Uuid extends ValuedQuery
{
    protected function getTermType() {
        return pb\Term_TermType::PB_UUID;
    }
}

class Minval extends ValuedQuery
{
    protected function getTermType() {
        return pb\Term_TermType::PB_MINVAL;
    }
}

class Maxval extends ValuedQuery
{
    protected function getTermType() {
        return pb\Term_TermType::PB_MAXVAL;
    }
}

?>
