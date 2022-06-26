<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class FloorTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);

        $op = new Operator\Floor($varA);
        $this->assertInstanceOf(\Ruler\VariableOperand::class, $op);
    }

    public function testInvalidData()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Arithmetic: values must be numeric');
        $varA = new Variable('a', 'string');
        $context = new Context();

        $op = new Operator\Floor($varA);
        $op->prepareValue($context);
    }

    /**
     * @dataProvider ceilingData
     */
    public function testCeiling($a, $result)
    {
        $varA = new Variable('a', $a);
        $context = new Context();

        $op = new Operator\Floor($varA);
        $this->assertEquals($op->prepareValue($context)->getValue(), $result);
    }

    public function ceilingData()
    {
        return [
            [1.2, 1],
            [1.0, 1],
            [1, 1],
            [-0.5, -1],
            [-1.5, -2],
        ];
    }
}
