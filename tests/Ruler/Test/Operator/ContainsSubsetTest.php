<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class ContainsSubsetTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', array(2));

        $op = new Operator\ContainsSubset($varA, $varB);
        $this->assertInstanceOf('Ruler\Proposition', $op);
    }

    /**
     * @dataProvider containsData
     */
    public function testContains($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\ContainsSubset($varA, $varB);
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

        $op = new Operator\DoesNotContainSubset($varA, $varB);
        $this->assertNotEquals($op->evaluate($context), $result);
    }

    public function containsData()
    {
        return array(
            array(array(1), array(1), true),
            array(array(1), 1, true),
            array(array(1, 2, 3), array(1, 2), true),
            array(array(1, 2, 3), array(2, 4), false),
            array(array('foo', 'bar', 'baz'), array('pow'), false),
            array(array('foo', 'bar', 'baz'), array('bar'), true),
            array(array('foo', 'bar', 'baz'), array('bar', 'baz'), true),
            array(null, 'bar', false),
            array(null, array('bar'), false),
            array(null, array('bar', 'baz'), false),
            array(null, null, true),
            array(array(), array(), true),
            array(array(1, 2, 3), array(2), true),
        );
    }
}
