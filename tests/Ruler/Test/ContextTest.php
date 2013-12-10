<?php

namespace Ruler\Test;

use Ruler\Context;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $facts = array(
            'name' => 'Mint Chip',
            'type' => 'Ice Cream',
            'delicious' => function () {
                return true;
            }
        );

        $context = new Context($facts);

        $this->assertTrue(isset($context['name']));
        $this->assertEquals('Mint Chip', $context['name']);

        $this->assertTrue(isset($context['type']));
        $this->assertEquals('Ice Cream', $context['type']);

        $this->assertTrue(isset($context['delicious']));
        $this->assertTrue($context['delicious']);
    }
}
