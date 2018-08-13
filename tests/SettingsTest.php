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
}
