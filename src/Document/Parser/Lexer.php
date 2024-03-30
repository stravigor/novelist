<?php

namespace Stravigor\Novelist\Document\Parser;

use Exception;

final class Lexer
{
    /**
     * @var int
     */
    private int $length;

    /**
     * @var int
     */
    private int $cursorPosition;

    /**
     * @var string
     */
    private string $documentContent;

    /**
     * @param string $documentContent
     */
    public function __construct(string $documentContent)
    {
        $this->documentContent = $documentContent;
        $this->length = mb_strlen($this->documentContent);
        $this->cursorPosition = 0;
    }

    public function current(): string
    {
        return mb_substr($this->documentContent, $this->cursorPosition, 1);
    }

    /**
     * @throws Exception
     */
    public function getNextToken(): Token
    {
        do {
            $ch = $this->consume();
            if ($ch === null) {
                return new Token(TokenType::END_OF_TOKEN);
            }
        } while ($this->isSkipCharacter($ch));

        return match ($ch) {
            '[' => new Token(TokenType::SQUARE_BRACKET_OPEN),
            ']' => new Token(TokenType::SQUARE_BRACKET_CLOSE),
            '{' => new Token(TokenType::CURLY_BRACKET_OPEN),
            '}' => new Token(TokenType::CURLY_BRACKET_CLOSE),
            '(' => new Token(TokenType::ROUND_BRACKET_OPEN),
            ')' => new Token(TokenType::ROUND_BRACKET_CLOSE),
            '=' => new Token(TokenType::EQUAL),
            ',' => new Token(TokenType::COMMA),
            '#' => $this->getCommentToken(),
            '"' => $this->getStringToken(),
            '`' => $this->getEmbeddedSourceToken(),
            '+', '-', '.', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' => $this->getNumberToken($ch),
            default => $this->getIdentifierOrLiteralToken($ch),
        };
    }

    private function consume(): ?string
    {
        if ($this->length <= $this->cursorPosition) {
            return null;
        }

        $ch = $this->current();
        $this->cursorPosition++;

        return $ch;
    }

    private function isSkipCharacter(?string $ch): bool
    {
        return $ch === ' ' || $ch === "\t" || $ch === "\n" || $ch === "\r";
    }

    /**
     * @throws Exception
     */
    private function getStringToken(): Token
    {
        $str = '';

        // Check if it's an nowdoc
        $next = mb_substr($this->documentContent, $this->cursorPosition, 2);
        $isNowDoc = ($next == '""');
        if($isNowDoc) {
            $this->cursorPosition += 2;
        }

        while (true) {
            $ch = $this->consume();

            if($isNowDoc) {
                $next = mb_substr($this->documentContent, $this->cursorPosition, 2);
                $isEndOfString = ($next == '""');
                if($isEndOfString) {
                    $this->cursorPosition += 3;
                }
            } else {
                $isEndOfString = ($ch === '"');
            }

            if ($ch === null) {
                break;
            } else if ($isEndOfString) {
                return $isNowDoc ?
                    new Token(TokenType::NOWDOC_STRING_VALUE, $str) :
                    new Token(TokenType::STRING_VALUE, $str);
            } else if ($ch !== '\\' || $isNowDoc) {
                $str .= $ch;
                continue;
            }

            $str .= match ($ch = $this->consume()) {
                '"' => '"',
                '\\' => '\\',
                '/' => '/',
                'b' => chr(0x8),
                'f' => "\f",
                'n' => "\n",
                'r' => "\r",
                't' => "\t",
                default => '\\' . $ch,
            };
        }

        throw new Exception('No end of string');
    }

    /**
     * @throws Exception
     */
    private function getEmbeddedSourceToken(): Token
    {
        $str = '';

        // Check if it's an nowdoc
        $next = mb_substr($this->documentContent, $this->cursorPosition, 2);
        $isEmbedded = ($next == '``');
        if(!$isEmbedded) {
            throw new Exception('');
        }
        $this->cursorPosition += 2;

        $ch = $this->consume();

        // Skip language declaration
        while (trim($ch) != '') {
            $ch = $this->consume();
        }

        while (true) {
            $ch = $this->consume();

            $isEndOfString = false;

            if($ch == '`') {
                $next = mb_substr($this->documentContent, $this->cursorPosition, 2);
                $isEndOfString = ($next == '``');
                if($isEndOfString) {
                    $this->cursorPosition += 3;
                }
            }

            if ($ch === null) {
                break;
            } else if ($isEndOfString) {
                return new Token(TokenType::EMBEDDED_SOURCE_CODE, trim($str));
            } else {
                $str .= $ch;
            }
        }

        throw new Exception('No end of string');
    }

    /**
     * @throws Exception
     */
    private function getIdentifierOrLiteralToken(string $str): Token
    {
        while (true) {
            $ch = $this->consume();

            if ($ch === null) {
                break;
            } else if(
                $ch == '_'
                || $ch == '-'
                || 'a' <= $ch && $ch <= 'z'
                || 'A' <= $ch && $ch <= 'Z'
            ) {
                $str .= $ch;
            } else {
                $this->cursorPosition--;
                break;
            }
        }

        return match ($str) {
            'true'  => new Token(TokenType::BOOLEAN_VALUE, true),
            'false' => new Token(TokenType::BOOLEAN_VALUE, false),
            'null'  => new Token(TokenType::NULL_VALUE),
            default => new Token(TokenType::IDENTIFIER, $str)
        };
    }

    /**
     * @return Token
     * @throws Exception
     */
    private function getCommentToken(): Token
    {
        $str = '';
        while (true) {
            $ch = $this->consume();
            if ($ch === null || $ch === PHP_EOL) {
                break;
            } else {
                $str .= $ch;
            }
        }
        return new Token(TokenType::COMMENT, $str);
    }

    /**
     * @param string $ch
     * @return Token
     */
    private function getNumberToken(string $ch): Token
    {
        $number = $ch;

        while (true) {
            $ch = $this->current();
            if (
                ('0' <= $ch && $ch <= '9')
                || $ch == '.'
                || $ch == '+'
                || $ch == '-'
                || $ch == 'e') {
                $number .= $ch;
                $this->consume();
                continue;
            }
            break;
        }

        return new Token(TokenType::NUMBER_VALUE, $number);
    }
}