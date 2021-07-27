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

        $this->assertMatchesSnapshot($snippet->getLines());
    }

    /** @test */
    public function it_gets_a_snippet_from_a_file_and_constrains_the_snippet_size_to_the_number_of_file_lines()
    {
        $file = new File($this->testsPath('data/file2.txt'));

        $snippet1 = (new CodeSnippet())
            ->surroundingLines(2, 3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $snippet2 = (new CodeSnippet())
            ->surroundingLine(10)
            ->snippetLineCount(3)
            ->fromFile($file);

        $snippet3 = (new CodeSnippet())
            ->surroundingLine(3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $snippet4 = (new CodeSnippet())
            ->surroundingLine(3)
            ->linesBefore(1)
            ->linesAfter(1)
            ->fromFile($file);

        $snippet5 = (new CodeSnippet())
            ->surroundingLines(3, 4)
            ->linesBefore(1)
            ->linesAfter(1)
            ->fromFile($file);

        $this->assertMatchesSnapshot($snippet1->getLines());
        $this->assertMatchesSnapshot($snippet2->getLines());
        $this->assertMatchesSnapshot($snippet3->getLines());
        $this->assertMatchesSnapshot($snippet4->getLines());
        $this->assertMatchesSnapshot($snippet5->getLines());
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
        $this->assertMatchesSnapshot($snippet->getLines());
    }

    /** @test */
    public function it_gets_a_multi_line_snippet_from_a_file()
    {
        $file = new File($this->testsPath('data/file1.php'));

        $snippet = (new CodeSnippet())
            ->surroundingLines(2, 3)
            ->linesBefore(0)
            ->linesAfter(1)
            //->snippetLineCount(3)
            ->fromFile($file);

        $this->assertCount(3, $snippet->getLines());
        $this->assertMatchesSnapshot($snippet->getLines());
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
        $this->assertMatchesSnapshot($snippet->getLines());
    }

    /** @test */
    public function it_returns_no_code_when_given_a_file_that_does_not_exist()
    {
        $file = new File($this->testsPath('data/missing.txt'));

        $snippet = (new CodeSnippet())
            ->surroundingLine(3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $this->assertMatchesSnapshot($snippet->getLines());
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

    /** @test */
    public function it_ensures_the_entire_surrounding_lines_are_displayed()
    {
        $file = new File($this->testsPath('data/file3.php'));

        $snippet = (new CodeSnippet())
            ->surroundingLines(18, 23)
            ->snippetLineCount(4)
            ->fromFile($file);

        $this->assertMatchesSnapshot($snippet->getLines());
    }

    /** @test */
    public function it_returns_the_snippet_as_a_string()
    {
        $snippet = (new CodeSnippet())
            ->surroundingLines(10, 12)
            ->snippetLineCount(8)
            ->fromFile($this->testsPath('data/file3.php'));

        $this->assertMatchesSnapshot($snippet->toString());
    }

    /** @test */
    public function it_returns_a_string_when_the_snippet_is_cast_to_a_string()
    {
        $snippet = (new CodeSnippet())
            ->surroundingLines(10, 12)
            ->snippetLineCount(8)
            ->fromFile($this->testsPath('data/file3.php'));

        $this->assertMatchesSnapshot((string)$snippet);
    }

    /** @test */
    public function it_returns_the_line_numbers()
    {
        $snippet = (new CodeSnippet())
            ->surroundingLines(10, 12)
            ->snippetLineCount(8)
            ->fromFile($this->testsPath('data/file3.php'));

        $this->assertCount(8, $snippet->getLineNumbers());
        $this->assertMatchesSnapshot($snippet->getLineNumbers());
    }
}
