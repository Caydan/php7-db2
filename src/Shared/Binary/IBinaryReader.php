<?php

namespace Shared\Binary;

interface IBinaryReader
{
    function readByte() : int;
    function readBytes(int $count) : array;
    function readInt16() : int;
    function readInt24() : int;
    function readInt32() : int;
    function readInt64() : int;
    function readFloat() : float;
    function readString(bool $has16Bit, int $len) : string;
    function skip(int $bytes);
    function getOffset() : int;
    function getSize() : int;
    function setEndian(string $endian);
    function getEndian() : string;
    function isBigEndian() : bool;
    function isLittleEndian() : bool;
}
