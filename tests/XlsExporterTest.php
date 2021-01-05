<?php

namespace clagiordano\weblibs\dataexport\tests;

use clagiordano\weblibs\dataexport\OutputMethods;
use clagiordano\weblibs\dataexport\XlsExporter;
use \InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use \RuntimeException;

/**
 * Class XlsExporterTest
 * @package clagiordano\weblibs\validator\tests
 */
class XlsExporterTest extends TestCase
{
    /** @var XlsExporter $class */
    private $class = null;
    /** @var string $testFile */
    private $testFile = "/tmp/test_output.xls";

    protected function setUp(): void
    {
        parent::setUp();

        $this->class = new XlsExporter($this->testFile, "save");
        self::assertInstanceOf(
            'clagiordano\weblibs\dataexport\XlsExporter',
            $this->class
        );
    }

    public function testInvalidOutputMethod()
    {
        self::expectException(InvalidArgumentException::class);
        $this->class = new XlsExporter($this->testFile, "INVALID");
    }

    public function testInvalidOutputFile()
    {
        self::expectException(RuntimeException::class);
        $this->class = new XlsExporter("/invalid/path/for/file", "save");

        $status = $this->class->writeFile();
        self::assertFalse($status);
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
        self::assertTrue($status);
    }

    /**
     * @runInSeparateProcess
     * @group download
     *
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
        ob_end_clean();

        /**
         * FIXME: Test that both headers has been sent
        $this->assertContains(
            "Content-type: application/vnd.ms-excel",
            $headers
        );

        $this->assertContains(
            "Content-Disposition: attachment; filename=\""
            . basename($this->testFile) . "\"",
            $headers
        );
         */

        $this->assertTrue($status);

    }

    /**
     * @test
     * @group save
     */
    public function testOutputFile()
    {
        $this->class = new XlsExporter($this->testFile, OutputMethods::OUTPUT_SAVE);
        self::assertInstanceOf(
            XlsExporter::class,
            $this->class
        );

        $this->class->writeNumber(0, 0, 1234);
        $this->class->writeString(1, 0, "test");

        $status = $this->class->writeFile();

        self::assertTrue($status);
    }
}
