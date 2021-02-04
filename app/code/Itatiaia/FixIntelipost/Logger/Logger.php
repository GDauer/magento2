<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

namespace Itatiaia\FixIntelipost\Logger;
/**
 * Intelipost custom logger allows name changing to differentiate log call origin
 * Class Logger
 *
 * @package Itatiaia\FixIntelipost\Logger
 */
class Logger
    extends \Monolog\Logger
{

    /**
     * Set logger name
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}