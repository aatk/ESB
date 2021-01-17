<?php

class Myapp extends extend_controller
{
    public function Init($params)
    {
        $html = "<h1>Home</h1>
        <form>
            <input>DEMO INPUT</input><br/>
            <button>TEST CEST</button>
        </form>";
        
        return $html;
    }
}