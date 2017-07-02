<?php
/**
 * A basic sanity test to ensure Kint doesn't explode
 * on PHP versions below composer/phpunit support.
 */
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

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

set_error_handler('error');

include dirname(__FILE__).'/../vendor/kint-php/kint/build/kint.php';
include dirname(__FILE__).'/../init.php';

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

$expected = json_encode($expected);

Kint::$cli_detection = false;
Kint::$return = true;

echo 'JS'.PHP_EOL;
if (strpos(j($testdata), $expected) === false) {
    exit(1);
}

if ($error) {
    echo 'Errors occurred'.PHP_EOL;
    exit(1);
} else {
    exit(0);
}
