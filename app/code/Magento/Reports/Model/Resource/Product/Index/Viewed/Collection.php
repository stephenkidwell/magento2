<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */

/**
 * Reports Viewed Product Index Resource Collection
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\Reports\Model\Resource\Product\Index\Viewed;

class Collection extends \Magento\Reports\Model\Resource\Product\Index\Collection\AbstractCollection
{
    /**
     * Retrieve Product Index table name
     *
     * @return string
     */
    protected function _getTableName()
    {
        return $this->getTable('report_viewed_product_index');
    }
}
