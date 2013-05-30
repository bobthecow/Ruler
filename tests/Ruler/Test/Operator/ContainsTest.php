<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class ContainsTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', array(2));

        $op = new Operator\Contains($varA, $varB);
        $this->assertInstanceOf('Ruler\Proposition', $op);
        $this->assertInstanceOf('Ruler\Operator\ComparisonOperator', $op);
    }

    /**
     * @dataProvider containsData
     */
    public function testContains($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\Contains($varA, $varB);
        $this->assertEquals($op->evaluate($context), $result);
    }

    /**
     * @dataProvider containsData
     */
    public function testDoesNotContain($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\DoesNotContain($varA, $varB);
        $this->assertNotEquals($op->evaluate($context), $result);
    }

    public function containsData()
    {
        return array(
            array(array(1), 1, true),
            array(array(1, 2, 3), 1, true),
            array(array(1, 2, 3), 4, false),
            array(array('foo', 'bar', 'baz'), 'pow', false),
            array(array('foo', 'bar', 'baz'), 'bar', true),
            array(NULL, 'bar', false),
            array(NULL, NULL, false),
            array(array(1, 2, 3), array(2), false),
            array(array(1, 2, array('foo')), array('foo'), true),
            array(array(1), array(1), false),
            array('super', 'supercalifragilistic', true),
            array('fragil', 'supercalifragilistic', true),
            array('a', 'supercalifragilistic', true),
            array('stic', 'supercalifragilistic', true)
        );
    }
}
