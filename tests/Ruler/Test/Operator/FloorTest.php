<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class FloorTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);

        $op = new Operator\Floor($varA);
        $this->assertInstanceOf('Ruler\\VariableOperand', $op);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Arithmetic: values must be numeric
     */
    public function testInvalidData()
    {
        $varA    = new Variable('a', "string");
        $context = new Context();

        $op = new Operator\Floor($varA);
        $op->prepareValue($context);
    }

    /**
     * @dataProvider ceilingData
     */
    public function testCeiling($a, $result)
    {
        $varA    = new Variable('a', $a);
        $context = new Context();

        $op = new Operator\Floor($varA);
        $this->assertEquals($op->prepareValue($context)->getValue(), $result);
    }

    public function ceilingData()
    {
        return array(
            array(1.2, 1),
            array(1.0, 1),
            array(1, 1),
            array(-0.5, -1),
            array(-1.5, -2),
        );
    }
}
