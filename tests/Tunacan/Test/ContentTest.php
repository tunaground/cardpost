<?php
namespace Tunacan\Test;

use PHPUnit\Framework\TestCase;
use Tunacan\Bundle\Component\Content;

class ContentTest extends TestCase
{
    public function testConvertNewLineToBreak()
    {
        $content = new Content("test\ntest");
        $content->applyBreak();
        $expect = "test<br />test";
        $this->assertEquals($expect, $content->__toString());
    }
}
