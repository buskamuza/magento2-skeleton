<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @spi
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Catalog\Test\Block\Adminhtml\Product\Edit\Tab;

use Mtf\Client\Element;
use Magento\Backend\Test\Block\Widget\Tab;
use Mtf\Factory\Factory;

/**
 * Class Upsell
 * Upsell Tab
 */
class Upsell extends Tab
{
    const GROUP = 'upsells';

    /**
     * Select up-sell products
     *
     * @param array $products
     * @param Element|null $context
     * @return $this
     */
    public function fillFormTab(array $products, Element $context = null)
    {
        if (!isset($products['upsell_products'])) {
            return $this;
        }
        $element = $context ? : $this->_rootElement;
        $upSellBlock = Factory::getBlockFactory()->getMagentoCatalogAdminhtmlProductEditTabUpsellGrid(
            $element->find('#up_sell_product_grid')
        );
        foreach ($products['upsell_products']['value'] as $product) {
            $upSellBlock->searchAndSelect($product);
        }

        return $this;
    }
}
