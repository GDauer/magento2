<?php
// @codingStandardsIgnoreFile

namespace Webjump\Tests\Php;

use Exception;
use PHPMD\TextUI\Command;
use Magento\Framework\App\Utility\Files;
use Webjump\Tests\Php\Tools\CodeMessDetector;
use Webjump\Tests\Php\Tools\CodeSniffer;
use Webjump\Tests\Php\Tools\CopyPasteDetector;
use Webjump\Tests\Php\Tools\Wrapper;
use PHPUnit\Framework\TestCase;

/**
 * Set of tests for static code analysis, e.g. code style, code complexity, copy paste detecting, etc.
 * @codeCoverageIgnore
 */
class LiveCodeTest extends TestCase
{
    /**
     * @var string
     */
    protected static $reportDir = '';

    /**
     * @var string
     */
    protected static $pathToSource = '';

    /**
     * Setup basics for all tests
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$pathToSource = BP;
        self::$reportDir = self::$pathToSource . '/tests/static/report';
        echo self::$reportDir;
        if (!is_dir(self::$reportDir)) {
            mkdir(self::$reportDir, "777", true);
        }
    }

    /**
     * Returns base folder for suite scope
     *
     * @return string
     */
    private static function getBaseFilesFolder()
    {
        return __DIR__;
    }

    /**
     * Returns base directory for whitelisted files
     *
     * @return string
     */
    private static function getChangedFilesBaseDir()
    {
        return __DIR__ . '/..';
    }

    /**
     * Returns whitelist based on blacklist and git changed files
     *
     * @param array $fileTypes
     * @param string $changedFilesBaseDir
     * @param string $baseFilesFolder
     * @return array
     * @throws Exception
     */
    public static function getWhitelist($fileTypes = ['php'], $changedFilesBaseDir = '', $baseFilesFolder = '')
    {
        $globPatternsFolder = self::getBaseFilesFolder();
        if ('' !== $baseFilesFolder) {
            $globPatternsFolder = $baseFilesFolder;
        }

        $directoriesToCheck = Files::init()->readLists($globPatternsFolder . '/_files/whitelist/common.txt');
        $changedFiles = [];
        $globFilesListPattern = ($changedFilesBaseDir ?: self::getChangedFilesBaseDir()) . '/Php/_files/changed_files_ce*';

        foreach (glob($globFilesListPattern) as $listFile) {
            $changedFiles = array_merge($changedFiles, file($listFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
        }

        array_walk(
            $changedFiles,
            function (&$file) {
                $file = BP . '/' . $file;
            }
        );

        $changedFiles = array_filter(
            $changedFiles,
            function ($path) use ($directoriesToCheck, $fileTypes) {
                if (!file_exists($path)) {
                    echo "Não encontrou arquivo $path".PHP_EOL;
                    return false;
                }
                $path = realpath($path);
                foreach ($directoriesToCheck as $directory) {
                    $directory = realpath($directory);
                    if (strpos($path, $directory) === 0) {
                        if (!empty($fileTypes)) {
                            return in_array(pathinfo($path, PATHINFO_EXTENSION), $fileTypes);
                        }
                        return true;
                    }
                }
                return false;
            }
        );
        return $changedFiles;
    }

    /**
     * Run the magento specific coding standards on the code
     *
     * @return void
     * @throws Exception
     */
    public function testCodeStyle()
    {
        $reportFile = self::$reportDir . '/phpcs_report.txt';
        if (!file_exists($reportFile)) {
            touch($reportFile);
        }

        $codeSniffer = new CodeSniffer(realpath(self::$pathToSource . '/phpcs/ruleset.xml'), $reportFile, new Wrapper());
        $result = $codeSniffer->run(self::getWhitelist(['php', 'phtml']));
        $report = file_get_contents($reportFile);

        $this->assertEquals(
            0,
            $result,
            "PHP Code Sniffer detected {$result} violation(s): " . PHP_EOL . $report
        );
    }

    /**
     * Run mess detector on code
     *
     * @return void
     * @throws Exception
     */
    public function testCodeMess()
    {
        $reportFile = self::$reportDir . '/phpmd_report.txt';
        $codeMessDetector = new CodeMessDetector(realpath(__DIR__ . '/_files/phpmd/ruleset.xml'), $reportFile);

        if (!$codeMessDetector->canRun()) {
            $this->markTestSkipped('PHP Mess Detector is not available.');
        }
        $result = $codeMessDetector->run(self::getWhitelist(['php']));
        $output = "";
        if (file_exists($reportFile)) {
            $output = file_get_contents($reportFile);
        }
        $this->assertEquals(
            Command::EXIT_SUCCESS,
            $result,
            "PHP Code Mess has found error(s):" . PHP_EOL . $output
        );
        // delete empty reports
        if (file_exists($reportFile)) {
            unlink($reportFile);
        }
    }

    /**
     * Run copy paste detector on code
     *
     * @return void
     * @throws Exception
     */
    public function testCopyPaste()
    {
        $reportFile = self::$reportDir . '/phpcpd_report.xml';
        $copyPasteDetector = new CopyPasteDetector($reportFile);

        if (!$copyPasteDetector->canRun()) {
            $this->markTestSkipped('PHP Copy/Paste Detector is not available.');
        }

        $blackList = [];
        foreach (glob(__DIR__ . '/_files/phpcpd/blacklist/*.txt') as $list) {
            $blackList = array_merge($blackList, file($list, FILE_IGNORE_NEW_LINES));
        }

        $copyPasteDetector->setBlackList($blackList);


        $result = null;
        try {
            $fileList = self::getWhitelist(['php']);
            if(count($fileList) < 1) {
                $result = true;
            } else {
                $result = $copyPasteDetector->run($fileList);
            }
        } catch (\Exception $e) {
            echo "Error in Copy And Paste ".$e->getMessage();
        }

        $output = "";
        if (file_exists($reportFile)) {
            $output = file_get_contents($reportFile);
        }

        $this->assertTrue(
            $result,
            "PHP Copy/Paste Detector has found error(s):" . PHP_EOL . $output
        );
    }

    /**
     * Tests whitelisted files for strict type declarations.
     */
    public function testStrictTypes()
    {
        $changedFiles = self::getWhitelist(['php']);

        try {
            $blackList = Files::init()->readLists(
                self::getBaseFilesFolder() . '/_files/blacklist/strict_type.txt'
            );
        } catch (\Exception $e) {
            // nothing matched black list
            $blackList = [];
        }

        $toBeTestedFiles = array_diff(
            $changedFiles,
            $blackList
        );

        $filesMissingStrictTyping = [];
        foreach ($toBeTestedFiles as $fileName) {
            $file = file_get_contents($fileName);
            if (strstr($file, 'strict_types=1') === false) {
                $filesMissingStrictTyping[] = $fileName;
            }
        }

        $this->assertEquals(
            0,
            count($filesMissingStrictTyping),
            "Following files are missing strict type declaration:"
            . PHP_EOL
            . implode(PHP_EOL, $filesMissingStrictTyping)
        );
    }
}
