<?php


namespace DigitalHub\Minification\Plugin;

use Magento\Framework\View\Asset\Minification;

class ExcludeFilesFromMinification
{
    /**
     * @param Minification $subject
     * @param array $result
     * @param $contentType
     * @return array
     */
    public function afterGetExcludes(Minification $subject, array $result, $contentType)
    {
        if ($contentType == 'js') {
            $result[] = 'DigitalHub_FeedManager/js/codemirror/mode/q/q.js';
        }
        return $result;
    }
}