<?php

namespace Permafrost\CodeSnippets\Tests\Traits;

trait UsesTestPath
{
    public function testPath(string $path): string
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, $path]);
    }
}
