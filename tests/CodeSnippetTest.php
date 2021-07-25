<?php

namespace Permafrost\CodeSnippets\Tests;

use Permafrost\CodeSnippets\CodeSnippet;
use Permafrost\CodeSnippets\File;
use Permafrost\CodeSnippets\Tests\Traits\UsesTestPath;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CodeSnippetTest extends TestCase
{
    use MatchesSnapshots;
    use UsesTestPath;

    /** @test */
    public function it_gets_a_snippet_from_a_file()
    {
        $file = new File($this->testsPath('data/file2.txt'));

        $snippet = (new CodeSnippet())
            ->surroundingLine(3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $this->assertMatchesSnapshot($snippet->getCode());
    }

    /** @test */
    public function it_gets_a_snippet_from_a_file_using_linesBefore_and_linesAfter()
    {
        $file = new File($this->testsPath('data/file2.txt'));

        $snippet = (new CodeSnippet())
            ->surroundingLine(3)
            ->linesBefore(1)
            ->linesAfter(1)
            ->fromFile($file);

        $this->assertEquals(3, $snippet->getSnippetLineCount());
        $this->assertMatchesSnapshot($snippet->getCode());
    }

    /** @test */
    public function it_gets_a_multi_line_snippet_from_a_file()
    {
        $file = new File($this->testsPath('data/file1.php'));

        $snippet = (new CodeSnippet())
            ->surroundingLines(2, 3)
            ->linesBefore(0)
            ->linesAfter(1)
            ->fromFile($file);

        $this->assertEquals(3, $snippet->getSnippetLineCount());
        $this->assertMatchesSnapshot($snippet->getCode());
    }

    /** @test */
    public function it_gets_a_multi_line_snippet_from_a_file_using_snippetLineCount()
    {
        $file = new File($this->testsPath('data/file1.php'));

        $snippet = (new CodeSnippet())
            ->surroundingLines(2, 3)
            ->snippetLineCount(4)
            ->fromFile($file);

        $this->assertEquals(4, $snippet->getSnippetLineCount());
        $this->assertMatchesSnapshot($snippet->getCode());
    }

    /** @test */
    public function it_returns_no_code_when_given_a_file_that_does_not_exist()
    {
        $file = new File($this->testsPath('data/missing.txt'));

        $snippet = (new CodeSnippet())
            ->surroundingLine(3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $this->assertMatchesSnapshot($snippet->getCode());
    }

    /** @test */
    public function it_gets_the_line_number()
    {
        $file = new File($this->testsPath('data/file2.txt'));

        $snippet = (new CodeSnippet())
            ->surroundingLine(3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $this->assertEquals(3, $snippet->getLineNumberStart());
        $this->assertEquals(3, $snippet->getLineNumberEnd());
    }
}
