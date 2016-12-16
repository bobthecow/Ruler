<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class SymmetricDifferenceTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', array(2));

        $op = new Operator\SymmetricDifference($varA, $varB);
        $this->assertInstanceOf('Ruler\\VariableOperand', $op);
    }

    public function testInvalidData()
    {
        $varA    = new Variable('a', "string");
        $varB    = new Variable('b', "blah");
        $context = new Context();

        $op = new Operator\SymmetricDifference($varA, $varB);
        $this->assertEquals(
            array('string', 'blah'),
            $op->prepareValue($context)->getValue()
        );
    }

    /**
     * @dataProvider symmetricDifferenceData
     */
    public function testSymmetricDifference($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\SymmetricDifference($varA, $varB);
        $this->assertEquals(
            $result,
            $op->prepareValue($context)->getValue()
        );
    }

    public function symmetricDifferenceData()
    {
        return array(
            array(6, 2, array(6, 2)),
            array(
                array('a', 'b', 'c'),
                'a',
                array('b', 'c'),
            ),
            array(
                'a',
                array('a', 'b', 'c'),
                array('b', 'c'),
            ),
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
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('b', 'c'),
                array('a'),
            ),
            array(
                array('b', 'c'),
                array('b', 'd'),
                array('c', 'd'),
            ),
        );
    }
}
