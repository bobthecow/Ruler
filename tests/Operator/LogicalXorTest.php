<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Test\Fixtures\FalseProposition;
use Ruler\Test\Fixtures\TrueProposition;

class LogicalXorTest extends TestCase
{
    public function testInterface()
    {
        $true = new TrueProposition();

        $op = new Operator\LogicalXor([$true]);
        $this->assertInstanceOf(\Ruler\Proposition::class, $op);
    }

    public function testConstructor()
    {
        $true = new TrueProposition();
        $false = new FalseProposition();
        $context = new Context();

        $op = new Operator\LogicalXor([$true, $false]);
        $this->assertTrue($op->evaluate($context));
    }

    public function testAddPropositionAndEvaluate()
    {
        $true = new TrueProposition();
        $false = new FalseProposition();
        $context = new Context();

        $op = new Operator\LogicalXor();

        $op->addProposition($false);
        $this->assertFalse($op->evaluate($context));

        $op->addOperand($false);
        $this->assertFalse($op->evaluate($context));

        $op->addProposition($true);
        $this->assertTrue($op->evaluate($context));

        $op->addOperand($true);
        $this->assertFalse($op->evaluate($context));
    }

    public function testExecutingALogicalXorWithoutPropositionsThrowsAnException()
    {
        $this->expectException(\LogicException::class);
        $op = new Operator\LogicalXor();
        $op->evaluate(new Context());
    }
}
