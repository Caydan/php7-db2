<?php

namespace Shared\Binary;

use \Shared\Binary\IBinaryReader;
use \Shared\Binary\IBuffer;

class BinaryReader implements IBinaryReader
{
    const BIG_ENDIAN = 'bigendian';
    const LITTLE_ENDIAN = 'littleendian';

    protected $endian;
    protected $buffer;

    public function __construct(IBuffer $buffer)
    {
        $this->buffer = $buffer;
        $this->endian = BinaryReader::BIG_ENDIAN;
    }

    public function reset()
    {
        $this->buffer->rewind();
    }

    public function setEndian(string $endian)
    {
        if ($endian != BinaryReader::BIG_ENDIAN && $endian != BinaryReader::LITTLE_ENDIAN)
            throw new \Exception("invalid endian");

        $this->endian = $endian;
    }

    public function getEndian() : string
    {
        return $this->endian;
    }

    public function isBigEndian() : bool 
    {
        return $this->endian == BinaryReader::BIG_ENDIAN;
    }

    public function isLittleEndian() : bool 
    {
        return $this->endian == BinaryReader::LITTLE_ENDIAN;
    }

    public function readByte() : int
    {
        if (!$this->buffer->hasNext())
            throw new \Exception("end reached");

        return $this->buffer->nextByte();
    }

    public function readBytes(int $count) : array
    {
        $bytes = [];
        for ($i = 0; $i < $count; ++$i)
            $bytes[] = $this->readByte();
        return $bytes;
    }

    public function readInt16() : int
    {
        $bytes = $this->readBytes(2);

        if ($this->isBigEndian())
            return unpack("L", pack("C*", $bytes[0], $bytes[1], 0, 0))[1];
        else
            return unpack("L", pack("C*", 0, 0, $bytes[1], $bytes[0]))[1];
    }

    public function readInt24() : int
    {
        $bytes = $this->readBytes(3);

        if ($this->isBigEndian())
            return unpack("L", pack("C*", 0, $bytes[0], $bytes[1], $bytes[2]))[1];
        else
            return unpack("L", pack("C*", 0, $bytes[1], $bytes[0], $bytes[2]))[1];
    }

    public function readInt32() : int
    {
        $bytes = $this->readBytes(4);
        if ($this->isBigEndian())
            return unpack("L", pack("C*", $bytes[0], $bytes[1], $bytes[2], $bytes[3]))[1];
        else
            return unpack("L", pack("C*", $bytes[3], $bytes[2], $bytes[1], $bytes[0]))[1];
    }

    public function readInt64() : int
    {
        $bytes = $this->readBytes(8);
        if ($this->isBigEndian())
            return unpack("L", pack("C*", $bytes[0], $bytes[1], $bytes[2], $bytes[3], $bytes[4], $bytes[5], $bytes[6], $bytes[7]))[1];
        else
            return unpack("L", pack("C*", $bytes[7], $bytes[6], $bytes[5], $bytes[4], $bytes[3], $bytes[2], $bytes[1], $bytes[0]))[1];
    }

    public function readFloat() : float
    {
        $bytes = $this->readBytes(4);
        if ($this->isBigEndian())
            return unpack("f", pack("C*", $bytes[0], $bytes[1], $bytes[2], $bytes[3]))[1];
        else
            return unpack("f", pack("C*", $bytes[3], $bytes[2], $bytes[1], $bytes[0]))[1];
    }

    public function readString(bool $has16Bit = false, int $len = -1) : string
    {
        $str = "";
        $cnt = 0;
        $char = 0;

        do
        {
            $char = $has16Bit ? $this->readInt16() : $this->buffer->nextByte();
            $str .= chr($char);
        } while ($char !== 0 && ($len == -1 || $len > ++$cnt));

        return $str;
    }

    public function skip(int $bytes)
    {
        $this->readBytes($bytes);
    }

    public function getOffset() : int
    {
        return $this->buffer->getOffset();
    }

    public function getSize() : int
    {
        return $this->buffer->getSize();
    }
}