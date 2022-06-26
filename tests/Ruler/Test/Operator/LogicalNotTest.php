<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Test\Fixtures\TrueProposition;
use Ruler\Test\Fixtures\FalseProposition;
use PHPUnit\Framework\TestCase;

class LogicalNotTest extends TestCase
{
    public function testInterface()
    {
        $true = new TrueProposition();

        $op = new Operator\LogicalNot(array($true));
        $this->assertInstanceOf(\Ruler\Proposition::class, $op);
    }

    public function testConstructor()
    {
        $op = new Operator\LogicalNot(array(new FalseProposition()));
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
        $op = new Operator\LogicalNot(array(new TrueProposition(), new FalseProposition()));
    }

    public function testAddingASecondPropositionToLogicalNotThrowsAnException()
    {
        $this->expectException(\LogicException::class);
        $op = new Operator\LogicalNot();
        $op->addProposition(new TrueProposition());
        $op->addProposition(new TrueProposition());
    }
}
