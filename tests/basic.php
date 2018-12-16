<?php

/*
 * JS renderer for Kint
 * Copyright (C) 2016 Jonathan Vollebregt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use Kint\Kint;

/**
 * A basic sanity test to ensure Kint doesn't explode
 * on PHP versions below composer/phpunit support.
 */
\error_reporting(E_ALL | E_STRICT);
\ini_set('display_errors', true);

$error = false;

/**
 * Exits as a failure for any error.
 */
function error()
{
    global $error;
    $error = true;

    return false;
}

\set_error_handler('error');

if (\getenv('KINT_FILE')) {
    $composer = \file_get_contents(__DIR__.'/../composer.lock');
    $composer = \json_decode($composer, true);
    $version = false;

    foreach ($composer['packages'] as $package) {
        if ('kint-php/kint' === $package['name']) {
            $version = $package['version'];
        }
    }

    if (!$version) {
        throw new RuntimeException('Kint not found in composer lock file');
    }

    $phar = \file_get_contents('https://raw.githubusercontent.com/kint-php/kint/'.$version.'/build/kint.phar');

    \file_put_contents(__DIR__.'/kint.phar', $phar);

    require __DIR__.'/kint.phar';
    require __DIR__.'/../'.\getenv('KINT_FILE');
} else {
    require __DIR__.'/../vendor/autoload.php';
    require __DIR__.'/../init.php';
}

$composer = require __DIR__.'/../vendor/autoload.php';

// Register the composer autoloader after the KINT_FILE autoloader
$composer->unregister();
$composer->register();

$testdata = array(
    1234,
    (object) array('abc' => 'def'),
    1234.5678,
    'Good news everyone! I\'ve got some bad news!',
    null,
);

$expected = $testdata;
$expected[] = $testdata;
$expected[5][5] = 'RECURSION';

$testdata[] = &$testdata;

$expected = \json_encode($expected);

Kint::$cli_detection = false;
Kint::$return = true;

echo 'JS'.PHP_EOL;
if (false === \strpos(j($testdata), $expected)) {
    exit(1);
}

if ($error) {
    echo 'Errors occurred'.PHP_EOL;
    exit(1);
}
    exit(0);
