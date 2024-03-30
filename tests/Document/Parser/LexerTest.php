<?php

namespace Document\Parser;

use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Stravigor\Novelist\Document\Parser\Lexer;
use Stravigor\Novelist\Document\Parser\TokenType;

class LexerTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    #[TestDox('Test cursor position')]
    public function cursorPosition(): void
    {
        $lexer = new Lexer('{}');
        $actual = $lexer->current();
        $expected = '{';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    #[Test]
    #[TestDox('Parsing $input to results in $expected')]
    #[DataProvider('dataProvider')]
    public function getNextToken($input, $expected)
    {
        $lexer = new Lexer($input);
        $actual = $lexer->getNextToken();
        $this->assertEquals($expected, $actual->getType());
    }

    public static function dataProvider(): array
    {
        return [
            'parse SQUARE_BRACKET_OPEN' => [
                '[',
                TokenType::SQUARE_BRACKET_OPEN,
            ],
            'parse SQUARE_BRACKET_CLOSE more string' => [
                ']',
                TokenType::SQUARE_BRACKET_CLOSE,
            ],
            'parse CURLY_BRACKET_OPEN more string' => [
                '{',
                TokenType::CURLY_BRACKET_OPEN,
            ],
            'parse CURLY_BRACKET_CLOSE more string' => [
                '}',
                TokenType::CURLY_BRACKET_CLOSE,
            ],
            'parse ROUND_BRACKET_OPEN more string' => [
                '(',
                TokenType::ROUND_BRACKET_OPEN,
            ],
            'parse ROUND_BRACKET_CLOSE more string' => [
                ')',
                TokenType::ROUND_BRACKET_CLOSE,
            ],
            'parse EQUAL more string' => [
                '=',
                TokenType::EQUAL,
            ],
            'parse COMMA more string' => [
                ',',
                TokenType::COMMA,
            ],
            'parse STRING_VALUE more string' => [
                '"test"',
                TokenType::STRING_VALUE,
            ],
            'parse NUMBER_VALUE more string' => [
                '0',
                TokenType::NUMBER_VALUE,
            ],
            'parse BOOLEAN_VALUE true' => [
                'true ',
                TokenType::BOOLEAN_VALUE,
            ],
            'parse BOOLEAN_VALUE false' => [
                'false ',
                TokenType::BOOLEAN_VALUE,
            ],
            'parse NULL_VALUE' => [
                'null ',
                TokenType::NULL_VALUE,
            ],
            'parse IDENTIFIER' => [
                'namespace',
                TokenType::IDENTIFIER,
            ],
            'parse COMMENT' => [
                '# test',
                TokenType::COMMENT,
            ],
            'parse NOWDOC_STRING_VALUE' => [
                '""" test """',
                TokenType::NOWDOC_STRING_VALUE,
            ],

            'parse EMBEDDED_SOURCE_CODE' => [
                '``` test ```',
                TokenType::EMBEDDED_SOURCE_CODE,
            ],

            'parse END_OF_TOKEN' => [
                '',
                TokenType::END_OF_TOKEN,
            ],
        ];
    }
}