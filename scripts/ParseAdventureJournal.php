<?php

require_once "../loader.php";

$db2File = "data/AdventureJournal.db2";
$file = \Shared\FileSystem\File::open($db2File, "rb");

$db2 = new \Game\DB2\AdventureJournal($file);
$db2->load();

$records = $db2->getRecords();

foreach ($records as $record)
{
    echo $record->getId() . ",";
    for ($i = 0; $i < 22; ++$i)
    {
        echo $record->getField($i) . ",";
    }
    echo "\n";
}
