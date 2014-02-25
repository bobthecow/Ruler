<?php

namespace Ruler\Test\Operator;

use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class StartsWithTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 'foo bar baz');
        $varB = new Variable('b', 'foo');

        $op = new Operator\StartsWith($varA, $varB);
        $this->assertInstanceOf('Ruler\Proposition', $op);
    }

    /**
     * @dataProvider startsWithData
     */
    public function testStartsWith($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\StartsWith($varA, $varB);
        $this->assertEquals($op->evaluate($context), $result);
    }

    public function startsWithData()
    {
        return array(
            array('supercalifragilistic', 'supercalifragilistic', true),
            array('supercalifragilistic','super', true),
            array('supercalifragilistic','SUPER', false),
            array('supercalifragilistic', 'stic', false),
            array('supercalifragilistic', '', false),
        );
    }
}
