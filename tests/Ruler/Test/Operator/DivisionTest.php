<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class DivisionTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', array(2));

        $op = new Operator\Division($varA, $varB);
        $this->assertInstanceOf('Ruler\Proposition', $op);
        $this->assertInstanceOf('Ruler\Operator\ArithmeticOperator', $op);
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

        $op = new Operator\Division($varA, $varB);
        $op->evaluate($context);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Division by zero
     */
    public function testDivideByZero()
    {
        $varA    = new Variable('a', rand(1,100));
        $varB    = new Variable('b', 0);
        $context = new Context();

        $op = new Operator\Division($varA, $varB);
        $op->evaluate($context);
    }

    /**
     * @dataProvider divisionData
     */
    public function testDivision($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\Division($varA, $varB);
        $this->assertEquals($op->evaluate($context), $result);
    }

    public function divisionData()
    {
        return array(
            array(6, 2, 3),
            array(7.5, 2.5, 3.0),
        );
    }
}
