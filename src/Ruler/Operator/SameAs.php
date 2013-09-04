<?php

namespace Ruler\Operator;

use Ruler\Operator\ComparisonOperator;
use Ruler\Context;

class SameAs extends ComparisonOperator
{
    public function evaluate(Context $context)
    {
        return $this->left->prepareValue($context)->sameAs($this->right->prepareValue($context));
    }
}