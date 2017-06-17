<?php

namespace Shared\DB2;

use \Shared\Binary\IBinaryReader;
use \Shared\Binary\BinaryReader;

class DB2Structure
{
    public $structure = [];

    private $offset = 0;

    public function getNextField()
    {
        return $this->structure[$this->offset++];
    }

    public function reset()
    {
        $this->offset = 0;
    }

    private function __construct()
    {
    }

    public static function read(IBinaryReader $reader, int $fieldCount) : DB2Structure
    {
        $currentEndian = $reader->getEndian();

        $reader->setEndian(BinaryReader::BIG_ENDIAN);

        $item = new DB2Structure();

        for ($i = 0; $i < $fieldCount; ++$i)
        {
            $size = $reader->readInt16();
            $position = $reader->readInt16();
            $byteSize = (32 - $size) / 8;

            $item->structure[] = [ "size" => $size, "byteSize" => $byteSize, "position" => $position ];
        }

        $reader->setEndian($currentEndian);

        return $item;
    }
}
