<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class MultiplicationTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', [2]);

        $op = new Operator\Multiplication($varA, $varB);
        $this->assertInstanceOf(\Ruler\VariableOperand::class, $op);
    }

    public function testInvalidData()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Arithmetic: values must be numeric');
        $varA = new Variable('a', 'string');
        $varB = new Variable('b', 'blah');
        $context = new Context();

        $op = new Operator\Multiplication($varA, $varB);
        $op->prepareValue($context);
    }

    /**
     * @dataProvider multiplyData
     */
    public function testMultiply($a, $b, $result)
    {
        $varA = new Variable('a', $a);
        $varB = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\Multiplication($varA, $varB);
        $this->assertEquals($op->prepareValue($context)->getValue(), $result);
    }

    public function multiplyData()
    {
        return [
            [6, 2, 12],
            [7, 3, 21],
            [2.5, 1.5, 3.75],
        ];
    }
}
