<?php

namespace Ruler\Test;

use Ruler\Context;
use Ruler\Value;
use Ruler\Variable;
use Ruler\VariableProperty;

class VariablePropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $name = 'evil';
        $prop = new VariableProperty(new Variable, $name);
        $this->assertEquals($name, $prop->getName());
        $this->assertNull($prop->getValue());
    }

    public function testGetSetValue()
    {
        $values = explode(', ', 'Plug it, play it, burn it, rip it, drag and drop it, zip, unzip it');

        $prop = new VariableProperty(new Variable, 'technologic');
        foreach ($values as $valueString) {
            $prop->setValue($valueString);
            $this->assertEquals($valueString, $prop->getValue());
        }
    }

    public function testPrepareValue()
    {
        $values = array(
            'root' => array(
                'one' => 'Foo',
                'two' => 'BAR',
            ),
        );

        $context = new Context($values);

        $var = new Variable('root');

        $propA = new VariableProperty($var, 'undefined', 'default');
        $this->assertInstanceOf('Ruler\Value', $propA->prepareValue($context));
        $this->assertEquals(
            'default',
            $propA->prepareValue($context)->getValue(),
            "VariableProperties should return the default value if it's missing from the context."
        );

        $propB = new VariableProperty($var, 'one', 'FAIL');
        $this->assertEquals(
            'Foo',
            $propB->prepareValue($context)->getValue()
        );
    }
}
