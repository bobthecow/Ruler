<?php

namespace Ruler\Test;

use Ruler\Set;

class SetTest extends \PHPUnit_Framework_TestCase
{
    public function testNonStringableObject()
    {
        $setExpected = array(
            new \stdClass(),
            new \stdClass()
        );
        $set = new Set($setExpected);
        $this->assertEquals(1, count($set));
    }
}
