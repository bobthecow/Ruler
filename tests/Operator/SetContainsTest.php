<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class SetContainsTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', [2]);

        $op = new Operator\SetContains($varA, $varB);
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

        $op = new Operator\SetContains($varA, $varB);
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

        $op = new Operator\SetDoesNotContain($varA, $varB);
        $this->assertNotEquals($op->evaluate($context), $result);
    }

    public function containsData()
    {
        return [
            [[1], 1, true],
            [[1, 2, 3], 1, true],
            [[1, 2, 3], 4, false],
            [['foo', 'bar', 'baz'], 'pow', false],
            [['foo', 'bar', 'baz'], 'bar', true],
            [null, 'bar', false],
            [null, null, false],
            [[1, 2, 3], [2], false],
            [[1, 2, ['foo']], ['foo'], true],
            [[1], [1], false],
        ];
    }
}
