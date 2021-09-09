<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br Copyright
 * @link        http://www.webjump.com.br
 */
//phpcs:ignoreFile
declare(strict_types=1);

$pullRequestFiles = [];

$blackList = [
    'registration.php', 'setup/', 'block/'
];

$diffCommand = sprintf(
    'git diff --diff-filter=ACMR origin/%s...origin/%s --name-only',
    $argv[2], $argv[1]
);

exec($diffCommand, $pullRequestFiles);

// Ignore deleted files
$pullRequestFiles = array_filter($pullRequestFiles, 'file_exists');

// Filter test and code files
$phpFiles = [];
$testFiles = [];

foreach ($pullRequestFiles as $file) {
    $fileInfo = pathinfo($file);

    if (strpos(strtolower($file), 'setup/') !== false) {
        continue;
    }

    if (strpos(strtolower($file), 'block/') !== false) {
        continue;
    }

    if (strstr($fileInfo['dirname'], 'app/code/') === false) {
        continue;
    }

    if ($fileInfo['extension'] != 'php') {
        continue;
    }

    if (in_array($fileInfo['basename'], $blackList)) {
        continue;
    }

    if (substr($fileInfo['filename'], -9) == 'Interface') {
        continue;
    }

    if (substr($fileInfo['filename'], -4) == 'Test') {
        continue;
    }
    array_push($phpFiles, $file);
}
$retCode = 0;
if(empty($phpFiles)) {
    exit($retCode);
}

$newTestFiles = [];
foreach($phpFiles as $modifiedFile) {
    $newTestFile = $modifiedFile;
    $fileToArray = explode('/', $newTestFile);
    if (empty($fileToArray[3])) {
        continue;
    }
    $fileToArray[3] .= '/Test/Unit';
    $fileName = array_pop($fileToArray);
    $fileName = str_replace('.php', 'Test.php', $fileName);
    array_push($fileToArray, $fileName);
    $newTestFile = implode('/', $fileToArray);
    array_push($newTestFiles, ['class' => $modifiedFile, 'test' => $newTestFile]);
}

$missingFiles = [];

foreach ($newTestFiles as $newTestFile) {
    if (!file_exists($newTestFile['test'])) {
       array_push($missingFiles, $newTestFile['class']);
    }
    array_push($testFiles, $newTestFile['test']);
}

if (count($missingFiles) > 0) {
    print '*************************************'.PHP_EOL;
    print '**** There are untested files *******'.PHP_EOL;
    print '*************************************'.PHP_EOL;
    print 'FILES:'.PHP_EOL;
    print implode(PHP_EOL, $missingFiles);
    print PHP_EOL;
    print '*************************************'.PHP_EOL;
    exit(1);
}


// Build the XML testsuite replacement
$testFilesXml = '';
foreach ($testFiles as $testFile) {
    $testFilesXml .= sprintf("<file>../../../%s</file>\n", $testFile);
}

$filterFilesXml = '';
foreach ($phpFiles as $phpFile) {
    $filterFilesXml .= sprintf("<file>../../../%s</file>\n", $phpFile);
}

$basePhpUnitXml = file_get_contents('dev/tests/unit/pipeline-phpunit.xml.dist');

file_put_contents(
    'dev/tests/unit/pipeline-phpunit.xml',
    str_replace(
        ['%TESTSUITE_FILES%', '%TESTSUITE_FILTERS%'],
        [$testFilesXml, $filterFilesXml],
        $basePhpUnitXml
    )
);

passthru(
    './vendor/bin/phpunit -c dev/tests/unit/pipeline-phpunit.xml --testsuite="Pipeline_TestSuite"',
    $retCode
);

exit($retCode);
