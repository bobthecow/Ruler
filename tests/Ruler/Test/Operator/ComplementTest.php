<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;
use PHPUnit\Framework\TestCase;

class ComplementTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', array(2));

        $op = new Operator\Complement($varA, $varB);
        $this->assertInstanceOf('Ruler\\VariableOperand', $op);
    }

    public function testInvalidData()
    {
        $varA    = new Variable('a', "string");
        $varB    = new Variable('b', "blah");
        $context = new Context();

        $op = new Operator\Complement($varA, $varB);
        $this->assertEquals(
            array('string'),
            $op->prepareValue($context)->getValue()
        );
    }

    /**
     * @dataProvider complementData
     */
    public function testComplement($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\Complement($varA, $varB);
        $this->assertEquals(
            $result,
            $op->prepareValue($context)->getValue()
        );
    }

    public function complementData()
    {
        return array(
            array(6, 2, array(6)),
            array(
                array('a', 'b', 'c'),
                'a',
                array('b', 'c'),
            ),
            array(
                'a',
                array('a', 'b', 'c'),
                array(),
            ),
            array(
                'a',
                array('b', 'c'),
                array('a'),
            ),
            array(
                array('a', 'b', 'c'),
                array(),
                array('a', 'b', 'c'),
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
                array('a', 'b', 'c'),
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
                array('c'),
            ),
        );
    }
}
