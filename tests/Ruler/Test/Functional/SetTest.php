<?php

namespace Ruler\Test\Functional;

use Ruler\RuleBuilder;
use Ruler\Context;

class SetTest extends \PHPUnit_Framework_TestCase
{
    public function testComplicated()
    {
        $rb = new RuleBuilder();
        $context = new Context(array(
            'expected' => 'a',
            'foo' => array('a', 'z'),
            'bar' => array('z', 'b'),
            'baz' => array('a', 'z', 'b', 'q'),
            'bob' => array('a', 'd'),
        ));

        $this->assertTrue(
            $rb->create(
                $rb['foo']->intersect(
                    $rb['bar']->symmetricDifference($rb['baz'])
                )->setContains($rb['expected'])
            )->evaluate($context)
        );

        $this->assertTrue(
            $rb->create(
                $rb['bar']->union(
                    $rb['bob']
                )->containsSubset($rb['foo'])
            )->evaluate($context)
        );
    }

    public function setUnion()
    {
        return array(
            array(
                array('a', 'b', 'c'),
                array(),
                array('a', 'b', 'c'),
            ),
            array(
                array(),
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
            ),
            array(
                array(),
                array(),
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('d', 'e', 'f'),
                array('a', 'b', 'c', 'd', 'e', 'f'),
            ),
            array(
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
            ),
            array(
                array('a', 'b', 'c'),
                array('b', 'c'),
                array('a', 'b', 'c'),
            ),
            array(
                array('b', 'c'),
                array('b', 'd'),
                array('b', 'c', 'd'),
            ),
        );
    }

    /**
     * @dataProvider setUnion
     */
    public function testUnion($a, $b, $expected)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('a', 'b', 'expected'));
        $this->assertTrue(
            $rb->create(
                $rb['expected']->equalTo(
                    $rb['a']->union($rb['b'])
                )
            )->evaluate($context)
        );
    }

    public function setIntersect()
    {
        return array(
            array(
                array('a', 'b', 'c'),
                array(),
                array(),
            ),
            array(
                array(),
                array('a', 'b', 'c'),
                array(),
            ),
            array(
                array(),
                array(),
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('d', 'e', 'f'),
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
            ),
            array(
                array('a', 'b', 'c'),
                array('b', 'c'),
                array('b', 'c'),
            ),
            array(
                array('b', 'c'),
                array('b', 'd'),
                array('b'),
            ),
        );
    }

    /**
     * @dataProvider setIntersect
     */
    public function testIntersect($a, $b, $expected)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('a', 'b', 'expected'));
        $this->assertTrue(
            $rb->create(
                $rb['expected']->equalTo(
                    $rb['a']->intersect($rb['b'])
                )
            )->evaluate($context)
        );
    }

    public function setComplement()
    {
        return array(
            array(
                array('a', 'b', 'c'),
                array(),
                array('a', 'b', 'c'),
            ),
            array(
                array(),
                array('a', 'b', 'c'),
                array(),
            ),
            array(
                array(),
                array(),
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('d', 'e', 'f'),
                array('a', 'b', 'c'),
            ),
            array(
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('b', 'c'),
                array('a'),
            ),
            array(
                array('b', 'c'),
                array('b', 'd'),
                array('c'),
            ),
        );
    }

    /**
     * @dataProvider setComplement
     */
    public function testComplement($a, $b, $expected)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('a', 'b', 'expected'));
        $this->assertTrue(
            $rb->create(
                $rb['expected']->equalTo(
                    $rb['a']->complement($rb['b'])
                )
            )->evaluate($context)
        );
    }

    public function setSymmetricDifference()
    {
        return array(
            array(
                array('a', 'b', 'c'),
                array(),
                array('a', 'b', 'c'),
            ),
            array(
                array(),
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
            ),
            array(
                array(),
                array(),
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('d', 'e', 'f'),
                array('a', 'b', 'c', 'd', 'e', 'f'),
            ),
            array(
                array('a', 'b', 'c'),
                array('a', 'b', 'c'),
                array(),
            ),
            array(
                array('a', 'b', 'c'),
                array('b', 'c'),
                array('a'),
            ),
            array(
                array('b', 'c'),
                array('b', 'd'),
                array('c', 'd'),
            ),
        );
    }

    /**
     * @dataProvider setSymmetricDifference
     */
    public function testSymmetricDifference($a, $b, $expected)
    {
        $rb = new RuleBuilder();
        $context = new Context(compact('a', 'b', 'expected'));
        $this->assertTrue(
            $rb->create(
                $rb['expected']->equalTo(
                    $rb['a']->symmetricDifference($rb['b'])
                )
            )->evaluate($context)
        );
    }
}
