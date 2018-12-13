<?php
namespace QSCMF\Tests\Helpers;

use QSCMF\Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_is_value_empty()
    {
        $this->assertFalse(is_value_empty(123));
        $this->assertFalse(is_value_empty('123'));
        $this->assertFalse(is_value_empty([1,2,3]));
        $this->assertTrue(is_value_empty(null));
        $this->assertFalse(is_value_empty('null'));
        $this->assertTrue(is_value_empty(''));
        $this->assertTrue(is_value_empty([]));
        $this->assertFalse(is_value_empty('0'));
        $this->assertFalse(is_value_empty(0));
    }

    public function test_normalize_path()
    {
        $this->assertEquals('test', normalize_path(base_path() . DIRECTORY_SEPARATOR . 'test'));
        $this->assertEquals('', normalize_path(base_path()));
        $this->assertEquals('', normalize_path(base_path() . DIRECTORY_SEPARATOR));
        $this->assertEquals('test', normalize_path(base_path() . '/\test'));
        $this->assertEquals('test', normalize_path('test'));
    }
}
