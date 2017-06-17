<?php

function __autoload(string $namespace)
{
    $relativePath = str_replace("\\", "/", $namespace);
    require_once __DIR__ . '/src/' . $relativePath . ".php";
}
