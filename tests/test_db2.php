<?php

include "../loader.php";

$filename = "../data/dbc/enUS/AreaTable.db2";
$file = \Shared\FileSystem\File::open($filename, "rb");


$db2 = new \Game\DB2\AreaTable($file);
$db2->load();

print_r($db2);


/*$buffer = $file->getBuffer();
$reader = new \Shared\Binary\BinaryReader($buffer);

$db2Header = \Shared\DB2\DB2Header::read($reader);


print_r($db2Header);

echo "Current Offset: " . $reader->getOffset() . "\n\n";

for ($i = 0; $i < $db2Header->recordCount; ++$i)
{
    echo ": " . $reader->readInt32() . " \n";
    echo ": " . $reader->readInt32() . " \n";
    echo ": " . $reader->readInt16() . " \n";
    echo ": " . $reader->readInt16() . " \n\n";
}

$reader->skip(2);

echo $reader->getOffset() - 4192 . ": ". $reader->readString() . "\n";
echo $reader->getOffset() - 4192 . ": ". $reader->readString() . "\n";
echo $reader->getOffset() - 4192 . ": ". $reader->readString() . "\n";
echo $reader->getOffset() - 4192 . ": ". $reader->readString() . "\n";
echo $reader->getOffset() - 4192 . ": ". $reader->readString() . "\n";
echo $reader->getOffset() - 4192 . ": ". $reader->readString() . "\n";*/