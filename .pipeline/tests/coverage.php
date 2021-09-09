<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

if (!file_exists('dev/tests/unit/test-reports/phpunit.coverage.xml')) {
    exit(0);
}

$retCode = 0;
passthru(
    './vendor/bin/coverage-check dev/tests/unit/test-reports/phpunit.coverage.xml 80',
    $retCode
);

exit($retCode);