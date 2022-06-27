<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Test\Fixtures\FalseProposition;
use Ruler\Test\Fixtures\TrueProposition;

class LogicalNotTest extends TestCase
{
    public function testInterface()
    {
        $true = new TrueProposition();

        $op = new Operator\LogicalNot([$true]);
        $this->assertInstanceOf(\Ruler\Proposition::class, $op);
    }

    public function testConstructor()
    {
        $op = new Operator\LogicalNot([new FalseProposition()]);
        $this->assertTrue($op->evaluate(new Context()));
    }

    public function testAddPropositionAndEvaluate()
    {
        $op = new Operator\LogicalNot();

        $op->addProposition(new TrueProposition());
        $this->assertFalse($op->evaluate(new Context()));
    }

    public function testExecutingALogicalNotWithoutPropositionsThrowsAnException()
    {
        $this->expectException(\LogicException::class);
        $op = new Operator\LogicalNot();
        $op->evaluate(new Context());
    }

    public function testInstantiatingALogicalNotWithTooManyArgumentsThrowsAnException()
    {
        $this->expectException(\LogicException::class);
        $op = new Operator\LogicalNot([new TrueProposition(), new FalseProposition()]);
    }

    public function testAddingASecondPropositionToLogicalNotThrowsAnException()
    {
        $this->expectException(\LogicException::class);
        $op = new Operator\LogicalNot();
        $op->addProposition(new TrueProposition());
        $op->addProposition(new TrueProposition());
    }
}
