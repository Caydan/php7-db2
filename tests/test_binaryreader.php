<?php

include "../src/Shared/FileSystem/File.php";
include "../src/Shared/Binary/IBuffer.php";
include "../src/Shared/Binary/FileBuffer.php";
include "../src/Shared/Binary/IBinaryReader.php";
include "../src/Shared/Binary/BinaryReader.php";

$filename = "./data/test_binaryreader.bin";
$file = \Shared\FileSystem\File::open($filename, "rb");
$buffer = $file->getBuffer();

$reader = new \Shared\Binary\BinaryReader($buffer);

echo "==== READ AS STRING ==== \n";
echo $reader->readString() . "\n \n";

$reader->reset();

echo "==== READ AS INT32 ==== \n";
echo $reader->readInt32() . " \n \n";

$reader->reset();

echo "==== READ AS FLOAT ==== \n";
echo $reader->readFloat() . " \n \n";
