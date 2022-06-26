<?php

namespace Ruler\Test\Functional;

use Ruler\RuleBuilder;
use Ruler\Context;
use PHPUnit\Framework\TestCase;

class RulerTest extends TestCase
{
    /**
     * @dataProvider truthTableTwo
     */
    public function testDeMorgan($p, $q)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p', 'q'));
        $this->assertEquals(
            $rb->create(
                $rb->logicalNot(
                    $rb->logicalAnd(
                        $rb['p']->equalTo(true),
                        $rb['q']->equalTo(true)
                    )
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalOr(
                    $rb->logicalNot(
                        $rb['p']->equalTo(true)
                    ),
                    $rb->logicalNot(
                        $rb['q']->equalTo(true)
                    )
                )
            )->evaluate($context)
        );
    }

    /**
     * @dataProvider truthTableTwo
     */
    public function testDeMorganTwo($p, $q)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p', 'q'));
        $this->assertEquals(
            $rb->create(
                $rb->logicalNot(
                    $rb->logicalOr(
                        $rb['p']->equalTo(true),
                        $rb['q']->equalTo(true)
                    )
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalAnd(
                    $rb->logicalNot(
                        $rb['p']->equalTo(true)
                    ),
                    $rb->logicalNot(
                        $rb['q']->equalTo(true)
                    )
                )
            )->evaluate($context)
        );
    }
    /**
     * @dataProvider truthTableTwo
     */
    public function testCommutation($p, $q)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p', 'q'));
        $this->assertEquals(
            $rb->create(
                $rb->logicalOr(
                    $rb['p']->equalTo(true),
                    $rb['q']->equalTo(true)
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalOr(
                    $rb['q']->equalTo(true),
                    $rb['p']->equalTo(true)
                )
            )->evaluate($context)
        );
    }

    /**
     * @dataProvider truthTableTwo
     */
    public function testCommutationTwo($p, $q)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p', 'q'));
        $this->assertEquals(
            $rb->create(
                $rb->logicalAnd(
                    $rb['p']->equalTo(true),
                    $rb['q']->equalTo(true)
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalAnd(
                    $rb['q']->equalTo(true),
                    $rb['p']->equalTo(true)
                )
            )->evaluate($context)
        );
    }

    /**
     * @dataProvider truthTableThree
     */
    public function testAssociation($p, $q, $r)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p', 'q', 'r'));
        $this->assertEquals(
            $rb->create(
                $rb->logicalOr(
                    $rb['p']->equalTo(true),
                    $rb->logicalOr(
                        $rb['q']->equalTo(true),
                        $rb['r']->equalTo(true)
                    )
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalOr(
                    $rb->logicalOr(
                        $rb['p']->equalTo(true),
                        $rb['q']->equalTo(true)
                    ),
                    $rb['r']->equalTo(true)
                )
            )->evaluate($context)
        );
    }

    /**
     * @dataProvider truthTableThree
     */
    public function testAssociationTwo($p, $q, $r)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p', 'q', 'r'));
        $this->assertEquals(
            $rb->create(
                $rb->logicalAnd(
                    $rb['p']->equalTo(true),
                    $rb->logicalAnd(
                        $rb['q']->equalTo(true),
                        $rb['r']->equalTo(true)
                    )
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalAnd(
                    $rb->logicalAnd(
                        $rb['p']->equalTo(true),
                        $rb['q']->equalTo(true)
                    ),
                    $rb['r']->equalTo(true)
                )
            )->evaluate($context)
        );
    }

    /**
     * @dataProvider truthTableThree
     */
    public function testDistribution($p, $q, $r)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p', 'q', 'r'));
        $this->assertEquals(
            $rb->create(
                $rb->logicalAnd(
                    $rb['p']->equalTo(true),
                    $rb->logicalOr(
                        $rb['q']->equalTo(true),
                        $rb['r']->equalTo(true)
                    )
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalOr(
                    $rb->logicalAnd(
                        $rb['p']->equalTo(true),
                        $rb['q']->equalTo(true)
                    ),
                    $rb->logicalAnd(
                        $rb['p']->equalTo(true),
                        $rb['r']->equalTo(true)
                    )
                )
            )->evaluate($context)
        );
    }

    /**
     * @dataProvider truthTableThree
     */
    public function testDistributionTwo($p, $q, $r)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p', 'q', 'r'));
        $this->assertEquals(
            $rb->create(
                $rb->logicalOr(
                    $rb['p']->equalTo(true),
                    $rb->logicalAnd(
                        $rb['q']->equalTo(true),
                        $rb['r']->equalTo(true)
                    )
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalAnd(
                    $rb->logicalOr(
                        $rb['p']->equalTo(true),
                        $rb['q']->equalTo(true)
                    ),
                    $rb->logicalOr(
                        $rb['p']->equalTo(true),
                        $rb['r']->equalTo(true)
                    )
                )
            )->evaluate($context)
        );
    }

    /**
     * @dataProvider truthTableOne
     */
    public function testDoubleNegation($p)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p'));
        $this->assertEquals(
            $rb->create(
                $rb['p']->equalTo(true)
            )->evaluate($context),
            $rb->create(
                $rb->logicalNot(
                    $rb->logicalNot(
                        $rb['p']->equalTo(true)
                    )
                )
            )->evaluate($context)
        );
    }

    /**
     * @dataProvider truthTableOne
     */
    public function testTautology($p)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p'));
        $this->assertEquals(
            $rb->create(
                $rb['p']->equalTo(true)
            )->evaluate($context),
            $rb->create(
                $rb->logicalOr(
                    $rb['p']->equalTo(true),
                    $rb['p']->equalTo(true)
                )
            )->evaluate($context)
        );
    }

    /**
     * @dataProvider truthTableOne
     */
    public function testTautologyTwo($p)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p'));
        $this->assertEquals(
            $rb->create(
                $rb['p']->equalTo(true)
            )->evaluate($context),
            $rb->create(
                $rb->logicalAnd(
                    $rb['p']->equalTo(true),
                    $rb['p']->equalTo(true)
                )
            )->evaluate($context)
        );
    }

    /**
     * @dataProvider truthTableOne
     */
    public function testExcludedMiddle($p)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p'));
        $this->assertEquals(
            $rb->create(
                $rb->logicalOr(
                    $rb['p']->equalTo(true),
                    $rb->logicalNot(
                        $rb['p']->equalTo(true)
                    )
                )
            )->evaluate($context),
            true
        );
    }

    /**
     * @dataProvider truthTableOne
     */
    public function testNonContradiction($p)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p'));
        $this->assertEquals(
            $rb->create(
                $rb->logicalNot(
                    $rb->logicalAnd(
                        $rb['p']->equalTo(true),
                        $rb->logicalNot(
                            $rb['p']->equalTo(true)
                        )
                    )
                )
            )->evaluate($context),
            true
        );
    }

    public function truthTableOne()
    {
        return array(
            array(true),
            array(false),
        );
    }

    public function truthTableTwo()
    {
        return array(
            array(true,  true),
            array(true,  false),
            array(false, true),
            array(false, false),
        );
    }

    public function truthTableThree()
    {
        return array(
            array(true,  true,  true),
            array(true,  true,  false),
            array(true,  false, true),
            array(true,  false, false),
            array(false, true,  true),
            array(false, true,  false),
            array(false, false, true),
            array(false, false, false),
        );
    }
}
