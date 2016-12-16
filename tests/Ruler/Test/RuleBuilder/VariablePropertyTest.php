<?php

namespace Ruler\Test\RuleBuilder;

use Ruler\Context;
use Ruler\Value;
use Ruler\RuleBuilder;
use Ruler\RuleBuilder\Variable;
use Ruler\RuleBuilder\VariableProperty;

class VariablePropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $name = 'evil';
        $prop = new VariableProperty(new Variable(new RuleBuilder()), $name);
        $this->assertEquals($name, $prop->getName());
        $this->assertNull($prop->getValue());
    }

    public function testGetSetValue()
    {
        $values = explode(', ', 'Plug it, play it, burn it, rip it, drag and drop it, zip, unzip it');

        $prop = new VariableProperty(new Variable(new RuleBuilder()), 'technologic');
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

        $var = new Variable(new RuleBuilder(), 'root');

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

    public function testFluentInterfaceHelpersAndAnonymousVariables()
    {
        $context = new Context(array(
            'root' => array(
                'a' => 1,
                'b' => 2,
                'c' => array(1, 4),
                'd' => array(
                    'foo' => 1,
                    'bar' => 2,
                    'baz' => array(
                        'qux' => 3,
                    ),
                ),
                'e' => 'string',
                'f' => 'ring',
                'g' => 'stuff'
            ),
        ));

        $root = new Variable(new RuleBuilder(), 'root');

        $varA = $root['a'];
        $varB = $root['b'];
        $varC = $root['c'];
        $varD = $root['d'];
        $varE = $root['e'];
        $varF = $root['f'];
        $varG = $root['g'];

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

        $this->assertInstanceOf('Ruler\Operator\StringContains', $varE->stringContains('ring'));
        $this->assertTrue($varE->stringContains($varF)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\StringDoesNotContain', $varE->stringDoesNotContain('cheese'));
        $this->assertTrue($varE->stringDoesNotContain($varG)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\SetContains', $varC->setContains(1));
        $this->assertTrue($varC->setContains($varA)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\SetDoesNotContain', $varC->setDoesNotContain(1));
        $this->assertTrue($varC->setDoesNotContain($varB)->evaluate($context));

        $this->assertInstanceOf('Ruler\RuleBuilder\VariableProperty', $varD['bar']);
        $this->assertEquals($varD['foo']->getName(), 'foo');
        $this->assertTrue($varD['foo']->equalTo(1)->evaluate($context));

        $this->assertInstanceOf('Ruler\RuleBuilder\VariableProperty', $varD['foo']);
        $this->assertEquals($varD['bar']->getName(), 'bar');
        $this->assertTrue($varD['bar']->equalTo(2)->evaluate($context));

        $this->assertInstanceOf('Ruler\RuleBuilder\VariableProperty', $varD['baz']['qux']);
        $this->assertEquals($varD['baz']['qux']->getName(), 'qux');
        $this->assertTrue($varD['baz']['qux']->equalTo(3)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\EndsWith', $varE->endsWith('string'));
        $this->assertTrue($varE->endsWith($varE)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\EndsWithInsensitive', $varE->endsWithInsensitive('STRING'));
        $this->assertTrue($varE->endsWithInsensitive($varE)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\StartsWith', $varE->startsWith('string'));
        $this->assertTrue($varE->startsWith($varE)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\StartsWithInsensitive', $varE->startsWithInsensitive('STRING'));
        $this->assertTrue($varE->startsWithInsensitive($varE)->evaluate($context));
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Deprecated
     * @expectedExceptionMessage Contains operator is deprecated, please use SetContains
     */
    public function testDeprecationNoticeForContainsWithSet()
    {
        $context = new Context(array(
            'var' => array('foo', 'bar', 'baz'),
        ));

        $var = new Variable(new RuleBuilder(), 'var');

        $this->assertTrue($var->contains('bar')->evaluate($context));
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Deprecated
     * @expectedExceptionMessage Contains operator is deprecated, please use StringContains
     */
    public function testDeprecationNoticeForContainsWithString()
    {
        $context = new Context(array(
            'var' => 'string',
        ));

        $var = new Variable(new RuleBuilder(), 'var');

        $this->assertTrue($var->contains('ring')->evaluate($context));
    }
}
