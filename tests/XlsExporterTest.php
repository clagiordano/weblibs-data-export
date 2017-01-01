<?php

namespace clagiordano\weblibs\dataexport\tests;

use clagiordano\weblibs\dataexport\XlsExporter;

/**
 * Class XlsExporterTest
 * @package clagiordano\weblibs\validator\tests
 */
class XlsExporterTest extends \PHPUnit_Framework_TestCase
{
    /** @var XlsExporter $class */
    private $class = null;
    /** @var string $testFile */
    private $testFile = "/tmp/test_output.xls";

    public function setUp()
    {
        $this->class = new XlsExporter($this->testFile, "save");
        $this->assertInstanceOf(
            'clagiordano\weblibs\dataexport\XlsExporter',
            $this->class
        );
    }

    public function testInvalidOutputMethod()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->class = new XlsExporter($this->testFile, "INVALID");
    }

    public function testInvalidOutputFile()
    {
        $this->setExpectedException('\RuntimeException');
        $this->class = new XlsExporter("/invalid/path/for/file", "save");

        $status = $this->class->writeFile();
        $this->assertFalse($status);
    }

    public function testWriteNumber()
    {
        $this->class->writeNumber(0, 0, 1234567890);
    }

    public function testWriteString()
    {
        $this->class->writeString(1, 0, "test string");
    }

    public function testWriteFile()
    {
        $this->class->writeNumber(0, 0, 1234567890);
        $this->class->writeString(1, 0, "test string");

        $status = $this->class->writeFile();
        $this->assertTrue($status);
    }

    /**
     * @runInSeparateProcess
     * @requires extension xdebug
     */
    public function testDownloadFile()
    {
        $this->class = new XlsExporter($this->testFile, "download");
        $this->assertInstanceOf(
            'clagiordano\weblibs\dataexport\XlsExporter',
            $this->class
        );

        $this->class->writeNumber(0, 0, 1234);
        $this->class->writeString(1, 0, "test");

        ob_start();
        $status = $this->class->writeFile();
        $headers = xdebug_get_headers();

        $this->assertContains(
            "Content-type: application/vnd.ms-excel",
            $headers
        );

        $this->assertContains(
            "Content-Disposition: attachment; filename=\""
                . basename($this->testFile) . "\"",
            $headers
        );

        
        $this->assertTrue($status);
    }
}
