<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class UnionTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', [2]);

        $op = new Operator\Union($varA, $varB);
        $this->assertInstanceOf(\Ruler\VariableOperand::class, $op);
    }

    public function testInvalidData()
    {
        $varA = new Variable('a', 'string');
        $varB = new Variable('b', 'blah');
        $context = new Context();

        $op = new Operator\Union($varA, $varB);
        $this->assertEquals(
            $op->prepareValue($context)->getValue(),
            [
                'string',
                'blah',
            ]
        );
    }

    /**
     * @dataProvider unionData
     */
    public function testUnion($a, $b, $result)
    {
        $varA = new Variable('a', $a);
        $varB = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\Union($varA, $varB);
        $this->assertEquals($op->prepareValue($context)->getValue(), $result);
    }

    public function unionData()
    {
        return [
            [6, 2, [6, 2]],
            [
                'a',
                ['b', 'c'],
                ['a', 'b', 'c'],
            ],
            [
                ['a', 'b', 'c'],
                [],
                ['a', 'b', 'c'],
            ],
            [
                [],
                ['a', 'b', 'c'],
                ['a', 'b', 'c'],
            ],
            [
                [],
                [],
                [],
            ],
            [
                ['a', 'b', 'c'],
                ['d', 'e', 'f'],
                ['a', 'b', 'c', 'd', 'e', 'f'],
            ],
            [
                ['a', 'b', 'c'],
                ['a', 'b', 'c'],
                ['a', 'b', 'c'],
            ],
            [
                ['a', 'b', 'c'],
                ['b', 'c'],
                ['a', 'b', 'c'],
            ],
            [
                ['b', 'c'],
                ['b', 'd'],
                ['b', 'c', 'd'],
            ],
        ];
    }
}
