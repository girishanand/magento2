<?php declare(strict_types=1);
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Captcha\Test\Unit\Helper\Adminhtml;

use Magento\Captcha\Helper\Adminhtml\Data;
use Magento\Framework\Filesystem\Directory\Write;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    /**
     * @var \Magento\Captcha\Helper\Adminhtml\Data | |PHPUnit_Framework_MockObject_MockObject
     */
    protected $_model;

    /**
     * setUp
     */
    protected function setUp(): void
    {
        $objectManagerHelper = new ObjectManager($this);
        $className = Data::class;
        $arguments = $objectManagerHelper->getConstructArguments($className);

        $backendConfig = $arguments['backendConfig'];
        $backendConfig->expects(
            $this->any()
        )->method(
            'getValue'
        )->with(
            'admin/captcha/qwe'
        )->will(
            $this->returnValue('1')
        );

        $filesystemMock = $arguments['filesystem'];
        $directoryMock = $this->createMock(Write::class);

        $filesystemMock->expects($this->any())->method('getDirectoryWrite')->will($this->returnValue($directoryMock));
        $directoryMock->expects($this->any())->method('getAbsolutePath')->will($this->returnArgument(0));

        $this->_model = $objectManagerHelper->getObject($className, $arguments);
    }

    public function testGetConfig()
    {
        $this->assertEquals('1', $this->_model->getConfig('qwe'));
    }

    /**
     * @covers \Magento\Captcha\Helper\Adminhtml\Data::_getWebsiteCode
     */
    public function testGetWebsiteId()
    {
        $this->assertStringEndsWith('/admin/', $this->_model->getImgDir());
    }
}
