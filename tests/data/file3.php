<?php

class MyClass
{
    protected function test()
    {
        /**
        @var Ray $ray
         */
        $ray = ray()->text('hello world')
            ->blue()
            ->large();

        Ray
            ::rateLimiter()
            ->count(5);

        collect([])
            ->map(function($a) {
                return $a;
            })
            ->filter()
            ->ray();
    }
}

function test($foo)
{
    ray($foo);

    // ray(12);

    $one = strtolower("test" . ' two');
    $two = '2';

    if ($two === '2') {
        rd($two);
    }

    return $one . $two . $foo;
}
