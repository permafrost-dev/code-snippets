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
        $file = new File($this->getTestsPath('data/file2.txt'));

        $snippet = (new CodeSnippet())
            ->surroundingLine(3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $this->assertMatchesSnapshot($snippet->getCode());
    }

    /** @test */
    public function it_returns_no_code_when_given_a_file_that_does_not_exist()
    {
        $file = new File($this->getTestsPath('data/missing.txt'));

        $snippet = (new CodeSnippet())
            ->surroundingLine(3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $this->assertMatchesSnapshot($snippet->getCode());
    }

    /** @test */
    public function it_gets_the_line_number()
    {
        $file = new File($this->getTestsPath('data/file2.txt'));

        $snippet = (new CodeSnippet())
            ->surroundingLine(3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $this->assertEquals(3, $snippet->getLineNumber());
    }
}
