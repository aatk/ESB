<?php


class Test
{
    public function __call($name, $arguments) {
        // Замечание: значение $name регистрозависимо.
        echo "Вызов метода '$name' "
            . implode(', ', $arguments). "\n";
        
        return $this;
    }
}