<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Tools\I18n\Pack;

/**
 * Generator test
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Tools\I18n\Dictionary\Loader\FileInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dictionaryLoaderMock;

    /**
     * @var \Magento\Tools\I18n\Pack\WriterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $packWriterMock;

    /**
     * @var \Magento\Tools\I18n\Factory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $factoryMock;

    /**
     * @var \Magento\Tools\I18n\Dictionary|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dictionaryMock;

    /**
     * @var \Magento\Tools\I18n\Pack\Generator
     */
    protected $_generator;

    protected function setUp()
    {
        $this->dictionaryLoaderMock = $this->getMock('Magento\Tools\I18n\Dictionary\Loader\FileInterface');
        $this->packWriterMock = $this->getMock('Magento\Tools\I18n\Pack\WriterInterface');
        $this->factoryMock = $this->getMock('Magento\Tools\I18n\Factory', [], [], '', false);
        $this->dictionaryMock = $this->getMock('Magento\Tools\I18n\Dictionary', [], [], '', false);

        $objectManagerHelper = new \Magento\TestFramework\Helper\ObjectManager($this);
        $this->_generator = $objectManagerHelper->getObject(
            'Magento\Tools\I18n\Pack\Generator',
            [
                'dictionaryLoader' => $this->dictionaryLoaderMock,
                'packWriter' => $this->packWriterMock,
                'factory' => $this->factoryMock
            ]
        );
    }

    public function testGenerate()
    {
        $dictionaryPath = 'dictionary_path';
        $packPath = 'pack_path';
        $localeString = 'locale';
        $mode = 'mode';
        $allowDuplicates = true;
        $localeMock = $this->getMock('Magento\Tools\I18n\Locale', [], [], '', false);

        $phrases = [$this->getMock('Magento\Tools\I18n\Dictionary\Phrase', [], [], '', false)];
        $this->dictionaryMock->expects($this->once())
            ->method('getPhrases')
            ->will($this->returnValue([$phrases]));

        $this->factoryMock->expects($this->once())
            ->method('createLocale')
            ->with($localeString)
            ->will($this->returnValue($localeMock));
        $this->dictionaryLoaderMock->expects($this->once())
            ->method('load')
            ->with($dictionaryPath)
            ->will($this->returnValue($this->dictionaryMock));
        $this->packWriterMock->expects($this->once())
            ->method('write')
            ->with($this->dictionaryMock, $packPath, $localeMock, $mode);

        $this->_generator->generate($dictionaryPath, $packPath, $localeString, $mode, $allowDuplicates);
    }

    /**
     * @expectedExceptionMessage No phrases have been found by the specified path.
     * @expectedException \UnexpectedValueException
     */
    public function testGenerateEmptyFile()
    {
        $dictionaryPath = 'dictionary_path';
        $packPath = 'pack_path';
        $localeString = 'locale';
        $mode = 'mode';
        $allowDuplicates = true;
        $localeMock = $this->getMock('Magento\Tools\I18n\Locale', [], [], '', false);

        $this->factoryMock->expects($this->once())
            ->method('createLocale')
            ->with($localeString)
            ->will($this->returnValue($localeMock));
        $this->dictionaryLoaderMock->expects($this->once())
            ->method('load')
            ->with($dictionaryPath)
            ->will($this->returnValue($this->dictionaryMock));

        $this->_generator->generate($dictionaryPath, $packPath, $localeString, $mode, $allowDuplicates);
    }

    public function testGenerateWithNotAllowedDuplicatesAndDuplicatesExist()
    {
        $error = "Duplicated translation is found, but it is not allowed.\n"
            . "The phrase \"phrase1\" is translated differently in 1 places.\n"
            . "The phrase \"phrase2\" is translated differently in 1 places.\n";
        $this->setExpectedException('\RuntimeException', $error);

        $allowDuplicates = false;

        $phraseFirstMock = $this->getMock('Magento\Tools\I18n\Dictionary\Phrase', [], [], '', false);
        $phraseFirstMock->expects($this->once())->method('getPhrase')->will($this->returnValue('phrase1'));
        $phraseSecondMock = $this->getMock('Magento\Tools\I18n\Dictionary\Phrase', [], [], '', false);
        $phraseSecondMock->expects($this->once())->method('getPhrase')->will($this->returnValue('phrase2'));

        $this->dictionaryLoaderMock->expects($this->any())
            ->method('load')
            ->will($this->returnValue($this->dictionaryMock));
        $phrases = [$this->getMock('Magento\Tools\I18n\Dictionary\Phrase', [], [], '', false)];
        $this->dictionaryMock->expects($this->once())
            ->method('getPhrases')
            ->will($this->returnValue([$phrases]));
        $this->dictionaryMock->expects($this->once())
            ->method('getDuplicates')
            ->will($this->returnValue([[$phraseFirstMock], [$phraseSecondMock]]));

        $this->_generator->generate('dictionary_path', 'pack_path', 'locale', 'mode', $allowDuplicates);
    }
}
