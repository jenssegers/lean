<?php

use PHPUnit\Framework\TestCase;
use Slim\Settings;

class SettingsTest extends TestCase
{
    public function testGetDotNotation()
    {
        $settings = new Settings([
            'foo' => [
                'bar' => 'baz',
            ],
        ]);

        $this->assertEquals('baz', $settings->get('foo.bar'));
        $this->assertEquals(null, $settings->get('bar'));
    }

    public function testSetDotNotation()
    {
        $settings = new Settings();
        $settings->set('foo.bar', 'baz');

        $this->assertEquals('baz', $settings->get('foo.bar'));
    }

    public function testGetWithDefaultValue()
    {
        $settings = new Settings();

        $this->assertEquals('baz', $settings->get('foo.bar', 'baz'));
    }

    public function testHasDotNotation()
    {
        $settings = new Settings([
            'foo' => [
                'bar' => 'baz',
            ],
        ]);

        $this->assertTrue($settings->has('foo.bar'));
        $this->assertFalse($settings->has('bar'));
    }
}
