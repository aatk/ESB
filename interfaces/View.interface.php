<?php

interface View
{
    
    public function __construct($viewobject);
    
    public function generate();
    
}