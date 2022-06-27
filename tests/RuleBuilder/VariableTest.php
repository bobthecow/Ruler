<?php

namespace Ruler\Test\RuleBuilder;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\RuleBuilder;
use Ruler\RuleBuilder\Variable;

class VariableTest extends TestCase
{
    public function testConstructor()
    {
        $name = 'evil';
        $var = new Variable(new RuleBuilder(), $name);
        $this->assertEquals($name, $var->getName());
        $this->assertNull($var->getValue());
    }

    public function testGetSetValue()
    {
        $values = \explode(', ', 'Plug it, play it, burn it, rip it, drag and drop it, zip, unzip it');

        $variable = new Variable(new RuleBuilder(), 'technologic');
        foreach ($values as $valueString) {
            $variable->setValue($valueString);
            $this->assertEquals($valueString, $variable->getValue());
        }
    }

    public function testPrepareValue()
    {
        $values = [
            'one'   => 'Foo',
            'two'   => 'BAR',
            'three' => function () {
                return 'baz';
            },
        ];

        $context = new Context($values);

        $rb = new RuleBuilder();
        $varA = new Variable($rb, 'four', 'qux');
        $this->assertInstanceOf(\Ruler\Value::class, $varA->prepareValue($context));
        $this->assertEquals(
            'qux',
            $varA->prepareValue($context)->getValue(),
            "Variables should return the default value if it's missing from the context."
        );

        $varB = new Variable($rb, 'one', 'FAIL');
        $this->assertEquals(
            'Foo',
            $varB->prepareValue($context)->getValue()
        );

        $varC = new Variable($rb, 'three', 'FAIL');
        $this->assertEquals(
            'baz',
            $varC->prepareValue($context)->getValue()
        );

        $varD = new Variable($rb, null, 'qux');
        $this->assertInstanceOf(\Ruler\Value::class, $varD->prepareValue($context));
        $this->assertEquals(
            'qux',
            $varD->prepareValue($context)->getValue(),
            "Anonymous variables don't require a name to prepare value"
        );
    }

