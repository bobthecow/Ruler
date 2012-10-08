<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;
use Ruler\Value;

/**
 *
 * @author Adam Englander <adam.englander@sellingsource.com>
 */
class ContainsTest extends \PHPUnit_Framework_TestCase
{
	public function testInterface()
	{
		$varA = new Variable('a', 1);
		$varB = new Variable('b', 2);

		$op = new Operator\Contains($varA, $varB);
		$this->assertInstanceOf('Ruler\Proposition', $op);
		$this->assertInstanceOf('Ruler\Operator\ComparisonOperator', $op);
	}

	public function testConstructorAndEvaluationNulls()
	{
		$varA    = new Variable('a', new Value(null));
		$varB    = new Variable('b', 'b');
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));

		$context['b'] = new Value(null);
		$this->assertTrue($op->evaluate($context));

		$context['a'] = function() {
			return 'a';
		};
		$this->assertFalse($op->evaluate($context));
	}

	public function testConstructorAndEvaluationObjects()
	{
		$varA    = new Variable('a', (object) array('a' => 1));
		$varB    = new Variable('b', (object) array('a' => 2));
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));

		$context['b'] = (object) array('a' => 1);
		$this->assertTrue($op->evaluate($context));
	}

	public function testEvaluationStringInStringEscapesRegularExpressionCharacters() {
		$varA = new Variable('a', 'abc/$^.*+def');
		$varB = new Variable('b', '/$^.*+');

		$op = new Operator\Contains($varA, $varB);
		$this->assertTrue($op->evaluate(new Context()));
	}

	public function testConstructorAndEvaluationStringInString()
	{
		$varA    = new Variable('a', 'bcd');
		$varB    = new Variable('b', 'a');
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));

		$context['a'] = 'abc';
		$this->assertTrue($op->evaluate($context));

		$context['a'] = 'cde';
		$context['b'] = function() {
			return 'e';
		};
		$this->assertTrue($op->evaluate($context));

		$context['4'] = 3;
		$this->assertTrue($op->evaluate($context));
	}

	public function testConstructorAndEvaluationArrayInString()
	{
		$varA    = new Variable('a', 'abc');
		$varB    = new Variable('b', array('b'));
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));
	}

	public function testConstructorAndEvaluationObjectInString()
	{
		$varA    = new Variable('a', 'abc');
		$varB    = new Variable('b', (object) array('b'));
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));
	}

	public function testConstructorAndEvaluationStringInArray()
	{
		$varA    = new Variable('a', array('b', 'c', 'd'));
		$varB    = new Variable('b', 'a');
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));

		$context['a'] = array('a', 'b', 'c');
		$this->assertTrue($op->evaluate($context));

		$context['a'] = array('c', 'd', 'e');
		$context['b'] = function() {
			return 'e';
		};
		$this->assertTrue($op->evaluate($context));

		$context['a'] = array('a', 'b', 'c');
		$context['b'] = array('b', 'c', 'd');
		$this->assertFalse($op->evaluate($context));
	}

	public function testConstructorAndEvaluationArrayInArray()
	{
		$varA    = new Variable('a', array('b', 'c', 'd'));
		$varB    = new Variable('b', array('a', 'b'));
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));

		$context['a'] = array('a', 'b', 'c');
		$this->assertTrue($op->evaluate($context));

		$context['a'] = array('c', 'd', 'e');
		$context['b'] = function() {
			return array('d', 'e');
		};
		$this->assertTrue($op->evaluate($context));
	}

	public function testConstructorAndEvaluationTraversableInArray()
	{
		$varA    = new Variable('a', array('b', 'c', 'd'));
		$varB    = new Variable('b', new \ArrayIterator(array('a', 'b')));
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));

		$context['a'] = array('a', 'b', 'c');
		$this->assertTrue($op->evaluate($context));

		$context['a'] = array('c', 'd', 'e');
		$context['b'] = function() {
			return new \ArrayIterator(array('d', 'e'));
		};
		$this->assertTrue($op->evaluate($context));
	}

	public function testConstructorAndEvaluationStringInIterator()
	{
		$varA    = new Variable('a', new \ArrayIterator(array('b', 'c', 'd')));
		$varB    = new Variable('b', 'a');
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));

		$context['a'] = new \ArrayIterator(array('a', 'b', 'c'));
		$this->assertTrue($op->evaluate($context));

		$context['a'] = new \ArrayIterator(array('c', 'd', 'e'));
		$context['b'] = function() {
			return 'd';
		};
		$this->assertTrue($op->evaluate($context));
	}

	public function testConstructorAndEvaluationArrayInIterator()
	{
		$varA    = new Variable('a', new \ArrayIterator(array('b', 'c', 'd')));
		$varB    = new Variable('b', array('a', 'b'));
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));

		$context['a'] = new \ArrayIterator(array('a', 'b', 'c'));
		$this->assertTrue($op->evaluate($context));

		$context['a'] = new \ArrayIterator(array('c', 'd', 'e'));
		$context['b'] = function() {
			return array('d', 'e');
		};
		$this->assertTrue($op->evaluate($context));
	}

	public function testConstructorAndEvaluationIteratorInIterator()
	{
		$varA    = new Variable('a', new \ArrayIterator(array('b', 'c', 'd')));
		$varB    = new Variable('b', new \ArrayIterator(array('a', 'b')));
		$context = new Context();

		$op = new Operator\Contains($varA, $varB);
		$this->assertFalse($op->evaluate($context));

		$context['a'] = new \ArrayIterator(array('a', 'b', 'c'));
		$this->assertTrue($op->evaluate($context));

		$context['a'] = new \ArrayIterator(array('c', 'd', 'e'));
		$context['b'] = function() {
			return new \ArrayIterator(array('d', 'e'));
		};
		$this->assertTrue($op->evaluate($context));
	}
}
