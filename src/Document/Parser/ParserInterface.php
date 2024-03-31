<?php

namespace Stravigor\Novelist\Document\Parser;

/**
 * The ParserInterface defines the contract for parser classes.
 */
interface ParserInterface
{
    /**
     * Parses the input stream using the lexer and returns the next token.
     *
     * @param Lexer $lexer The lexer instance for tokenization.
     * @param Token|null $token The optional token to start parsing from.
     * @return Token The parsed token.
     */
    function parse(Lexer $lexer, ?Token $token = null): Token;

    /**
     * Converts the parsed data into an array representation.
     *
     * @return array The array representation of the parsed data.
     */
    function toArray(): array;
}