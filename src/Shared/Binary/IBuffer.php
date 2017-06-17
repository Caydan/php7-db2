<?php

namespace Shared\Binary;

interface IBuffer
{
    function nextByte() : int;
    function hasNext() : bool;
    function getOffset() : int;
    function getSize() : int;
}
