<?php

namespace Permafrost\CodeSnippets;

class SnippetLine
{
    /** @var int */
    public $lineNumber = -1;

    /** @var string */
    public $value = '';

    /** @var bool */
    public $isSelected = false;

    public function __construct(int $lineNumber, string $value, bool $isSelected)
    {
        $this->lineNumber = $lineNumber;
        $this->value = $value;
        $this->isSelected = $isSelected;
    }

    public static function create(int $lineNumber, string $value, bool $isSelected): self
    {
        return new static(...func_get_args());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function lineNumber(): int
    {
        return $this->lineNumber;
    }

    public function isSelected(): bool
    {
        return $this->isSelected;
    }

    public function __toString()
    {
        return $this->value();
    }
}
