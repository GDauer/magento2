<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Report Reviews collection
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Itatiaia\CustomReports\Model\ResourceModel\Report\GroupedCollection;

/**
 * @api
 * @since 100.0.2
 */
class Initial extends \Magento\Reports\Model\ResourceModel\Report\Collection
{
    /**
     * Report sub-collection class name
     *
     * @var string
     */
    protected $_reportCollection = \Itatiaia\CustomReports\Model\ResourceModel\Report\GroupedCollection::class;
}
