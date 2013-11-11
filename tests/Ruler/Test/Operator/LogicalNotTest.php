<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Test\Fixtures\TrueProposition;
use Ruler\Test\Fixtures\FalseProposition;

class LogicalNotTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $true = new TrueProposition();

        $op = new Operator\LogicalNot(array($true));
        $this->assertInstanceOf('Ruler\Proposition', $op);
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

    /**
     * @expectedException \LogicException
     */
    public function testExecutingALogicalNotWithoutPropositionsThrowsAnException()
    {
        $op = new Operator\LogicalNot();
        $op->evaluate(new Context());
    }

    /**
     * @expectedException \LogicException
     */
    public function testInstantiatingALogicalNotWithTooManyArgumentsThrowsAnException()
    {
        $op = new Operator\LogicalNot(array(new TrueProposition(), new FalseProposition()));
    }

    /**
     * @expectedException \LogicException
     */
    public function testAddingASecondPropositionToLogicalNotThrowsAnException()
    {
        $op = new Operator\LogicalNot();
        $op->addProposition(new TrueProposition());
        $op->addProposition(new TrueProposition());
    }
}
