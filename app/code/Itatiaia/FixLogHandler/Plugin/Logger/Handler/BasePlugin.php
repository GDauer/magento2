<?php
namespace Itatiaia\FixLogHandler\Plugin\Logger\Handler;

class BasePlugin
{
    public function afterSanitizeFileName(\Magento\Framework\Logger\Handler\Base $subject, $result)
    {
        return preg_replace("#pub\/#is", "", $result);
    }
}
