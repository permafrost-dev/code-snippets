<?php

namespace Permafrost\CodeSnippets;

use RuntimeException;

/**
 * Original code from spatie/backtrace
 *
 * @link https://github.com/spatie/backtrace/blob/master/src/CodeSnippet.php
 */
class CodeSnippet
{
    /** @var int */
    protected $surroundingLine = 1;

    protected $surroundingLines = [];

    /** @var int */
    protected $snippetLineCount = 9;

    /** @var int|null */
    protected $linesBefore = null;

    /** @var int|null */
    protected $linesAfter = null;

    /** @var array|string[] */
    protected $code = [];

    public function surroundingLine(int $surroundingLine): self
    {
        $this->surroundingLines = [$surroundingLine];

        return $this;
    }

    public function surroundingLines(int $surroundingLineFirst, int $surroundingLineLast): self
    {
        $this->surroundingLines = range($surroundingLineFirst, $surroundingLineLast);

        return $this;
    }

    public function snippetLineCount(int $snippetLineCount): self
    {
        $this->snippetLineCount = $snippetLineCount;

        return $this;
    }

    public function linesBefore(int $linesBefore): self
    {
        $this->linesBefore = $linesBefore;

        return $this;
    }

    public function linesAfter(int $linesAfter): self
    {
        $this->linesAfter = $linesAfter;

        return $this;
    }

    public function getSnippetLineCount(): int
    {
        return $this->snippetLineCount;
    }

    /**
     * @param File|string $file
     * @return static
     */
    public function fromFile($file): self
    {
        if (is_string($file)) {
            $file = new File($file);
        }

        if (! $file instanceof File) {
            $this->code = [];

            return $this;
        }

        if (! $file->exists()) {
            $this->code = [];

            return $this;
        }

        try {
            [$startLineNumber, $endLineNumber] = $this->getBoundsMulti($file->numberOfLines());

            $code = [];

            $line = $file->getLine($startLineNumber);

            $currentLineNumber = $startLineNumber;

            while ($currentLineNumber <= $endLineNumber) {
                $value = rtrim(substr($line, 0, 250));
                $code[$currentLineNumber] = SnippetLine::create($currentLineNumber, $value, $this->isSurroundedLineNumber($currentLineNumber));

                $line = $file->getNextLine();
                $currentLineNumber++;
            }

            $this->code = $code;
        } catch (RuntimeException $exception) {
            $this->code = [];
        }

        return $this;
    }

    public function getCode(): array
    {
        return $this->code;
    }

    public function getLineNumberStart(): int
    {
        return $this->surroundingLines[0];
    }

    public function getLineNumberEnd(): int
    {
        return $this->surroundingLines[count($this->surroundingLines) - 1];
    }

    protected function isSurroundedLineNumber(int $lineNumber): bool
    {
        return in_array($lineNumber, $this->surroundingLines, true);
    }

    protected function getBoundsMulti(int $totalNumberOfLineInFile): array
    {
        $firstLine = max($this->surroundingLines[0] - floor($this->snippetLineCount / 2), 1);
        $lastLine = max($this->surroundingLines[count($this->surroundingLines) - 1] - floor($this->snippetLineCount / 2), 1);
        $endLine = $lastLine + ($this->snippetLineCount - 1);
        $startLine = $firstLine - ($this->snippetLineCount - 1);

        if (is_int($this->linesAfter) && is_int($this->linesBefore)) {
            $firstLine = max($this->surroundingLines[0], 1);
            $lastLine = max($this->surroundingLines[count($this->surroundingLines) - 1], 1);

            $startLine = max($firstLine - $this->linesBefore, 1);
            $endLine = max($lastLine + $this->linesAfter, 1);

            $this->snippetLineCount = ($endLine - $startLine) + 1;
        }

        if ($startLine <= 0) {
            $startLine = 1;
        }

        if ($endLine > $totalNumberOfLineInFile) {
            $endLine = $totalNumberOfLineInFile;

            if (count($this->surroundingLines) === 1) {
                $startLine = max($endLine - ($this->snippetLineCount - 1), 1);
            }
        }

        return [$startLine, $endLine];
    }
}
