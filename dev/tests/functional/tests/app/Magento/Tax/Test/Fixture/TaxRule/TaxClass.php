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

namespace Magento\Tax\Test\Fixture\TaxRule;

use Mtf\Fixture\FixtureFactory;
use Mtf\Fixture\FixtureInterface;

/**
 * Class TaxClass
 *
 * Data keys:
 *  - dataSet
 */
class TaxClass implements FixtureInterface
{
    /**
     * Array with tax class names
     *
     * @var array
     */
    protected $data;

    /**
     * Array with tax class fixtures
     *
     * @var array
     */
    protected $fixture;

    /**
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data = [])
    {
        $this->params = $params;
        if (isset($data['dataSet'])) {
            $dataSets = $data['dataSet'];
            foreach ($dataSets as $dataSet) {
                if ($dataSet !== '-') {
                    /** @var \Magento\Tax\Test\Fixture\TaxClass $taxClass */
                    $taxClass = $fixtureFactory->createByCode('taxClass', ['dataSet' => $dataSet]);
                    $this->fixture[] = $taxClass;
                    $this->data[] = $taxClass->getClassName();
                }
            }
        }
    }

    /**
     * Persist custom selections tax classes
     *
     * @return void
     */
    public function persist()
    {
        //
    }

    /**
     * Return prepared data set
     *
     * @param $key [optional]
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getData($key = null)
    {
        return $this->data;
    }

    /**
     * Return data set configuration settings
     *
     * @return string
     */
    public function getDataConfig()
    {
        return $this->params;
    }

    /**
     * Return tax class fixture
     *
     * @return array
     */
    public function getFixture()
    {
        return $this->fixture;
    }
}
