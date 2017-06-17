<?php

namespace Shared\DB2;

use \Shared\Binary\IBinaryReader;
use \Shared\Binary\BinaryReader;
use \Shared\DB2\DB2Record;

abstract class DB2File
{
    const FIELD_BYTE    = 1;
    const FIELD_INT16   = 2;
    const FIELD_INT24   = 3;
    const FIELD_INT32   = 4;
    const FIELD_INT64   = 5;
    const FIELD_FLOAT   = 6;
    const FIELD_STR     = 7;
    const FIELD_STR_LOC = 8;

    protected $reader;

    protected $header;
    protected $records;
    protected $stringTable;

    private $isLoaded;

    public function __construct(IBinaryReader $reader)
    {
        $this->reader = $reader;
        $this->isLoaded = false;
    }

    public function getRecordSize()
    {
        $struct = $this->getStructure();
        $size = 0;

        foreach ($struct as $field)
        {
            switch ($field)
            {
                case DB2File::FIELD_BYTE:
                    $size += 1;
                    break;
                case DB2File::FIELD_INT16:
                    $size += 2;
                    break;
                case DB2File::FIELD_INT24:
                    $size += 3;
                    break;
                case DB2File::FIELD_INT32:
                case DB2File::FIELD_FLOAT:
                    $size += 4;
                    break;
                case DB2File::FIELD_INT64:
                    $size += 8;
                    break;
                case DB2File::FIELD_STR_LOC:
                case DB2File::FIELD_STR:
                    $size += 4;
                    break;
                default:
                    throw new \Exception("Field type $field is not supported!");
            }
        }

        return $size;
    }

    public function load()
    {
        if ($this->isLoaded)
            throw new \Exception("File already loaded!");

        $this->header = DB2Header::read($this->reader);
        $this->records = [];
        $this->stringTable = [];

        $structure = $this->getStructure();

        for ($row = 0; $row < $this->header->recordCount; ++$row)
        {
            $record = [];
            $realRecordSize = 0;

            foreach ($structure as $field)
            {
                switch ($field)
                {
                    case DB2File::FIELD_BYTE:
                        $record[] = $this->reader->readByte();
                        $realRecordSize += 1;
                        break;
                    case DB2File::FIELD_INT16:
                        $record[] = $this->reader->readInt16();
                        $realRecordSize += 2;
                        break;
                    case DB2File::FIELD_INT24:
                        $record[] = $this->reader->readInt24();
                        $realRecordSize += 3;
                        break;
                    case DB2File::FIELD_INT32:
                        $record[] = $this->reader->readInt32();
                        $realRecordSize += 4;
                        break;
                    case DB2File::FIELD_FLOAT:
                        $record[] = $this->reader->readFloat();
                        $realRecordSize += 4;
                        break;
                    case DB2File::FIELD_INT64:
                        $record[] = $this->reader->readInt64();
                        $realRecordSize += 8;
                        break;
                    case DB2File::FIELD_STR:
                    case DB2File::FIELD_STR_LOC:
                        $refId = $this->reader->readInt32();
                        $this->stringTable[$refId] = "";
                        $record[] = &$this->stringTable[$refId];
                        $realRecordSize += 4;
                        break;
                }
            }

            $bytesToSkip = $this->header->recordSize - $realRecordSize;
            if ($bytesToSkip > 0)
                $this->reader->skip($bytesToSkip);

            $this->records[] = new DB2Record($record);
        }

        $offset = $this->reader->getOffset();
        $tableOffset = 0;
        
        for ($i = 0; $tableOffset < $this->header->stringTableSize;)
        {
            $this->stringTable[$tableOffset] = $this->reader->readString();
            $tableOffset = $this->reader->getOffset() - $offset;
        }

        if ($this->hasIndexFieldInData())
        {
            foreach ($this->records as $record)
            {
                $record->setId($this->reader->readInt32());
            }
        }

        $this->isLoaded = true;
    }

    public function getRecords()
    {
        return $this->records;
    }

    public abstract function getStructure() : array;
    public abstract function hasIndexFieldInData() : bool;
}
