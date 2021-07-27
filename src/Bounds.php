<?php

namespace Permafrost\CodeSnippets;

class Bounds
{
    /** @var int */
    public $start = 0;

    /** @var int */
    public $end = 0;

    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public static function create(int $start, int $end): self
    {
        return new static(...func_get_args());
    }

    public static function createFromArray(array $lineNumbers): self
    {
        sort($lineNumbers, SORT_NUMERIC);

        return static::create($lineNumbers[0], $lineNumbers[count($lineNumbers) - 1]);
    }

    public function toArray(): array
    {
        return [$this->start, $this->end];
    }

    public function mergeWith(self $bounds): self
    {
        $data = array_merge($this->toArray(), $bounds->toArray());

        return static::createFromArray($data);
    }

    public function copy(self $bounds): self
    {
        $this->start = $bounds->start;
        $this->end = $bounds->end;

        return $this;
    }

    public function count(): int
    {
        return count(range($this->start, $this->end));
    }
}
