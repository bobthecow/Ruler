<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class StringContainsInsensitiveTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', array(2));

        $op = new Operator\StringContainsInsensitive($varA, $varB);
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

        $op = new Operator\StringContainsInsensitive($varA, $varB);
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

        $op = new Operator\StringDoesNotContainInsensitive($varA, $varB);
        $this->assertNotEquals($op->evaluate($context), $result);
    }

    public function containsData()
    {
        return array(
            array('supercalifragilistic', 'super', true),
            array('supercalifragilistic', 'fragil', true),
            array('supercalifragilistic', 'a', true),
            array('supercalifragilistic', 'stic', true),
            array('timmy', 'bob', false),
            array('timmy', 'tim', true),
            array('supercalifragilistic', 'SUPER', true),
            array('supercalifragilistic', 'frAgil', true),
            array('supercalifragilistic', 'A', true),
            array('supercalifragilistic', 'sTiC', true),
            array('timmy', 'bob', false),
            array('timmy', 'TIM', true),
            array('tim', 'TIM', true),
            array('tim', 'TiM', true),
        );
    }
}
