<?php

echo "<pre>";

echo "DIR DO TESTE:\n";
echo __DIR__;

echo "\n\nCONTEÚDO DE APP:\n";
print_r(scandir(__DIR__ . "/.."));

echo "\n\nCONTEÚDO DE TAREFAS:\n";
print_r(scandir("C:/xampp/htdocs/tarefas"));

echo "</pre>";