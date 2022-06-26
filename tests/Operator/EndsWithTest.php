<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class EndsWithTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 'foo bar baz');
        $varB = new Variable('b', 'foo');

        $op = new Operator\StartsWith($varA, $varB);
        $this->assertInstanceOf(\Ruler\Proposition::class, $op);
    }

    /**
     * @dataProvider endsWithData
     */
    public function testEndsWith($a, $b, $result)
    {
        $varA = new Variable('a', $a);
        $varB = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\EndsWith($varA, $varB);
        $this->assertEquals($op->evaluate($context), $result);
    }

    public function endsWithData()
    {
        return [
            ['supercalifragilistic', 'supercalifragilistic', true],
            ['supercalifragilistic', 'stic', true],
            ['supercalifragilistic', 'STIC', false],
            ['supercalifragilistic', 'super', false],
            ['supercalifragilistic', '', false],
        ];
    }
}
