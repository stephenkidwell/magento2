<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Reports\Block\Adminhtml;

/**
 * Adminhtml products report page content block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Product extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Magento_Reports';
        $this->_controller = 'adminhtml_product';
        $this->_headerText = __('Products Report');
        parent::_construct();
        $this->buttonList->remove('add');
    }
}
