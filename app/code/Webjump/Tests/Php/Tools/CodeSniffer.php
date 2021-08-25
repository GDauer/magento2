<?php
namespace Webjump\Tests\Php\Tools;

/**
 * Class CodeSniffer
 *
 * @package Webjump\Tests\Php\Tools
 */
class CodeSniffer implements ToolInterface, ExtensionInterface
{
    /**
     * Ruleset directory
     *
     * @var string
     */
    private $rulesetDir;

    /**
     * Report file
     *
     * @var string
     */
    private $reportFile;

    /**
     * PHPCS cli tool wrapper
     *
     * @var Wrapper
     */
    private $wrapper;

    /**
     * List of extensions for tool run
     *
     * @var array
     */
    private $extensions = ['php'];

    /**
     * Constructor
     *
     * @param string  $rulesetDir \Directory that locates the inspection rules
     * @param string  $reportFile Destination file to write inspection report to
     * @param Wrapper $wrapper    Wrapper
     */
    public function __construct($rulesetDir, $reportFile, Wrapper $wrapper)
    {
        $this->reportFile = $reportFile;
        $this->rulesetDir = $rulesetDir;
        $this->wrapper = $wrapper;
    }

    /**
     * Set Extensions
     *
     * @param array $extensions Extensions
     *
     * @return void
     */
    public function setExtensions(array $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * Whether the tool can be ran on the current environment
     *
     * @return bool
     */
    public function canRun()
    {
        return class_exists('\PHP_CodeSniffer\Runner');
    }

    /**
     * Run PHPCS
     *
     * @param array $whiteList Whitelist
     * @throws \PHP_CodeSniffer\Exceptions\DeepExitException
     * @return array|int
     */
    public function run(array $whiteList)
    {
        if (empty($whiteList)) {
            return 0;
        }

        if (!defined('PHP_CODESNIFFER_IN_TESTS')) {
            define('PHP_CODESNIFFER_IN_TESTS', true);
        }

        $this->wrapper->checkRequirements();
        $settings = [];
        $settings['files'] = $whiteList;
        $settings['standards'] = [$this->rulesetDir];
        $settings['extensions'] = $this->extensions;
        $settings['reports']['full'] = $this->reportFile;
        $this->wrapper->setSettings($settings);

        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        ob_start();
        $result = $this->wrapper->runPHPCS();
        ob_end_clean();

        return $result;
    }
}
