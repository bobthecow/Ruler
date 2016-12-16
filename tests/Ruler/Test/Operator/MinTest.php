<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class MinTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $var = new Variable('a', array(5, 2, 9));

        $op = new Operator\Min($var);
        $this->assertInstanceOf('Ruler\\VariableOperand', $op);
    }

    /**
     * @dataProvider invalidData
     * @expectedException \RuntimeException
     * @expectedExceptionMessage min: all values must be numeric
     */
    public function testInvalidData($datum)
    {
        $var = new Variable('a', $datum);
        $context = new Context();

        $op = new Operator\Min($var);
        $op->prepareValue($context);
    }

    public function invalidData()
    {
        return array(
            array('string'),
            array(array('string')),
            array(array(1, 2, 3, 'string')),
            array(array('string', 1, 2, 3)),
        );
    }

    /**
     * @dataProvider minData
     */
    public function testMin($a, $result)
    {
        $var = new Variable('a', $a);
        $context = new Context();

        $op = new Operator\Min($var);
        $this->assertEquals(
            $result,
            $op->prepareValue($context)->getValue()
        );
    }

    public function minData()
    {
        return array(
            array(5, 5),
            array(array(), null),
            array(array(5), 5),
            array(array(-2, -5, -242), -242),
            array(array(2, 5, 242), 2),
        );
    }
}
