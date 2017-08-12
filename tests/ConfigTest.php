<?php

namespace Maduser\Minimal\Config\Tests;

use Maduser\Minimal\Config\Config;
use Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testContructor()
    {
        $config = new Config();
    }

    public function setItems()
    {
        $expected = new Config();

        $config = new Config();
        $result = $config->setItems(['key1' => 'value1', 'key2' => 'value2']);

        $this->assertEquals($expected, $result);
    }

    public function testGetItems()
    {
        $expected = ['key1' => 'value1', 'key2' => 'value2'];

        $config = new Config();
        $config->setItems(['key1' => 'value1', 'key2' => 'value2']);
        $result = $config->getItems();

        $this->assertEquals($expected, $result);
    }

    public function testIsLiteral()
    {
        $config = new Config();

        $this->assertEquals(null, $config->isLiteral());
    }

    public function testSetLiteral()
    {
        $config = new Config();
        $config->setLiteral(true);
        $this->assertTrue($config->isLiteral());
        $config->setLiteral(false);
        $this->assertFalse($config->isLiteral());
    }

    public function testExists()
    {
        $expected = 'value2';

        $config = new Config();
        $config->setItems(['key1' => 'value1', 'key2' => 'value2']);
        $result = $config->exists('key2');

        $this->assertEquals($expected, $result);
    }

    public function testExistsWithElse()
    {
        $expected = 'value3';

        $config = new Config();
        $config->setItems(['key1' => 'value1', 'key2' => 'value2']);
        $result = $config->exists('key3', 'value3');

        $this->assertEquals($expected, $result);
    }

    public function testItem()
    {
        $expected = 'value1';

        $config = new Config();
        $config->item('key1', 'value1');
        $result = $config->item('key1');

        $this->assertEquals($expected, $result);
    }

    public function testItemIsNull()
    {
        $config = new Config();
        $config->item('key1', 'value1');
        $result = $config->item('key2');

        $this->assertNull($result);
    }

    /**
     * @expectedException \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    public function testItemThrowsKeyDoesNotExist()
    {
        $config = new Config();
        $config->item('key1', 'value1');
        $result = $config->item('key2', null, true);
    }

    public function testMerge()
    {
        $expected = [
            'key1' => [
                'sub1' => 'value1',
                'sub2' => 'value2',
                'sub3' => 'value3'
            ],
            'key2' => [
                'sub1' => 'value1',
                'sub2' => 'value2',
                'sub3' => 'value3'
            ]
        ];

        $config = new Config();
        $config->setItems([
            'key1' => [
                'sub1' => 'value1',
                'sub2' => '2',
                'sub3' => 'value3'
            ],
            'key2' => [
                'sub1' => 'value1',
                'sub3' => 'value2'
            ]
        ]);

        $config->merge('key1', [
            'sub2' => 'value2'
        ]);

        $config->merge('key2', [
            'sub2' => 'value2',
            'sub3' => 'value3'
        ]);

        $result = $config->getItems();

        $this->assertEquals($expected, $result);
    }

    public function testInit()
    {
        // TODO: test file system function
    }

    public function testFile()
    {
        // TODO: test file system function
    }

    public function testFind()
    {
        $expected = 'value5';

        $config = new Config();
        $config->setItems([
            'key1' => [
                'sub1' => 'value1',
                'sub2' => 'value2',
                'sub3' => 'value3'
            ],
            'key2' => [
                'sub1' => 'value4',
                'sub2' => 'value5',
                'sub3' => 'value6'
            ]
        ]);

        $result = $config->find('key2.sub2', $config->getItems());

        $this->assertEquals($expected, $result);
    }

    /**
     * @expectedException \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    public function testFindWithThrow()
    {
        $expected = 'value5';

        $config = new Config();
        $config->setItems([
            'key1' => [
                'sub1' => 'value1',
                'sub2' => 'value2',
                'sub3' => 'value3'
            ],
            'key2' => [
                'sub1' => 'value4',
                'sub2' => 'value5',
                'sub3' => 'value6'
            ]
        ]);

        $result = $config->find('key3', $config->getItems(), true);

        $this->assertEquals($expected, $result);
    }

    public function testFindReturnsNull()
    {
        $expected = 'value5';

        $config = new Config();
        $config->setItems([
            'key1' => [
                'sub1' => 'value1',
                'sub2' => 'value2',
                'sub3' => 'value3'
            ],
            'key2' => [
                'sub1' => 'value4',
                'sub2' => 'value5',
                'sub3' => 'value6'
            ]
        ]);

        $result = $config->find('key3', $config->getItems());

        $this->assertNull($result);
    }

    /**
     * @expectedException \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    public function testThrowKeyDoesNotExist()
    {
        $config = new Config();
        $config->throwKeyDoesNotExist('key1');
    }
}
