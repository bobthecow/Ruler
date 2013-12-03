<?php

namespace Ruler\Test;

use Ruler\Value;

class ValueTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $valueString = 'technologic';
        $value = new Value($valueString);
        $this->assertEquals($valueString, $value->getValue());
    }

    /**
     * @dataProvider getRelativeValues
     */
    public function testGreaterThanEqualToAndLessThan($a, $b, $gt, $eq, $lt)
    {
        $valA = new Value($a);
        $valB = new Value($b);

        $this->assertEquals($gt, $valA->greaterThan($valB));
        $this->assertEquals($lt, $valA->lessThan($valB));
        $this->assertEquals($eq, $valA->equalTo($valB));
    }

    public function getRelativeValues()
    {
        return array(
            array(1, 2,     false, false, true),
            array(2, 1,     true, false, false),
            array(1, 1,     false, true, false),
            array('a', 'b', false, false, true),
            array(
                new \DateTime('-5 days'),
                new \DateTime('+5 days'),
                false, false, true
            ),
        );
    }
}
