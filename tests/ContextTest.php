<?php

/*
 * Copyright (c) 2009 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Ruler\Test;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Test\Fixtures\Fact;
use Ruler\Test\Fixtures\Invokable;

/**
 * Ruler Context test.
 *
 * Derived from Pimple, by Fabien Potencier:
 *
 * https://github.com/fabpot/Pimple
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 * @author Justin Hileman <justin@justinhileman.info>
 */
class ContextTest extends TestCase
{
    public function testConstructor()
    {
        $facts = [
            'name'      => 'Mint Chip',
            'type'      => 'Ice Cream',
            'delicious' => function () {
                return true;
            },
        ];

        $context = new Context($facts);

        $this->assertTrue(isset($context['name']));
        $this->assertEquals('Mint Chip', $context['name']);

        $this->assertTrue(isset($context['type']));
        $this->assertEquals('Ice Cream', $context['type']);

        $this->assertTrue(isset($context['delicious']));
        $this->assertTrue($context['delicious']);
    }

    public function testWithString()
    {
        $context = new Context();
        $context['param'] = 'value';

        $this->assertEquals('value', $context['param']);
    }

    public function testWithClosure()
    {
        $context = new Context();
        $context['fact'] = function () {
            return new Fact();
        };

        $this->assertInstanceOf(\Ruler\Test\Fixtures\Fact::class, $context['fact']);
    }

    public function testFactsShouldBeDifferent()
    {
        $context = new Context();
        $context['fact'] = function () {
            return new Fact();
        };

        $factOne = $context['fact'];
        $this->assertInstanceOf(\Ruler\Test\Fixtures\Fact::class, $factOne);

        $factTwo = $context['fact'];
        $this->assertInstanceOf(\Ruler\Test\Fixtures\Fact::class, $factTwo);

        $this->assertNotSame($factOne, $factTwo);
    }

    public function testShouldPassContextAsParameter()
    {
        $context = new Context();
        $context['fact'] = function () {
            return new Fact();
        };
        $context['context'] = function ($context) {
            return $context;
        };

        $this->assertNotSame($context, $context['fact']);
        $this->assertSame($context, $context['context']);
    }

    public function testIsset()
    {
        $context = new Context();
        $context['param'] = 'value';
        $context['fact'] = function () {
            return new Fact();
        };

        $context['null'] = null;

        $this->assertTrue(isset($context['param']));
        $this->assertTrue(isset($context['fact']));
        $this->assertTrue(isset($context['null']));
        $this->assertFalse(isset($context['non_existent']));
    }

    public function testConstructorInjection()
    {
        $params = ['param' => 'value'];
        $context = new Context($params);

        $this->assertSame($params['param'], $context['param']);
    }

    public function testOffsetGetValidatesKeyIsPresent()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Fact "foo" is not defined.');
        $context = new Context();
        echo $context['foo'];
    }

    public function testOffsetGetHonorsNullValues()
    {
        $context = new Context();
        $context['foo'] = null;
        $this->assertNull($context['foo']);
    }

    public function testUnset()
    {
        $context = new Context();
        $context['param'] = 'value';
        $context['fact'] = function () {
            return new Fact();
        };

        unset($context['param'], $context['fact']);
        $this->assertFalse(isset($context['param']));
        $this->assertFalse(isset($context['fact']));
    }

    /**
     * @dataProvider factDefinitionProvider
     */
    public function testShare($fact)
    {
        $context = new Context();
        $context['shared_fact'] = $context->share($fact);

        $factOne = $context['shared_fact'];
        $this->assertInstanceOf(\Ruler\Test\Fixtures\Fact::class, $factOne);

        $factTwo = $context['shared_fact'];
        $this->assertInstanceOf(\Ruler\Test\Fixtures\Fact::class, $factTwo);

        $this->assertSame($factOne, $factTwo);
    }

    /**
     * @dataProvider factDefinitionProvider
     */
    public function testProtect($fact)
    {
        $context = new Context();
        $context['protected'] = $context->protect($fact);

        $this->assertSame($fact, $context['protected']);
    }

    public function testGlobalFunctionNameAsParameterValue()
    {
        $context = new Context();
        $context['global_function'] = 'strlen';
        $this->assertSame('strlen', $context['global_function']);
    }

    public function testRaw()
    {
        $context = new Context();
        $context['fact'] = $definition = function () { return 'foo'; };
        $this->assertSame($definition, $context->raw('fact'));
    }

    public function testRawHonorsNullValues()
    {
        $context = new Context();
        $context['foo'] = null;
        $this->assertNull($context->raw('foo'));
    }

    public function testRawValidatesKeyIsPresent()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Fact "foo" is not defined.');
        $context = new Context();
        $context->raw('foo');
    }

    public function testKeys()
    {
        $context = new Context();
        $context['foo'] = 123;
        $context['bar'] = 123;

        $this->assertEquals(['foo', 'bar'], $context->keys());
    }

    /** @test */
    public function settingAnInvokableObjectShouldTreatItAsFactory()
    {
        $context = new Context();
        $context['invokable'] = new Invokable();

        $this->assertInstanceOf(\Ruler\Test\Fixtures\Fact::class, $context['invokable']);
    }

    /** @test */
    public function settingNonInvokableObjectShouldTreatItAsParameter()
    {
        $context = new Context();
        $context['non_invokable'] = new Fact();

        $this->assertInstanceOf(\Ruler\Test\Fixtures\Fact::class, $context['non_invokable']);
    }

    /**
     * @dataProvider badFactDefinitionProvider
     */
    public function testShareFailsForInvalidFactDefinitions($fact)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value is not a Closure or invokable object.');
        $context = new Context();
        $context->share($fact);
    }

    /**
     * @dataProvider badFactDefinitionProvider
     */
    public function testProtectFailsForInvalidFactDefinitions($fact)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Callable is not a Closure or invokable object.');
        $context = new Context();
        $context->protect($fact);
    }

    /**
     * Provider for invalid fact definitions.
     */
    public function badFactDefinitionProvider()
    {
        return [
            [123],
            [new Fact()],
        ];
    }

    /**
     * Provider for fact definitions.
     */
    public function factDefinitionProvider()
    {
        return [
            [function ($value) {
                $fact = new Fact();
                $fact->value = $value;

                return $fact;
            }],
            [new Invokable()],
        ];
    }
}