    public function testFluentInterfaceHelpersAndAnonymousVariables()
    {
        $rb = new RuleBuilder();
        $context = new Context([
            'a' => 1,
            'b' => 2,
            'c' => [1, 4],
            'd' => [
                'foo' => 1,
                'bar' => 2,
                'baz' => [
                    'qux' => 3,
                ],
            ],
            'e' => 1.5,
        ]);

        $varA = new Variable($rb, 'a');
        $varB = new Variable($rb, 'b');
        $varC = new Variable($rb, 'c');
        $varD = new Variable($rb, 'd');
        $varE = new Variable($rb, 'e');

        $this->assertInstanceOf(\Ruler\Operator\GreaterThan::class, $varA->greaterThan(0));
        $this->assertTrue($varA->greaterThan(0)->evaluate($context));
        $this->assertFalse($varA->greaterThan(2)->evaluate($context));

        $this->assertInstanceOf(\Ruler\Operator\GreaterThanOrEqualTo::class, $varA->greaterThanOrEqualTo(0));
        $this->assertTrue($varA->greaterThanOrEqualTo(0)->evaluate($context));
        $this->assertTrue($varA->greaterThanOrEqualTo(1)->evaluate($context));
        $this->assertFalse($varA->greaterThanOrEqualTo(2)->evaluate($context));

        $this->assertInstanceOf(\Ruler\Operator\LessThan::class, $varA->lessThan(0));
        $this->assertTrue($varA->lessThan(2)->evaluate($context));
        $this->assertFalse($varA->lessThan(0)->evaluate($context));

        $this->assertInstanceOf(\Ruler\Operator\LessThanOrEqualTo::class, $varA->lessThanOrEqualTo(0));
        $this->assertTrue($varA->lessThanOrEqualTo(1)->evaluate($context));
        $this->assertTrue($varA->lessThanOrEqualTo(2)->evaluate($context));
        $this->assertFalse($varA->lessThanOrEqualTo(0)->evaluate($context));

        $this->assertInstanceOf(\Ruler\Operator\EqualTo::class, $varA->equalTo(0));
        $this->assertTrue($varA->equalTo(1)->evaluate($context));
        $this->assertFalse($varA->equalTo(0)->evaluate($context));
        $this->assertFalse($varA->equalTo(2)->evaluate($context));

        $this->assertInstanceOf(\Ruler\Operator\NotEqualTo::class, $varA->notEqualTo(0));
        $this->assertFalse($varA->notEqualTo(1)->evaluate($context));
        $this->assertTrue($varA->notEqualTo(0)->evaluate($context));
        $this->assertTrue($varA->notEqualTo(2)->evaluate($context));

        $this->assertInstanceof(\Ruler\RuleBuilder\Variable::class, $varA->add(3));
        $this->assertInstanceof(\Ruler\Operator\Addition::class, $varA->add(3)->getValue());
        $this->assertInstanceof(\Ruler\Value::class, $varA->add(3)->prepareValue($context));
        $this->assertEquals(4, $varA->add(3)->prepareValue($context)->getValue());
        $this->assertEquals(0, $varA->add(-1)->prepareValue($context)->getValue());

        $this->assertInstanceof(\Ruler\RuleBuilder\Variable::class, $varE->ceil());
        $this->assertInstanceof(\Ruler\Operator\Ceil::class, $varE->ceil()->getValue());
        $this->assertEquals(2, $varE->ceil()->prepareValue($context)->getValue());

        $this->assertInstanceof(\Ruler\RuleBuilder\Variable::class, $varB->divide(3));
        $this->assertInstanceof(\Ruler\Operator\Division::class, $varB->divide(3)->getValue());
        $this->assertEquals(1, $varB->divide(2)->prepareValue($context)->getValue());
        $this->assertEquals(-2, $varB->divide(-1)->prepareValue($context)->getValue());

        $this->assertInstanceof(\Ruler\RuleBuilder\Variable::class, $varE->floor());
        $this->assertInstanceof(\Ruler\Operator\Floor::class, $varE->floor()->getValue());
        $this->assertEquals(1, $varE->floor()->prepareValue($context)->getValue());

        $this->assertInstanceof(\Ruler\RuleBuilder\Variable::class, $varA->modulo(3));
        $this->assertInstanceof(\Ruler\Operator\Modulo::class, $varA->modulo(3)->getValue());
        $this->assertEquals(1, $varA->modulo(3)->prepareValue($context)->getValue());
        $this->assertEquals(0, $varB->modulo(2)->prepareValue($context)->getValue());

        $this->assertInstanceof(\Ruler\RuleBuilder\Variable::class, $varA->multiply(3));
        $this->assertInstanceof(\Ruler\Operator\Multiplication::class, $varA->multiply(3)->getValue());
        $this->assertEquals(6, $varB->multiply(3)->prepareValue($context)->getValue());
        $this->assertEquals(-2, $varB->multiply(-1)->prepareValue($context)->getValue());

        $this->assertInstanceof(\Ruler\RuleBuilder\Variable::class, $varA->negate());
        $this->assertInstanceof(\Ruler\Operator\Negation::class, $varA->negate()->getValue());
        $this->assertEquals(-1, $varA->negate()->prepareValue($context)->getValue());
        $this->assertEquals(-2, $varB->negate()->prepareValue($context)->getValue());

        $this->assertInstanceof(\Ruler\RuleBuilder\Variable::class, $varA->subtract(3));
        $this->assertInstanceof(\Ruler\Operator\Subtraction::class, $varA->subtract(3)->getValue());
        $this->assertEquals(-2, $varA->subtract(3)->prepareValue($context)->getValue());
        $this->assertEquals(2, $varA->subtract(-1)->prepareValue($context)->getValue());

        $this->assertInstanceof(\Ruler\RuleBuilder\Variable::class, $varA->exponentiate(3));
        $this->assertInstanceof(\Ruler\Operator\Exponentiate::class, $varA->exponentiate(3)->getValue());
        $this->assertEquals(1, $varA->exponentiate(3)->prepareValue($context)->getValue());
        $this->assertEquals(1, $varA->exponentiate(-1)->prepareValue($context)->getValue());
        $this->assertEquals(8, $varB->exponentiate(3)->prepareValue($context)->getValue());
        $this->assertEquals(0.5, $varB->exponentiate(-1)->prepareValue($context)->getValue());

        $this->assertFalse($varA->greaterThan($varB)->evaluate($context));
        $this->assertTrue($varA->lessThan($varB)->evaluate($context));

        $this->assertInstanceOf(\Ruler\Operator\SetContains::class, $varC->setContains(1));
        $this->assertTrue($varC->setContains($varA)->evaluate($context));

        $this->assertInstanceOf(\Ruler\Operator\SetDoesNotContain::class, $varC->setDoesNotContain(1));
        $this->assertTrue($varC->setDoesNotContain($varB)->evaluate($context));

        $this->assertInstanceOf(\Ruler\RuleBuilder\VariableProperty::class, $varD['bar']);
        $this->assertEquals($varD['foo']->getName(), 'foo');
        $this->assertTrue($varD['foo']->equalTo(1)->evaluate($context));

        $this->assertInstanceOf(\Ruler\RuleBuilder\VariableProperty::class, $varD['foo']);
        $this->assertEquals($varD['bar']->getName(), 'bar');
        $this->assertTrue($varD['bar']->equalTo(2)->evaluate($context));

        $this->assertInstanceOf(\Ruler\RuleBuilder\VariableProperty::class, $varD['baz']['qux']);
        $this->assertEquals($varD['baz']['qux']->getName(), 'qux');
        $this->assertTrue($varD['baz']['qux']->equalTo(3)->evaluate($context));
    }

    public function testArrayAccess()
    {
        $var = new Variable(new RuleBuilder());
        $this->assertInstanceOf(\ArrayAccess::class, $var);

        $foo = $var['foo'];
        $bar = $var['bar'];
        $this->assertInstanceOf(\Ruler\RuleBuilder\VariableProperty::class, $foo);
        $this->assertInstanceOf(\Ruler\RuleBuilder\VariableProperty::class, $bar);

        $this->assertSame($var['foo'], $foo);
        $this->assertSame($var['bar'], $bar);
        $this->assertNotSame($foo, $bar);

        $this->assertTrue(isset($var['foo']));
        $this->assertTrue(isset($var['bar']));

        $this->assertFalse(isset($var['baz']));
        $this->assertFalse(isset($var['qux']));

        $baz = $var->getProperty('baz');
        $this->assertTrue(isset($var['baz']));

        $qux = $var['qux'];
        $this->assertTrue(isset($var['qux']));

        unset($var['foo'], $var['bar'], $var['baz']);

        $this->assertFalse(isset($var['foo']));
        $this->assertFalse(isset($var['bar']));
        $this->assertFalse(isset($var['baz']));
        $this->assertTrue(isset($var['qux']));
    }
}
