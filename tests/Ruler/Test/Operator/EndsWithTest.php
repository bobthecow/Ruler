<?php

namespace Ruler\Test\Operator;

use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class EndsWithTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 'foo bar baz');
        $varB = new Variable('b', 'foo');

        $op = new Operator\StartsWith($varA, $varB);
        $this->assertInstanceOf('Ruler\Proposition', $op);
    }

    /**
     * @dataProvider endsWithData
     */
    public function testEndsWith($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\EndsWith($varA, $varB);
        $this->assertEquals($op->evaluate($context), $result);
    }

    public function endsWithData()
    {
        return array(
            array('supercalifragilistic', 'supercalifragilistic', true),
            array('supercalifragilistic', 'stic', true),
            array('supercalifragilistic', 'STIC', false),
            array('supercalifragilistic', 'super', false),
            array('supercalifragilistic', '', false),
        );
    }
}
