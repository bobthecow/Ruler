<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Test\Fixtures\TrueProposition;
use Ruler\Test\Fixtures\FalseProposition;

class LogicalOrTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $true = new TrueProposition();

        $op = new Operator\LogicalOr(array($true));
        $this->assertInstanceOf('Ruler\Proposition', $op);
    }

    public function testConstructor()
    {
        $true    = new TrueProposition();
        $false   = new FalseProposition();
        $context = new Context();

        $op = new Operator\LogicalOr(array($true, $false));
        $this->assertTrue($op->evaluate($context));
    }

    public function testAddPropositionAndEvaluate()
    {
        $true    = new TrueProposition();
        $false   = new FalseProposition();
        $context = new Context();

        $op = new Operator\LogicalOr();

        $op->addProposition($false);
        $this->assertFalse($op->evaluate($context));

        $op->addProposition($false);
        $this->assertFalse($op->evaluate($context));

        $op->addOperand($true);
        $this->assertTrue($op->evaluate($context));
    }

    /**
     * @expectedException \LogicException
     */
    public function testExecutingALogicalOrWithoutPropositionsThrowsAnException()
    {
        $op = new Operator\LogicalOr();
        $op->evaluate(new Context());
    }
}
