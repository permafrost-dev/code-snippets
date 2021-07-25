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
    /** @var array|int[] */
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

        if (is_int($this->linesAfter) && is_int($this->linesBefore)) {
            $range = range($this->surroundingLines[0], $this->surroundingLines[count($this->surroundingLines[0]) - 1]);

            $this->snippetLineCount = ($this->linesAfter + $this->linesBefore) + count($range);
        }

        return $this;
    }

    public function linesAfter(int $linesAfter): self
    {
        $this->linesAfter = $linesAfter;

        if (is_int($this->linesAfter) && is_int($this->linesBefore)) {
            $range = range($this->surroundingLines[0], $this->surroundingLines[count($this->surroundingLines) - 1]);

            $this->snippetLineCount = ($this->linesAfter + $this->linesBefore) + count($range);
        }

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
                $isSelected = $this->isSurroundedLineNumber($currentLineNumber);

                $code[$currentLineNumber] = SnippetLine::create($currentLineNumber, $value, $isSelected);

                $line = $file->getNextLine();
                $currentLineNumber++;
            }

            $this->code = $code;
        } catch (RuntimeException $exception) {
            $this->code = [];
        }

        return $this;
    }

    /**
     * @return array|SnippetLine[]
     */
    public function getLines(): array
    {
        return $this->code;
    }

    public function getLineNumberStart(): int
    {
        return $this->surroundingLines[0] ?? 0;
    }

    public function getLineNumberEnd(): int
    {
        return $this->surroundingLines[count($this->surroundingLines) - 1] ?? 0;
    }

    protected function isSurroundedLineNumber(int $lineNumber): bool
    {
        return in_array($lineNumber, $this->surroundingLines, true);
    }

    protected function getBounds(int $surroundingLine, int $totalNumberOfLineInFile): array
    {
        $startLine = max($surroundingLine - floor($this->snippetLineCount / 2), 1);

        $endLine = $startLine + ($this->snippetLineCount - 1);

        if ($endLine > $totalNumberOfLineInFile) {
            $endLine = $totalNumberOfLineInFile;
            $startLine = max($endLine - ($this->snippetLineCount - 1), 1);
        }

        return [$startLine, $endLine];
    }

    protected function getBoundsMulti(int $totalNumberOfLineInFile): array
    {
        $startLine = $this->surroundingLines[0];
        $endLine = $this->surroundingLines[count($this->surroundingLines) - 1];

        // snippetLineCount() was used
        if (! is_int($this->linesAfter) || ! is_int($this->linesBefore)) {
            [$startLine, $endLine] = $this->getBoundsMultiForSnippetLineCount(
                $startLine,
                $endLine,
                $totalNumberOfLineInFile
            );
        }

        // linesBefore() and linesAfter() were used
        if (is_int($this->linesAfter) && is_int($this->linesBefore)) {
            $startLine -= $this->linesBefore;
            $endLine += $this->linesAfter;

            $this->updateSnippetLineCount($startLine, $endLine);
        }

        [$startLine, $endLine] = $this->ensureBoundsAreWithinLimits($startLine, $endLine, $totalNumberOfLineInFile);
        [$startLine, $endLine] = $this->trimSnippetSize($startLine, $endLine);

        $this->updateSnippetLineCount($startLine, $endLine);

        return [$startLine, $endLine];
    }

    protected function getBoundsMultiForSnippetLineCount(int $firstLineNum, int $lastLineNum, int $totalNumberOfLineInFile): array
    {
        $startBounds = $this->getBounds($firstLineNum, $totalNumberOfLineInFile);
        $endBounds = $this->getBounds($lastLineNum, $totalNumberOfLineInFile);

        $bounds = array_merge($startBounds, $endBounds);
        sort($bounds, SORT_NUMERIC);

        $startLine = $bounds[0];
        $endLine = $bounds[count($bounds) - 1];

        return [$startLine, $endLine];
    }

    protected function updateSnippetLineCount(int $startLine, int $endLine): void
    {
        $this->snippetLineCount = ($endLine - $startLine) + 1;
    }

    protected function trimSnippetSize(int $startLine, int $endLine): array
    {
        if (count(range($startLine, $endLine)) > $this->snippetLineCount) {
            if (! in_array($endLine, $this->surroundingLines, true)) {
                $endLine--;
            }
        }

        if (count(range($startLine, $endLine)) > $this->snippetLineCount) {
            if (! in_array($startLine, $this->surroundingLines, true)) {
                $startLine++;
            }
        }

        return [$startLine, $endLine];
    }

    protected function ensureBoundsAreWithinLimits(int $startLine, int $endLine, int $totalNumberOfLineInFile): array
    {
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
