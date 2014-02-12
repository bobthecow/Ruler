<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class ContainedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', array(2));

        $op = new Operator\Contains($varA, $varB);
        $this->assertInstanceOf('Ruler\Proposition', $op);
    }

    /**
     * @dataProvider containedData
     */
    public function testContained($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\Contained($varA, $varB);
        $this->assertEquals($op->evaluate($context), $result);
    }

    /**
     * @dataProvider containedData
     */
    public function testIsNotContained($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\IsNotContained($varA, $varB);
        $this->assertNotEquals($op->evaluate($context), $result);
    }

    public function containedData()
    {
        return array(
            array(1, array(1), true),
            array(1, array(1, 2, 3), true),
            array(4, array(1, 2, 3), false),
            array('pow', array('foo', 'bar', 'baz'), false),
            array('bar', array('foo', 'bar', 'baz'), true),
            array('bar', null, false),
            array(null, null, false),
            array(array(2), array(1, 2, 3), false),
            array(array('foo'), array(1, 2, array('foo')), true),
            array(array(1), array(1), false),
            array('super', 'supercalifragilistic', true),
            array('fragil', 'supercalifragilistic', true),
            array('a', 'supercalifragilistic', true),
            array('stic', 'supercalifragilistic', true)
        );
    }
}
