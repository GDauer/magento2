<?php
/**
 * System config image field backend model
 */
namespace Treinamento\AdminPageExample\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\File;

/**
 * @api
 * @since 100.0.2
 */
class Pdf extends File
{
    /**
     * Getter for allowed extensions of uploaded files
     *
     * @return string[]
     */
    protected function _getAllowedExtensions()
    {
        return ['pdf'];
    }
}
