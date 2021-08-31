<?php
declare(strict_types=1);

namespace Webjump\Tests\Php\travis;
/**
 * Class GeneratePhpUnitFileForFilesDevToMasterAndCurrentBranch
 *
 * @package Webjump\Tests\Php\travis
 * @codeCoverageIgnore
 */
class GeneratePhpUnitFileForFilesDevToMasterAndCurrentBranch
{
    private const PATH_FILES_WHITELIST_PR = '/_files/changed_files_ce.txt';

    private const PATH_FILES_WHITELIST_DEV_TO_MASTER = '/_files/changed_files_dev_to_master.txt';

    private const PATH_FILE_EXAMPLE_FILE_PHPUNIT_XML = '/_files/phpunit.xml.example';

    private const STRING_TO_REPLACE_FILES_TESTS = '<<<files_tests>>>';

    private const STRING_TO_REPLACE_FILES_WHITELIST = '<<<files_whitelist>>>';

    private const STRING_TO_REPLACE_PATH_COVERAGE = '<<<path_coverage>>>';

    private $exampleFile = '';

    /**
     * Construct
     *
     * GeneratePhpUnitFileForFilesDevToMasterAndCurrentBranch constructor.
     */
    public function __construct()
    {
        $this->getExampleFile();
    }

    /**
     * Generate File PRs
     *
     * @return void
     */
    public function generateFilePr()
    {
        $filesPr = $this->getFilesPr();
        $filesSuiteOutput = '';
        $filesWhitelistOutput = '';
        foreach ($filesPr as $file) {
            if (strpos($file, 'Test/Unit') === false
                &&
                strpos($file, 'interface.php') === false
                &&
                strpos($file, strtolower('registration.php')) === false
            ) {
                $filesSuiteOutput .= '<file suffix=".php">'.$this->getBasePath(trim($file)).'</file>'.PHP_EOL;
                $fileTeste = $this->mountPathUnitTestFile($file);
                $filesWhitelistOutput .= '<file suffix="Test.php">'.$fileTeste.'</file>'.PHP_EOL;
            }
        }
        $this->exampleFile = str_replace(
            [
                self::STRING_TO_REPLACE_FILES_WHITELIST,
                self::STRING_TO_REPLACE_FILES_TESTS,
                self::STRING_TO_REPLACE_PATH_COVERAGE
            ],
            [$filesSuiteOutput, $filesWhitelistOutput, $this->getBasePath().'tests/coverage_pr/'],
            $this->exampleFile
        );
        file_put_contents($this->getBasePath().'phpunit_pr.xml', $this->exampleFile);
    }

    /**
     * Mount PAth Unit Test File
     *
     * @param $originalPathFile
     * @return string
     */
    private function mountPathUnitTestFile($originalPathFile)
    {
        $splitFile = explode('/', trim($originalPathFile));
        foreach ($splitFile as $key => $item){
            if($key > 3){
                $splitFile[$key + 1] = $item;
            }
        }
        $splitFile[4] = 'Test/Unit';
        $newFilePath = implode('/', $splitFile);
        $newFilePath = str_replace('.php', 'Test.php', $newFilePath);
        $resultPath = $this->getBasePath().$newFilePath;
        return $resultPath;
    }

    /**
     * Get file PRs
     *
     * @return array
     */
    private function getFilesPr()
    {
        $file = fopen(__DIR__.self::PATH_FILES_WHITELIST_PR, 'r');
        $arrayFiles = [];
        while (($line = fgetcsv($file)) !== FALSE) {
            array_push($arrayFiles, $line[0]);
        }
        return $arrayFiles;
    }

    /**
     * Get Example File
     *
     * @return void
     */
    private function getExampleFile()
    {
        $this->exampleFile = file_get_contents(__DIR__.self::PATH_FILE_EXAMPLE_FILE_PHPUNIT_XML);
    }

    /**
     * Get Base Path
     *
     * @param string $path
     * @return false|string
     */
    private function getBasePath($path = '')
    {
        return ($path === '') ? realpath($path).'/' : realpath($path);
    }
}

$phpunit = new GeneratePhpUnitFileForFilesDevToMasterAndCurrentBranch();
$phpunit->generateFilePr();
