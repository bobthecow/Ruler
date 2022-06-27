<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Test\Fixtures\FalseProposition;
use Ruler\Test\Fixtures\TrueProposition;

class LogicalAndTest extends TestCase
{
    public function testInterface()
    {
        $true = new TrueProposition();

        $op = new Operator\LogicalAnd([$true]);
        $this->assertInstanceOf(\Ruler\Proposition::class, $op);
    }

    public function testConstructor()
    {
        $true = new TrueProposition();
        $false = new FalseProposition();
        $context = new Context();

        $op = new Operator\LogicalAnd([$true, $false]);
        $this->assertFalse($op->evaluate($context));
    }

    public function testAddPropositionAndEvaluate()
    {
        $true = new TrueProposition();
        $false = new FalseProposition();
        $context = new Context();

        $op = new Operator\LogicalAnd();

        $op->addProposition($true);
        $this->assertTrue($op->evaluate($context));

        $op->addOperand($true);
        $this->assertTrue($op->evaluate($context));

        $op->addProposition($false);
        $this->assertFalse($op->evaluate($context));
    }

    public function testExecutingALogicalAndWithoutPropositionsThrowsAnException()
    {
        $this->expectException(\LogicException::class);
        $op = new Operator\LogicalAnd();
        $op->evaluate(new Context());
    }
}
