<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class IntersectTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', array(2));

        $op = new Operator\Intersect($varA, $varB);
        $this->assertInstanceOf('Ruler\\VariableOperand', $op);
    }

    public function testInvalidData()
    {
        $varA    = new Variable('a', "string");
        $varB    = new Variable('b', "blah");
        $context = new Context();

        $op = new Operator\Intersect($varA, $varB);
        $this->assertEquals(
            array(),
            $op->prepareValue($context)->getValue()
        );
    }

    /**
     * @dataProvider intersectData
     */
    public function testIntersect($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\Intersect($varA, $varB);
        $this->assertEquals(
            $result,
            $op->prepareValue($context)->getValue()
        );
    }

    public function intersectData()
    {
        return array(
            array(6, 2, array()),
            array(
                array('a', 'c'),
                'a',
                array('a'),
            ),
            array(
                array('a', 'b', 'c'),
                array(),
                array(),
            ),
            array(
                array(),
                array('a', 'b', 'c'),
                array(),
            ),
            array(
                array(),
                array(),
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('d', 'e', 'f'),
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
            ),
            array(
                array('a', 'b', 'c'),
                array('b', 'c'),
                array('b', 'c'),
            ),
            array(
                array('b', 'c'),
                array('b', 'd'),
                array('b'),
            ),
        );
    }
}
