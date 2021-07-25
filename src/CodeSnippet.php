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
            $code = [];
            $bounds = $this->getBoundsMulti($file->numberOfLines());
            $line = $file->getLine($bounds->start);
            $currentLineNumber = $bounds->start;

            while ($currentLineNumber <= $bounds->end) {
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

    protected function getBounds(int $surroundingLine, int $totalNumberOfLineInFile): Bounds
    {
        $startLine = max($surroundingLine - floor($this->snippetLineCount / 2), 1);

        $endLine = $startLine + ($this->snippetLineCount - 1);

        if ($endLine > $totalNumberOfLineInFile) {
            $endLine = $totalNumberOfLineInFile;
            $startLine = max($endLine - ($this->snippetLineCount - 1), 1);
        }

        return Bounds::createFromArray([$startLine, $endLine]);
    }

    protected function getBoundsMulti(int $totalNumberOfLineInFile): Bounds
    {
        $bounds = Bounds::createFromArray($this->surroundingLines);

        // snippetLineCount() was used
        if (! is_int($this->linesAfter) || ! is_int($this->linesBefore)) {
            $this->getBoundsMultiForSnippetLineCount($bounds, $totalNumberOfLineInFile);
        }

        // linesBefore() and linesAfter() were used
        if (is_int($this->linesAfter) && is_int($this->linesBefore)) {
            $bounds->start -= $this->linesBefore;
            $bounds->end += $this->linesAfter;

            $this->updateSnippetLineCount($bounds);
        }

        $this->ensureBoundsAreWithinLimits($bounds, $totalNumberOfLineInFile);
        $this->trimSnippetSize($bounds);
        $this->updateSnippetLineCount($bounds);

        return $bounds;
    }

    protected function getBoundsMultiForSnippetLineCount(Bounds $bounds, int $totalNumberOfLineInFile): void
    {
        $startBounds = $this->getBounds($bounds->start, $totalNumberOfLineInFile);
        $endBounds = $this->getBounds($bounds->end, $totalNumberOfLineInFile);

        $bounds->copy($startBounds->mergeWith($endBounds));
    }

    protected function updateSnippetLineCount(Bounds $bounds): void
    {
        $this->snippetLineCount = ($bounds->end - $bounds->start) + 1;
    }

    protected function trimSnippetSize(Bounds $bounds): void
    {
        if (count(range($bounds->start, $bounds->end)) > $this->snippetLineCount) {
            if (! in_array($bounds->end, $this->surroundingLines, true)) {
                $bounds->end--;
            }
        }

        if (count(range($bounds->start, $bounds->end)) > $this->snippetLineCount) {
            if (! in_array($bounds->start, $this->surroundingLines, true)) {
                $bounds->start++;
            }
        }
    }

    protected function ensureBoundsAreWithinLimits(Bounds $bounds, int $totalNumberOfLineInFile): void
    {
        if ($bounds->start <= 0) {
            $bounds->start = 1;
        }

        if ($bounds->end > $totalNumberOfLineInFile) {
            $bounds->end = $totalNumberOfLineInFile;

            if (count($this->surroundingLines) === 1) {
                $bounds->start = max($bounds->end - ($this->snippetLineCount - 1), 1);
            }
        }
    }
}
