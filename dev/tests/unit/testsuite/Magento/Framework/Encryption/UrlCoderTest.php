<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Framework\Encryption;

class UrlCoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\Encryption\UrlCoder
     */
    protected $_urlCoder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_urlMock;

    /**
     * @var string
     */
    protected $_url = 'http://example.com';

    /**
     * @var string
     */
    protected $_encodeUrl = 'aHR0cDovL2V4YW1wbGUuY29t';

    protected function setUp()
    {
        $this->_urlMock = $this->getMock('Magento\Framework\UrlInterface', [], [], '', false);
        $this->_urlCoder = new \Magento\Framework\Encryption\UrlCoder($this->_urlMock);
    }

    public function testDecode()
    {
        $this->_urlMock->expects(
            $this->once()
        )->method(
            'sessionUrlVar'
        )->with(
            $this->_url
        )->will(
            $this->returnValue('expected')
        );
        $this->assertEquals('expected', $this->_urlCoder->decode($this->_encodeUrl));
    }

    public function testEncode()
    {
        $this->assertEquals($this->_encodeUrl, $this->_urlCoder->encode($this->_url));
    }
}
