<?php

namespace Shared\FileSystem;

use \Shared\Binary\IBuffer;
use \Shared\Binary\FileBuffer;

class File
{
    protected $filename;
    protected $resource;

    protected function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    protected function _create()
    {
        touch($this->filename);
    }

    protected function _delete()
    {
        unlink($this->filename);
    }

    protected function _open(string $mode)
    {
        $this->resource = fopen($this->filename, $mode);
    }

    public function isOpened() : bool
    {
        return is_resource($this->resource);
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getBuffer() : IBuffer
    {
        return new FileBuffer($this);
    }

    public static function exists(string $filename) : bool
    {
        return file_exists($filename);
    }

    public static function delete(string $filename) : bool
    {
        $file = new File($filename);
        $file->_delete();

        return !File::exists($filename);
    }

    public static function create(string $filename) : File
    {
        if (File::exists($filename))
            throw new \Exception("File already exists!");

        $file = new File($filename);
        $file->_create();

        return $file;
    }

    public static function open(string $filename, string $mode = "r") : File
    {
        if (!File::exists($filename))
            throw new \Exception("File doesn't exist!");

        $file = new File($filename);
        $file->_open($mode);

        return $file;
    }
}
