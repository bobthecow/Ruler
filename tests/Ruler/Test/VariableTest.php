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
            'three' => function() {
                return 'baz';
            }
        );

        $context = new Context($values);

        $varA = new Variable('four', 'qux');
        $this->assertInstanceOf('Ruler\Value', $varA->prepareValue($context));
        $this->assertEquals(
            'qux',
            $varA->prepareValue($context)->getValue(),
            "Variables should return the default value default if it's missing from the context."
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

    public function testFluentInterfaceHelpersAndAnonymousVariables()
    {
        $context = new Context(array(
            'a' => 1,
            'b' => 2,
            'c' => array(1, 4)
        ));
    
        $varA = new Variable('a');
        $varB = new Variable('b');
        $varC = new Variable('c');

        $this->assertInstanceOf('Ruler\Operator\ComparisonOperator', $varA->greaterThan(0));

        $this->assertInstanceOf('Ruler\Operator\GreaterThan', $varA->greaterThan(0));
        $this->assertTrue($varA->greaterThan(0)->evaluate($context));
        $this->assertFalse($varA->greaterThan(2)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\GreaterThanOrEqualTo', $varA->greaterThanOrEqualTo(0));
        $this->assertTrue($varA->greaterThanOrEqualTo(0)->evaluate($context));
        $this->assertTrue($varA->greaterThanOrEqualTo(1)->evaluate($context));
        $this->assertFalse($varA->greaterThanOrEqualTo(2)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\LessThan', $varA->lessThan(0));
        $this->assertTrue($varA->lessThan(2)->evaluate($context));
        $this->assertFalse($varA->lessThan(0)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\LessThanOrEqualTo', $varA->lessThanOrEqualTo(0));
        $this->assertTrue($varA->lessThanOrEqualTo(1)->evaluate($context));
        $this->assertTrue($varA->lessThanOrEqualTo(2)->evaluate($context));
        $this->assertFalse($varA->lessThanOrEqualTo(0)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\EqualTo', $varA->equalTo(0));
        $this->assertTrue($varA->equalTo(1)->evaluate($context));
        $this->assertFalse($varA->equalTo(0)->evaluate($context));
        $this->assertFalse($varA->equalTo(2)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\NotEqualTo', $varA->notEqualTo(0));
        $this->assertFalse($varA->notEqualTo(1)->evaluate($context));
        $this->assertTrue($varA->notEqualTo(0)->evaluate($context));
        $this->assertTrue($varA->notEqualTo(2)->evaluate($context));

        $this->assertFalse($varA->greaterThan($varB)->evaluate($context));
        $this->assertTrue($varA->lessThan($varB)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\Contains', $varC->contains(1));
        $this->assertTrue($varC->contains($varA)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\DoesNotContain', $varC->doesNotContain(1));
        $this->assertTrue($varC->doesNotContain($varB)->evaluate($context));
    }
}
