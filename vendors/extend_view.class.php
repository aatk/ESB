<?php

/**
 * Class extend_view
 */

abstract class extend_view implements View
{
    public $object = [];
    
    public function __construct($viewobject)
    {
        $this->object = $viewobject;
    }
    
    abstract public function generate();

    public function __toString()
    {
        return $this->generate();
    }
}