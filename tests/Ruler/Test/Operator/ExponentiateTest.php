<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class ExponentiateTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', array(2));

        $op = new Operator\Exponentiate($varA, $varB);
        $this->assertInstanceOf('Ruler\\VariableOperand', $op);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Arithmetic: values must be numeric
     */
    public function testInvalidData()
    {
        $varA    = new Variable('a', "string");
        $varB    = new Variable('b', "blah");
        $context = new Context();

        $op = new Operator\Exponentiate($varA, $varB);
        $op->prepareValue($context);
    }

    /**
     * @dataProvider exponentiateData
     */
    public function testExponentiate($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\Exponentiate($varA, $varB);
        $this->assertEquals($op->prepareValue($context)->getValue(), $result);
    }

    public function exponentiateData()
    {
        return array(
            array(6, 2, 36),
            array(10, -1, 0.1),
        );
    }
}
