<?php

namespace Stravigor\Novelist\Document\Stream;

interface InputStreamInterface
{
    /**
     * Reads a chunk of bytes from the stream.
     *
     * @return string A chunk of bytes, or an empty string when there's nothing left to read.
     */
    function read(): string;

    /**
     * Checks if the end of the stream has been reached.
     * @return bool True if the end of the stream has been reached, false otherwise.
     */
    function isEndOfStream(): bool;
}