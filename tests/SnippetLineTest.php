<?php

namespace Permafrost\CodeSnippets\Tests;

use Permafrost\CodeSnippets\SnippetLine;
use PHPUnit\Framework\TestCase;

class SnippetLineTest extends TestCase
{
    /** @test */
    public function it_displays_the_value_when_cast_to_a_string()
    {
        $line = new SnippetLine(1, 'test', true);

        $this->assertEquals('test', (string)$line);
    }

    /** @test */
    public function it_returns_the_correct_value()
    {
        $line = new SnippetLine(1, 'test', true);

        $this->assertEquals('test', $line->value());
    }

    /** @test */
    public function it_returns_the_correct_line_number()
    {
        $line = new SnippetLine(3, 'test', true);

        $this->assertEquals(3, $line->lineNumber());
    }

    /** @test */
    public function it_returns_the_correct_selected_state()
    {
        $line1 = new SnippetLine(1, 'test', true);
        $line2 = new SnippetLine(1, 'test', false);

        $this->assertTrue($line1->isSelected());
        $this->assertFalse($line2->isSelected());
    }
}
