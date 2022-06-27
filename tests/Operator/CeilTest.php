<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class CeilTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);

        $op = new Operator\Ceil($varA);
        $this->assertInstanceOf(\Ruler\VariableOperand::class, $op);
    }

    public function testInvalidData()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Arithmetic: values must be numeric');
        $varA = new Variable('a', 'string');
        $context = new Context();

        $op = new Operator\Ceil($varA);
        $op->prepareValue($context);
    }

    /**
     * @dataProvider ceilingData
     */
    public function testCeiling($a, $result)
    {
        $varA = new Variable('a', $a);
        $context = new Context();

        $op = new Operator\Ceil($varA);
        $this->assertEquals($op->prepareValue($context)->getValue(), $result);
    }

    public function ceilingData()
    {
        return [
            [1.2, 2],
            [1.0, 1],
            [1, 1],
            [-0.5, 0],
            [-1.5, -1],
        ];
    }
}
