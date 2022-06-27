<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class ContainsSubsetTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', [2]);

        $op = new Operator\ContainsSubset($varA, $varB);
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

        $op = new Operator\ContainsSubset($varA, $varB);
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

        $op = new Operator\DoesNotContainSubset($varA, $varB);
        $this->assertNotEquals($op->evaluate($context), $result);
    }

    public function containsData()
    {
        return [
            [[1], [1], true],
            [[1], 1, true],
            [[1, 2, 3], [1, 2], true],
            [[1, 2, 3], [2, 4], false],
            [['foo', 'bar', 'baz'], ['pow'], false],
            [['foo', 'bar', 'baz'], ['bar'], true],
            [['foo', 'bar', 'baz'], ['bar', 'baz'], true],
            [null, 'bar', false],
            [null, ['bar'], false],
            [null, ['bar', 'baz'], false],
            [null, null, true],
            [[], [], true],
            [[1, 2, 3], [2], true],
        ];
    }
}
