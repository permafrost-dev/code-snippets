<?php

namespace Permafrost\CodeSnippets\Tests\Traits;

trait UsesTestPath
{
    protected function testsPath(string $path): string
    {
        return implode(DIRECTORY_SEPARATOR, [realpath(__DIR__ . '/..'), $path]);
    }
}
