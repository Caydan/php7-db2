<?php

include "../src/Shared/FileSystem/File.php";
include "../src/Shared/Binary/IBuffer.php";
include "../src/Shared/Binary/FileBuffer.php";

$filename = "./data/test_filebuffer.bin";
$file = \Shared\FileSystem\File::open($filename, "rb");
$buffer = $file->getBuffer();

while ($buffer->hasNext())
{
    echo "Byte: " . $buffer->nextByte() . "\n";
}
