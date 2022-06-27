<?php

namespace Ruler\Test;

use PHPUnit\Framework\TestCase;
use Ruler\Set;
use Ruler\Test\Fixtures\toStringable;
use Ruler\Value;

class SetTest extends TestCase
{
    public function testNonStringableObject()
    {
        $setExpected = [
            new \stdClass(),
            new \stdClass(),
        ];
        $set = new Set($setExpected);
        $this->assertEquals(2, \count($set));
    }

    public function testObjectUniqueness()
    {
        $objectA = new \stdClass();
        $objectA->something = 'else';
        $objectB = new \stdClass();
        $objectB->foo = 'bar';

        $set = new Set([
            $objectA,
            $objectB,
        ]);

        $this->assertEquals(2, \count($set));
        $this->assertTrue($set->setContains(new Value($objectA)));
        $this->assertTrue($set->setContains(new Value($objectB)));
        $this->assertFalse($set->setContains(new Value(false)));
    }

    public function testStringable()
    {
        $set = new Set([
            $one = new toStringable(1),
            $two = new toStringable(2),
            $too = new toStringable(2),
        ]);

        $this->assertEquals(2, \count($set));
        $this->assertTrue($set->setContains(new Value($one)));
        $this->assertTrue($set->setContains(new Value($two)));
        $this->assertFalse($set->setContains(new Value($too)));
        $this->assertFalse($set->setContains(new Value(2)));
    }
}
