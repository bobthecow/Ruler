<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class StringContainsTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', [2]);

        $op = new Operator\StringContains($varA, $varB);
        $this->assertInstanceOf(\Ruler\Proposition::class, $op);
    }

    /**
     * @dataProvider containsData
     */
    public function testContains($a, $b, $result)
    {
        $varA = new Variable('a', $a);
        $varB = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\StringContains($varA, $varB);
        $this->assertEquals($op->evaluate($context), $result);
    }

    /**
     * @dataProvider containsData
     */
    public function testDoesNotContain($a, $b, $result)
    {
        $varA = new Variable('a', $a);
        $varB = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\StringDoesNotContain($varA, $varB);
        $this->assertNotEquals($op->evaluate($context), $result);
    }

    public function containsData()
    {
        return [
            ['supercalifragilistic', 'super', true],
            ['supercalifragilistic', 'fragil', true],
            ['supercalifragilistic', 'a', true],
            ['supercalifragilistic', 'stic', true],
            ['timmy', 'bob', false],
            ['tim', 'TIM', false],
        ];
    }
}
