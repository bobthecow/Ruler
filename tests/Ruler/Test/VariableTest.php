<?php

namespace Ruler\Test;

use Ruler\Variable;
use Ruler\Context;
use Ruler\Value;

class VariableTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $name = 'evil';
        $var = new Variable($name);
        $this->assertEquals($name, $var->getName());
        $this->assertNull($var->getValue());
    }

    public function testGetSetValue()
    {
        $values = explode(', ', 'Plug it, play it, burn it, rip it, drag and drop it, zip, unzip it');

        $variable = new Variable('technologic');
        foreach ($values as $valueString) {
            $variable->setValue($valueString);
            $this->assertEquals($valueString, $variable->getValue());
        }
    }

    public function testPrepareValue()
    {
        $values = array(
            'one' => 'Foo',
            'two' => 'BAR',
            'three' => function () {
                return 'baz';
            }
        );

        $context = new Context($values);

        $varA = new Variable('four', 'qux');
        $this->assertInstanceOf('Ruler\Value', $varA->prepareValue($context));
        $this->assertEquals(
            'qux',
            $varA->prepareValue($context)->getValue(),
            "Variables should return the default value if it's missing from the context."
        );

        $varB = new Variable('one', 'FAIL');
        $this->assertEquals(
            'Foo',
            $varB->prepareValue($context)->getValue()
        );

        $varC = new Variable('three', 'FAIL');
        $this->assertEquals(
            'baz',
            $varC->prepareValue($context)->getValue()
        );

        $varD = new Variable(null, 'qux');
        $this->assertInstanceOf('Ruler\Value', $varD->prepareValue($context));
        $this->assertEquals(
            'qux',
            $varD->prepareValue($context)->getValue(),
            "Anonymous variables don't require a name to prepare value"
        );
    }
}
