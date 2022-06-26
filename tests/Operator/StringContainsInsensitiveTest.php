<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class StringContainsInsensitiveTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', [2]);

        $op = new Operator\StringContainsInsensitive($varA, $varB);
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

        $op = new Operator\StringContainsInsensitive($varA, $varB);
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

        $op = new Operator\StringDoesNotContainInsensitive($varA, $varB);
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
            ['timmy', 'tim', true],
            ['supercalifragilistic', 'SUPER', true],
            ['supercalifragilistic', 'frAgil', true],
            ['supercalifragilistic', 'A', true],
            ['supercalifragilistic', 'sTiC', true],
            ['timmy', 'bob', false],
            ['timmy', 'TIM', true],
            ['tim', 'TIM', true],
            ['tim', 'TiM', true],
        ];
    }
}
