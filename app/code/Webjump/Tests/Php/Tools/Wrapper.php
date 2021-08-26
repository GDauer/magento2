<?php
namespace Webjump\Tests\Php\Tools;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Runner;

/**
 * Class Wrapper
 *
 * @package Webjump\Tests\Php\Tools
 */
class Wrapper extends Runner
{
    /**
     * @var array
     */
    private $settings = [];

    /**
     * Return the current version of php code sniffer
     *
     * @return string
     */
    public function version()
    {
        $version = '0.0.0';
        if (defined('\PHP_CodeSniffer\Config::VERSION')) {
            $version = Config::VERSION;
        }
        return $version;
    }

    /**
     * Initialize PHPCS runner and modifies the configuration settings
     *
     * @throws \PHP_CodeSniffer\Exceptions\DeepExitException
     * @return void
     */
    public function init()
    {
        $this->config->extensions = $this->settings['extensions'];
        unset($this->settings['extensions']);

        $settings = $this->config->getSettings();
        unset($settings['files']);

        $this->config->setSettings($settings);

        $this->config->setSettings(
            array_replace_recursive(
                $this->config->getSettings(),
                $this->settings
            )
        );
        parent::init();
    }

    /**
     * Sets the settings
     *
     * @param array $settings Settings
     * @return void
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }
}
