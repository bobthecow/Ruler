<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class ExistsTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);

        $op = new Operator\Exists($varA);
        $this->assertInstanceOf('Ruler\Proposition', $op);
        $this->assertInstanceOf('Ruler\Operator\ComparisonOperator', $op);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testExists($a, $result)
    {
        $varA    = new Variable($a);
        $context = new Context(['foo' => null, 'bar' => 'One Value']);

        $op = new Operator\Exists($varA);
        $this->assertEquals($op->evaluate($context), $result);

    }

    public function dataProvider()
    {
        return array(
            array('bar', true),
            array('foo', true),
            array('unknown', false)
        );
    }
}
