<?php
/*
 * This file is part of Stravigor Novelist, a PHP library for generating Laravel applications based on specifications.
 *
 * @package     Novelist
 * @author      Liva Ramarolahy <lr@stravigor.com>
 * @link        https://github.com/stravigor/novelist
 * @license     MIT License (https://opensource.org/licenses/MIT)
 */

namespace Novelist\Document\Parser;

/**
 * Enumerates the possible types of tokens generated by the lexer during the parsing process.
 */
enum TokenType: string
{
    case START_OF_TOKEN       = 'START_OF_TOKEN';
    case END_OF_TOKEN         = 'END_OF_TOKEN';
    case COMMA                = 'COMMA';
    case COMMENT              = 'COMMENT';
    case EQUAL                = 'EQUAL';
    case IDENTIFIER           = 'IDENTIFIER';
    case CURLY_BRACKET_OPEN   = 'CURLY_BRACKET_OPEN';
    case CURLY_BRACKET_CLOSE  = 'CURLY_BRACKET_CLOSE';
    case ROUND_BRACKET_OPEN   = 'ROUND_BRACKET_OPEN';
    case ROUND_BRACKET_CLOSE  = 'ROUND_BRACKET_CLOSE';
    case SQUARE_BRACKET_OPEN  = 'SQUARE_BRACKET_OPEN';
    case SQUARE_BRACKET_CLOSE = 'SQUARE_BRACKET_CLOSE';
    case NULL_VALUE           = 'NULL_VALUE';
    case BOOLEAN_VALUE        = 'BOOLEAN_VALUE';
    case NUMBER_VALUE         = 'NUMBER_VALUE';
    case STRING_VALUE         = 'STRING_VALUE';
    case NOWDOC_STRING_VALUE  = 'NOWDOC_STRING_VALUE';
    case EMBEDDED_SOURCE_CODE = 'EMBEDDED_SOURCE_CODE';
}