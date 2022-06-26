<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;
use PHPUnit\Framework\TestCase;

class NegationTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);

        $op = new Operator\Negation($varA);
        $this->assertInstanceOf(\Ruler\VariableOperand::class, $op);
    }

    public function testInvalidData()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Arithmetic: values must be numeric');
        $varA    = new Variable('a', "string");
        $context = new Context();

        $op = new Operator\Negation($varA);
        $op->prepareValue($context);
    }

    /**
     * @dataProvider negateData
     */
    public function testSubtract($a, $result)
    {
        $varA    = new Variable('a', $a);
        $context = new Context();

        $op = new Operator\Negation($varA);
        $this->assertEquals($op->prepareValue($context)->getValue(), $result);
    }

    public function negateData()
    {
        return array(
            array(1, -1),
            array(0.0, 0.0),
            array("0", 0),
            array(-62834, 62834),
        );
    }
}
