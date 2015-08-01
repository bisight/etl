<?php

namespace BiSight\Etl\Test;

use BiSight\Etl\Column;

class ColumnTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testUnserialize
     */
    public function testUnserializeDataProvider()
    {
        return array(
            array(
                'test:string(64)',
                'test',
                Column::createNew()
                    ->setName('test')
                    ->setType('string')
                    ->setLength(64)
            ), array(
                'test/test_alias:decimal(10;2)',
                'test_alias',
                Column::createNew()
                    ->setName('test')
                    ->setAlias('test_alias')
                    ->setType('decimal')
                    ->setLength(10)
                    ->setPrecision(2)
            ), array(
                'test',
                'test',
                Column::createNew()
                    ->setName('test')
                    ->setType('text') # Text type by default to store any data
            ),
        );
    }

    /**
     * @dataProvider testUnserializeDataProvider
     */
    public function testUnserialize($serializedColumn, $expectedAlias, $expectedColumn)
    {
        $this->assertEquals($expectedColumn, Column::unserialize($serializedColumn));
        $this->assertEquals(array($expectedAlias=>$expectedColumn), Column::unserializeArray($serializedColumn));
    }

    /**
     * Data provider for testUnserializeWithInvalidInput
     */
    public function testUnserializeWithInvalidInputDataProvider()
    {
        return array(
            array('bad_delimiter|string(32)'),
            array('no_type/alias(32)'),
            array('no_type(32)'),
        );
    }

    /**
     * @dataProvider testUnserializeWithInvalidInputDataProvider
     * @expectedException InvalidArgumentException
     */
    public function testUnserializeWithInvalidInput($invalidSerializedColumn)
    {
        Column::unserialize($invalidSerializedColumn);
    }
}
