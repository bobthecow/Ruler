<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class UnionTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', array(2));

        $op = new Operator\Union($varA, $varB);
        $this->assertInstanceOf('Ruler\\VariableOperand', $op);
    }

    public function testInvalidData()
    {
        $varA    = new Variable('a', "string");
        $varB    = new Variable('b', "blah");
        $context = new Context();

        $op = new Operator\Union($varA, $varB);
        $this->assertEquals(
            $op->prepareValue($context)->getValue(),
            array(
                'string',
                'blah'
            )
        );
    }

    /**
     * @dataProvider unionData
     */
    public function testUnion($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\Union($varA, $varB);
        $this->assertEquals($op->prepareValue($context)->getValue(), $result);
    }

    public function unionData()
    {
        return array(
            array(6, 2, array(6, 2)),
            array(
                'a',
                array('b', 'c'),
                array('a', 'b', 'c'),
            ),
            array(
                array('a', 'b', 'c'),
                array(),
                array('a', 'b', 'c'),
            ),
            array(
                array(),
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
            ),
            array(
                array(),
                array(),
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('d', 'e', 'f'),
                array('a', 'b', 'c', 'd', 'e', 'f'),
            ),
            array(
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
            ),
            array(
                array('a', 'b', 'c'),
                array('b', 'c'),
                array('a', 'b', 'c'),
            ),
            array(
                array('b', 'c'),
                array('b', 'd'),
                array('b', 'c', 'd'),
            ),
        );
    }
}
