<?php
/**
 * Code standard tool wrapper interface
 */
namespace Webjump\Tests\Php\Tools;

/**
 * Interface ToolInterface
 * @package Webjump\Tests\Php\Tools
 */
interface ToolInterface
{
    /**
     * Whether the tool can be ran on the current environment
     *
     * @return bool
     */
    public function canRun();

    /**
     * Run tool for files specified
     *
     * @param array $whiteList Files/directories to be inspected
     *
     * @return int
     */
    public function run(array $whiteList);
}
