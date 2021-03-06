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

namespace Magento\Framework\App;

class MaintenanceModeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MaintenanceMode
     */
    protected $model;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface  | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $flagDir ;

    protected function setUp()
    {
        $this->flagDir = $this->getMockForAbstractClass('\Magento\Framework\Filesystem\Directory\WriteInterface');
        $filesystem = $this->getMock('Magento\Framework\App\Filesystem', [], [], '', false);
        $filesystem->expects($this->any())
            ->method('getDirectoryWrite')
            ->will($this->returnValue($this->flagDir));

        $this->model = new MaintenanceMode($filesystem);
    }

    public function testIsOnInitial()
    {
        $this->flagDir->expects($this->once())->method('isExist')
            ->with(MaintenanceMode::FLAG_FILENAME)
            ->will($this->returnValue(false));
        $this->assertFalse($this->model->isOn());
    }

    public function testSetMaintenanceModeOn()
    {
        $this->flagDir->expects($this->at(0))->method('isExist')->with(MaintenanceMode::FLAG_FILENAME)
            ->will($this->returnValue(false));
        $this->flagDir->expects($this->at(1))->method('touch')->will($this->returnValue(true));
        $this->flagDir->expects($this->at(2))->method('isExist')->with(MaintenanceMode::FLAG_FILENAME)
            ->will($this->returnValue(true));
        $this->flagDir->expects($this->at(3))->method('isExist')->with(MaintenanceMode::IP_FILENAME)
            ->will($this->returnValue(false));


        $this->assertFalse($this->model->isOn());
        $this->assertTrue($this->model->set(true));
        $this->assertTrue($this->model->isOn());
    }

    public function testSetMaintenanceModeOff()
    {
        $this->flagDir->expects($this->at(0))->method('isExist')->with(MaintenanceMode::FLAG_FILENAME)
            ->will($this->returnValue(true));
        $this->flagDir->expects($this->at(1))->method('delete')->with(MaintenanceMode::FLAG_FILENAME)
            ->will($this->returnValue(false));
        $this->flagDir->expects($this->at(2))->method('isExist')->with(MaintenanceMode::FLAG_FILENAME)
            ->will($this->returnValue(false));

        $this->assertFalse($this->model->set(false));
        $this->assertFalse($this->model->isOn());
    }

    public function testSetAddresses()
    {
        $mapIsExist = [
            [MaintenanceMode::FLAG_FILENAME, true],
            [MaintenanceMode::IP_FILENAME, true]
        ];
        $this->flagDir->expects($this->any())->method('isExist')->will($this->returnValueMap($mapIsExist));
        $this->flagDir->expects($this->any())->method('writeFile')
            ->with(MaintenanceMode::IP_FILENAME)
            ->will($this->returnValue(true));

        $this->flagDir->expects($this->any())->method('readFile')
            ->with(MaintenanceMode::IP_FILENAME)
            ->will($this->returnValue(''));

        $this->model->setAddresses('');
        $this->assertEquals([''], $this->model->getAddressInfo());
    }

    public function testSetSingleAddresses()
    {
        $mapIsExist = [
            [MaintenanceMode::FLAG_FILENAME, true],
            [MaintenanceMode::IP_FILENAME, true]
        ];
        $this->flagDir->expects($this->any())->method('isExist')->will($this->returnValueMap($mapIsExist));
        $this->flagDir->expects($this->any())->method('delete')->will($this->returnValueMap($mapIsExist));

        $this->flagDir->expects($this->any())->method('writeFile')
            ->will($this->returnValue(10));

        $this->flagDir->expects($this->any())->method('readFile')
            ->with(MaintenanceMode::IP_FILENAME)
            ->will($this->returnValue('address1'));

        $this->model->setAddresses('address1');
        $this->assertEquals(['address1'], $this->model->getAddressInfo());
    }

    public function testOnSetMultipleAddresses()
    {
        $mapIsExist = [
            [MaintenanceMode::FLAG_FILENAME, true],
            [MaintenanceMode::IP_FILENAME, true]
        ];
        $this->flagDir->expects($this->any())->method('isExist')->will($this->returnValueMap($mapIsExist));
        $this->flagDir->expects($this->any())->method('delete')->will($this->returnValueMap($mapIsExist));

        $this->flagDir->expects($this->any())->method('writeFile')
            ->will($this->returnValue(10));

        $this->flagDir->expects($this->any())->method('readFile')
            ->with(MaintenanceMode::IP_FILENAME)
            ->will($this->returnValue('address1,10.50.60.123'));

        $expectedArray = ['address1', '10.50.60.123'];
        $this->model->setAddresses('address1,10.50.60.123');
        $this->assertEquals($expectedArray, $this->model->getAddressInfo());
        $this->assertFalse($this->model->isOn('address1'));
        $this->assertTrue($this->model->isOn('address3'));
    }

    public function testOffSetMultipleAddresses()
    {
        $mapIsExist = [
            [MaintenanceMode::FLAG_FILENAME, false],
            [MaintenanceMode::IP_FILENAME, true]
        ];
        $this->flagDir->expects($this->any())->method('isExist')->will($this->returnValueMap($mapIsExist));
        $this->flagDir->expects($this->any())->method('delete')->will($this->returnValueMap($mapIsExist));

        $this->flagDir->expects($this->any())->method('readFile')
            ->with(MaintenanceMode::IP_FILENAME)
            ->will($this->returnValue('address1,10.50.60.123'));

        $expectedArray = ['address1', '10.50.60.123'];
        $this->model->setAddresses('address1,10.50.60.123');
        $this->assertEquals($expectedArray, $this->model->getAddressInfo());
        $this->assertFalse($this->model->isOn('address1'));
        $this->assertFalse($this->model->isOn('address3'));
    }
}
