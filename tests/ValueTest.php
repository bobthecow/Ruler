<?php

namespace Ruler\Test;

use PHPUnit\Framework\TestCase;
use Ruler\Value;

class ValueTest extends TestCase
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
        return [
            [1, 2,     false, false, true],
            [2, 1,     true, false, false],
            [1, 1,     false, true, false],
            ['a', 'b', false, false, true],
            [
                new \DateTime('-5 days'),
                new \DateTime('+5 days'),
                false, false, true,
            ],
        ];
    }
}
