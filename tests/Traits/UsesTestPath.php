<?php

namespace Permafrost\CodeSnippets\Tests\Traits;

trait UsesTestPath
{
    public function getTestsPath(string $path): string
    {
        return implode(DIRECTORY_SEPARATOR, [realpath(__DIR__ . '/..'), $path]);
    }
}
