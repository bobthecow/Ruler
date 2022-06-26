<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;
use PHPUnit\Framework\TestCase;

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
        $varA    = new Variable('a', "string");
        $context = new Context();

        $op = new Operator\Ceil($varA);
        $op->prepareValue($context);
    }

    /**
     * @dataProvider ceilingData
     */
    public function testCeiling($a, $result)
    {
        $varA    = new Variable('a', $a);
        $context = new Context();

        $op = new Operator\Ceil($varA);
        $this->assertEquals($op->prepareValue($context)->getValue(), $result);
    }

    public function ceilingData()
    {
        return array(
            array(1.2, 2),
            array(1.0, 1),
            array(1, 1),
            array(-0.5, 0),
            array(-1.5, -1),
        );
    }
}
