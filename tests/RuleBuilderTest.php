<?php

namespace Ruler\Test;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\RuleBuilder;
use Ruler\Test\Fixtures\FalseProposition;
use Ruler\Test\Fixtures\TrueProposition;

class RuleBuilderTest extends TestCase
{
    public function testInterface()
    {
        $rb = new RuleBuilder();
        $this->assertInstanceOf(\Ruler\RuleBuilder::class, $rb);
        $this->assertInstanceOf(\ArrayAccess::class, $rb);
    }

    public function testManipulateVariablesViaArrayAccess()
    {
        $name = 'alpha';
        $rb = new RuleBuilder();

        $this->assertFalse(isset($rb[$name]));

        $var = $rb[$name];
        $this->assertTrue(isset($rb[$name]));

        $this->assertInstanceOf(\Ruler\Variable::class, $var);
        $this->assertInstanceOf(\Ruler\RuleBuilder\Variable::class, $var);
        $this->assertEquals($name, $var->getName());

        $this->assertSame($var, $rb[$name]);
        $this->assertNull($var->getValue());

        $rb[$name] = 'eeesh.';
        $this->assertEquals('eeesh.', $var->getValue());

        unset($rb[$name]);
        $this->assertFalse(isset($rb[$name]));
        $this->assertNotSame($var, $rb[$name]);
    }

    public function testLogicalOperatorGeneration()
    {
        $rb = new RuleBuilder();
        $context = new Context();

        $true = new TrueProposition();
        $false = new FalseProposition();

        $this->assertInstanceOf(\Ruler\Operator\LogicalAnd::class, $rb->logicalAnd($true, $false));
        $this->assertFalse($rb->logicalAnd($true, $false)->evaluate($context));

        $this->assertInstanceOf(\Ruler\Operator\LogicalOr::class, $rb->logicalOr($true, $false));
        $this->assertTrue($rb->logicalOr($true, $false)->evaluate($context));

        $this->assertInstanceOf(\Ruler\Operator\LogicalNot::class, $rb->logicalNot($true));
        $this->assertFalse($rb->logicalNot($true)->evaluate($context));

        $this->assertInstanceOf(\Ruler\Operator\LogicalXor::class, $rb->logicalXor($true, $false));
        $this->assertTrue($rb->logicalXor($true, $false)->evaluate($context));
    }

    public function testRuleCreation()
    {
        $rb = new RuleBuilder();
        $context = new Context();

        $true = new TrueProposition();
        $false = new FalseProposition();

        $this->assertInstanceOf(\Ruler\Rule::class, $rb->create($true));
        $this->assertTrue($rb->create($true)->evaluate($context));
        $this->assertFalse($rb->create($false)->evaluate($context));

        $executed = false;
        $rule = $rb->create($true, function () use (&$executed) {
            $executed = true;
        });

        $this->assertFalse($executed);
        $rule->execute($context);
        $this->assertTrue($executed);
    }

    public function testNotAddEqualTo()
    {
        $rb = new RuleBuilder();
        $context = new Context([
            'A2' => 8,
            'A3' => 4,
            'B2' => 13,
        ]);

        $rule = $rb->logicalNot(
            $rb['A2']->equalTo($rb['B2'])
        );
        $this->assertTrue($rule->evaluate($context));

        $rule = $rb['A2']->add($rb['A3']);

        $rule = $rb->logicalNot(
            $rule->equalTo($rb['B2'])
        );
        $this->assertTrue($rule->evaluate($context));
    }

    public function testExternalOperators()
    {
        $rb = new RuleBuilder();
        $rb->registerOperatorNamespace('\Ruler\Test\Fixtures');

        $context = new Context(['a' => 100]);
        $varA = $rb['a'];

        $this->assertTrue($varA->aLotGreaterThan(1)->evaluate($context));

        $context['a'] = 9;
        $this->assertFalse($varA->aLotGreaterThan(1)->evaluate($context));
    }

    public function testLogicExceptionOnUnknownOperator()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Unknown operator: "aLotBiggerThan"');
        $rb = new RuleBuilder();
        $rb->registerOperatorNamespace('\Ruler\Test\Fixtures');
        $varA = $rb['a'];

        $varA->aLotBiggerThan(1);
    }
}
