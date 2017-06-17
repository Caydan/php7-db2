<?php

namespace Shared\Binary;

use \Shared\Binary\IBuffer;
use \Shared\FileSystem\File;

class FileBuffer implements IBuffer
{
    protected $resource;

    public function __construct(File $file)
    {
        if (!$file->isOpened())
            throw new \Exception("File is not opened!");

        $this->resource = $file->getResource();
    }

    public function nextByte() : int
    {
        if (!$this->hasNext())
            throw new \Exception("EOF");

        return ord(fread($this->resource, 1));
    }

    public function hasNext() : bool
    {
        return !feof($this->resource);
    }

    public function getOffset() : int
    {
        return ftell($this->resource);
    }

    public function getSize() : int
    {
        $info = $this->getInfo();
        return $info["size"];
    }

    public function getInfo() : array
    {
        return fstat($this->resource);
    }

    public function rewind()
    {
        rewind($this->resource);
    }
}
