<?php

namespace Shared\DB2;

use \Shared\DB2\DB2File;

class DB2Searcher
{
    private $file;

    public function __construct(DB2File $file)
    {
        $this->file = $file;
    }

    public function search(string $search) : array
    {
        $result = [];
        $structure = $this->file->getStructure();
        $records = $this->file->getRecords();

        $isIntegerSearch = intval($search) !== 0;

        foreach ($records as $record)
        {
            $idx = 0;
            $matched = false;

            foreach ($structure as $field)
            {
                $content = $record->getField($idx++);

                switch ($field)
                {
                    case DB2File::FIELD_BYTE:
                    case DB2File::FIELD_INT16:
                    case DB2File::FIELD_INT24:
                    case DB2File::FIELD_INT32:
                    case DB2File::FIELD_INT64:
                    case DB2File::FIELD_FLOAT:
                        if ($isIntegerSearch && $content == $search)
                            $matched = true;
                        break;
                    case DB2File::FIELD_STR:
                    case DB2File::FIELD_STR_LOC:
                        if (strpos(strtoupper($content), strtoupper($search)) !== false)
                            $matched = true;
                        break;
                }

                if ($matched)
                {
                    $result[] = $record;
                    break;
                }
            }
        }

        return $result;
    }
}
