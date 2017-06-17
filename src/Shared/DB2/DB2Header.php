<?php

namespace Shared\DB2;

use \Shared\Binary\IBinaryReader;

class DB2Header
{
    const FLAG_OFFSET_MAP       = 0x01;
    const FLAG_SECONDARY_KEY    = 0x02;
    const FLAG_NON_INLINE_IDS   = 0x04;

    public $magic;
    public $recordCount;
    public $fieldCount;
    public $recordSize;
    public $stringTableSize;
    public $tableHash;
    public $layoutHash;
    public $minId;
    public $maxId;
    public $locale;
    public $copyTableSize;
    public $flags;
    public $idIndex;
    public $structure;
    public $totalFieldCount;
    public $commonDataTableSize;

    public function hasOffsetMap() : bool
    {
        return (($this->flags & DB2Header::FLAG_OFFSET_MAP) != 0);
    }

    public function hasSecondaryKey() : bool
    {
        return (($this->flags & DB2Header::FLAG_SECONDARY_KEY) != 0);
    }

    public function hasNonInlineIds() : bool
    {
        return (($this->flags & DB2Header::FLAG_NON_INLINE_IDS) != 0);
    }

    public static function read(IBinaryReader $reader) : DB2Header
    {
        $header = new DB2Header();
        $header->magic = $reader->readString(false, 4);
        $header->recordCount = $reader->readInt32();
        $header->fieldCount = $reader->readInt32();
        $header->recordSize = $reader->readInt32();
        $header->stringTableSize = $reader->readInt32();
        $header->tableHash = $reader->readInt32();
        $header->layoutHash = $reader->readInt32();
        $header->minId = $reader->readInt32();
        $header->maxId = $reader->readInt32();
        $header->locale = $reader->readInt32();
        $header->copyTableSize = $reader->readInt32();
        $header->flags = $reader->readInt16();
        $header->idIndex = $reader->readInt16();
        $header->totalFieldCount = $reader->readInt32();
        $header->commonDataTableSize = $reader->readInt32();
        $header->structure = DB2Structure::read($reader, $header->fieldCount);
        return $header;
    }
}
