<?php

declare(strict_types=1);
/**
 * This file is part of laravel-horizon-restart.
 *
 * @link     https://github.com/huangdijia/laravel-horizon-restart
 * @document https://github.com/huangdijia/laravel-horizon-restart/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
use Illuminate\Foundation\Testing\TestCase;

// uses(Tests\TestCase::class)->in('Feature');
// uses(TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}