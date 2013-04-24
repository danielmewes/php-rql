<?php namespace r;

class Insert extends ValuedQuery
{
    public function __construct(Table $table, $document, $upsert = null) {
        if (isset($upsert) && !\is_bool($upsert)) throw new RqlDriverError("Upsert must be bool.");
        if (!(is_object($document) && is_subclass_of($document, "\\r\\Query")))
            $document = nativeToDatum($document);
        $this->table = $table;
        $this->document = $document;
        $this->upsert = $upsert;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_INSERT);
        $term->set_args(0, $this->table->getPBTerm());
        $term->set_args(1, $this->document->getPBTerm());
        if (isset($this->upsert)) {
            $pair = new pb\Term_AssocPair();
            $pair->set_key("upsert");
            $subDatum = new BoolDatum($this->upsert);
            $pair->set_val($subDatum->getPBTerm());
            $term->set_optargs(0, $pair);
        }
        return $term;
    }
    
    private $table;
    private $document;
    private $upsert;
}

class Update extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $delta, $nonAtomic = null) {
        if (isset($nonAtomic ) && !\is_bool($nonAtomic )) throw new RqlDriverError("nonAtomic must be bool.");
        if (!(is_object($delta) && is_subclass_of($delta, "\\r\\Query"))) {
            try {
                $delta = nativeToDatum($delta);
                if (!is_subclass_of($delta, "\\r\\Datum")) {
                    // $delta is not a simple datum. Wrap it into a function:                
                    $delta = new RFunction(array(new RVar('_')), $delta);
                }
            } catch (RqlDriverError $e) {
                $delta = nativeToFunction($delta);
            }
        } else if (!(is_object($delta) && is_subclass_of($delta, "\\r\\FunctionQuery")) && !(is_object($delta) && is_subclass_of($delta, "\\r\\Datum"))) {
            $delta = new RFunction(array(new RVar('_')), $delta);
        }
        $this->selection = $selection;
        $this->delta = $delta;
        $this->nonAtomic  = $nonAtomic;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_UPDATE);
        $term->set_args(0, $this->selection->getPBTerm());
        $term->set_args(1, $this->delta->getPBTerm());
        if (isset($this->nonAtomic)) {
            $pair = new pb\Term_AssocPair();
            $pair->set_key("non_atomic");
            $subDatum = new BoolDatum($this->nonAtomic);
            $pair->set_val($subDatum->getPBTerm());
            $term->set_optargs(0, $pair);
        }
        return $term;
    }
    
    private $selection;
    private $delta;
    private $nonAtomic;
}

class Delete extends ValuedQuery
{
    public function __construct(ValuedQuery $selection) {
        $this->selection = $selection;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_DELETE);
        $term->set_args(0, $this->selection->getPBTerm());
        return $term;
    }
    
    private $selection;
}

class Replace extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $delta, $nonAtomic = null) {
        if (isset($nonAtomic ) && !\is_bool($nonAtomic )) throw new RqlDriverError("nonAtomic must be bool.");
        if (!(is_object($delta) && is_subclass_of($delta, "\\r\\Query"))) {
            // If we can make it an object, we will wrap that object into a function.
            // Otherwise, we will try to make it a function.
            try {
                $delta = nativeToDatum($delta);
                $delta = new RFunction(array(new RVar('_')), $delta);
            } catch (RqlDriverError $e) {
                $delta = nativeToFunction($delta);
            }
        }
        $this->selection = $selection;
        $this->delta = $delta;
        $this->nonAtomic  = $nonAtomic;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_REPLACE);
        $term->set_args(0, $this->selection->getPBTerm());
        $term->set_args(1, $this->delta->getPBTerm());
        if (isset($this->nonAtomic)) {
            $pair = new pb\Term_AssocPair();
            $pair->set_key("non_atomic");
            $subDatum = new BoolDatum($this->nonAtomic);
            $pair->set_val($subDatum->getPBTerm());
            $term->set_optargs(0, $pair);
        }
        return $term;
    }
    
    private $selection;
    private $delta;
    private $nonAtomic;
}

?>
