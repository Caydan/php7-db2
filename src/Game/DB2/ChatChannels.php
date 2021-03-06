<?php

namespace Game\DB2;

use \Shared\Binary\BinaryReader;
use \Shared\Binary\IBinaryReader;
use \Shared\DB2\DB2File;
use \Shared\FileSystem\File;

class ChatChannels extends DB2File
{
    public function __construct(File $file)
    {
        parent::__construct(new BinaryReader($file->getBuffer()));
    }

    public function getStructure() : array
    {
        return [
            DB2File::FIELD_INT32,
            DB2File::FIELD_STR_LOC,
            DB2File::FIELD_STR_LOC,
            DB2File::FIELD_INT16
        ];
    }

    public function hasIndexFieldInData() : bool
    {
        return true;
    }
}
