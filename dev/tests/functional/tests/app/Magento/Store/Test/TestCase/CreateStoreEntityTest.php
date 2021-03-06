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
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Store\Test\TestCase;

use Mtf\TestCase\Injectable;
use Magento\Backend\Test\Page\Adminhtml\StoreIndex;
use Magento\Backend\Test\Page\Adminhtml\StoreNew;
use Magento\Store\Test\Fixture\Store;
use Magento\Store\Test\Fixture\StoreGroup;

/**
 * Test Creation for CreateStoreEntity (Store Management)
 *
 * Preconditions:
 * 1. Create Store Group
 *
 * Test Flow:
 * 1. Open Backend
 * 2. Go to Stores -> All Stores
 * 3. Click "Create Store View" button
 * 4. Fill data according to dataset
 * 5. Perform all assertions
 *
 * @group Store_Management_(PS)
 * @ZephyrId MAGETWO-27647
 */
class CreateStoreEntityTest extends Injectable
{
    /**
     * Page StoreIndex
     *
     * @var StoreIndex
     */
    protected $storeIndex;

    /**
     * Page StoreNew
     *
     * @var StoreNew
     */
    protected $storeNew;

    /**
     * Preparing pages for test
     *
     * @param StoreIndex $storeIndex
     * @param StoreNew $storeNew
     * @return void
     */
    public function __inject(StoreIndex $storeIndex, StoreNew $storeNew)
    {
        $this->storeIndex = $storeIndex;
        $this->storeNew = $storeNew;
    }

    /**
     * Runs Test Creation for StoreEntityTest
     *
     * @param Store $store
     * @return void
     */
    public function test(Store $store)
    {
        //Steps:
        $this->storeIndex->open();
        $this->storeIndex->getGridPageActions()->addStoreView();
        $this->storeNew->getStoreForm()->fill($store);
        $this->storeNew->getFormPageActions()->save();
    }
}
