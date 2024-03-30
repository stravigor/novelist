<?php

namespace Stravigor\Novelist\Document\Parser;

class Token {

    /**
     * @var TokenType
     */
    protected TokenType $type;

    /**
     * @var mixed
     */
    protected mixed $value;

    /**
     * @param TokenType $type
     * @param mixed|null $value
     */
    public function __construct(TokenType $type, mixed $value = null)
    {
        $this->type  = $type;
        $this->value = $value;
    }

    /**
     * @return TokenType
     */
    public function getType(): TokenType
    {
        return $this->type;
    }

    /**
     * Get the token value
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

}