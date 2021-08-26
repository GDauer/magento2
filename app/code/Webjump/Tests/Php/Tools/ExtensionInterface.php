<?php
namespace Webjump\Tests\Php\Tools;

/**
 * Interface ExtensionInterface
 * @package Webjump\Tests\Php\Tools
 */
interface ExtensionInterface
{
    /**
     * Set extensions for tool to run
     * Example: 'php', 'xml', 'phtml', 'css'
     *
     * @param array $extensions Extensions
     *
     * @return void
     */
    public function setExtensions(array $extensions);
}
