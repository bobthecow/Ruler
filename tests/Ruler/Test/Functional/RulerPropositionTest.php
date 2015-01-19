<?php

namespace Ruler\Test\Functional;

use Ruler\RuleBuilder;
use Ruler\Context;

use Ruler\Operator\EqualTo;

/**
 * Class RulerPropositionTest
 *
 * When implementing both interfaces contracts, Proposition and VariableOperand, we can
 * just use the Variable instance as a Proposition and can have VariableOperators
 * passed as Proposition arguments to logical operators. Notice the lack of use:
 * $rb['q']->equalTo() in order to pass a proposition to logical operators.
 *
 * @package Ruler\Test\Functional
 */
class RulerPropositionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing rules like this:
     *
     * $rule = $rb->create(
     *      new \Ruler\Operator\EqualTo(
     *          $rb->logicalNot(
     *              $rb->logicalAnd(
     *                  $rb['p'],
     *                  $rb['q']
     *              )
     *          ),
     *          $rb->logicalOr(
     *              $rb->logicalNot(
     *                  $rb['p']
     *              ),
     *              $rb->logicalNot(
     *                  $rb['q']
     *              )
     *          )
     *      )
     * );
     *
     * @dataProvider truthTableTwo
     */
    public function testDeMorgan($p, $q)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p', 'q'));

        $left = $rb->logicalNot(
            $rb->logicalAnd(
                $rb['p'],
                $rb['q']
            )
        );

        $right = $rb->logicalOr(
            $rb->logicalNot(
                $rb['p']
            ),
            $rb->logicalNot(
                $rb['q']
            )
        );

        $this->assertEquals(
            $left->evaluate($context),
            $right->evaluate($context)
        );

        $rule = $rb->create(
            new EqualTo($left, $right)
        );

        $this->assertTrue($rule->evaluate($context));
    }

    /**
     * @dataProvider truthTableTwo
     */
    public function testDeMorganTwo($p, $q)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('p', 'q'));

        $left = $rb->create(
            $rb->logicalNot(
                $rb->logicalOr(
                    $rb['p'],
                    $rb['q']
                )
            )
        );

        $right = $rb->create(
            $rb->logicalAnd(
                $rb->logicalNot(
                    $rb['p']
                ),
                $rb->logicalNot(
                    $rb['q']
                )
            )
        );

        $this->assertEquals(
            $left->evaluate($context),
            $right->evaluate($context)
        );

        $rule = $rb->create(
            new EqualTo($left, $right)
        );

        $this->assertTrue($rule->evaluate($context));
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
                    $rb['p'],
                    $rb['q']
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalOr(
                    $rb['q'],
                    $rb['p']
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
                    $rb['p'],
                    $rb['q']
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalAnd(
                    $rb['q'],
                    $rb['p']
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
                    $rb['p'],
                    $rb->logicalOr(
                        $rb['q'],
                        $rb['r']
                    )
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalOr(
                    $rb->logicalOr(
                        $rb['p'],
                        $rb['q']
                    ),
                    $rb['r']
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
                    $rb['p'],
                    $rb->logicalAnd(
                        $rb['q'],
                        $rb['r']
                    )
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalAnd(
                    $rb->logicalAnd(
                        $rb['p'],
                        $rb['q']
                    ),
                    $rb['r']
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
                    $rb['p'],
                    $rb->logicalOr(
                        $rb['q'],
                        $rb['r']
                    )
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalOr(
                    $rb->logicalAnd(
                        $rb['p'],
                        $rb['q']
                    ),
                    $rb->logicalAnd(
                        $rb['p'],
                        $rb['r']
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
                    $rb['p'],
                    $rb->logicalAnd(
                        $rb['q'],
                        $rb['r']
                    )
                )
            )->evaluate($context),
            $rb->create(
                $rb->logicalAnd(
                    $rb->logicalOr(
                        $rb['p'],
                        $rb['q']
                    ),
                    $rb->logicalOr(
                        $rb['p'],
                        $rb['r']
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
                $rb['p']
            )->evaluate($context),
            $rb->create(
                $rb->logicalNot(
                    $rb->logicalNot(
                        $rb['p']
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
                $rb['p']
            )->evaluate($context),
            $rb->create(
                $rb->logicalOr(
                    $rb['p'],
                    $rb['p']
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
                $rb['p']
            )->evaluate($context),
            $rb->create(
                $rb->logicalAnd(
                    $rb['p'],
                    $rb['p']
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
                    $rb['p'],
                    $rb->logicalNot(
                        $rb['p']
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
                        $rb['p'],
                        $rb->logicalNot(
                            $rb['p']
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
