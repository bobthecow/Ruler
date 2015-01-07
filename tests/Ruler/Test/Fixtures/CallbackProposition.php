<?php

namespace Ruler\Test\Fixtures;

use Ruler\Proposition;
use Ruler\Context;
use Ruler\Value;

class CallbackProposition implements Proposition
{
    private $callback;

    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('CallbackProposition expects a callable argument');
        }

        $this->callback = $callback;
    }

    public function evaluate(Context $context)
    {
        return call_user_func($this->callback, $context);
    }

    /**
     * @param Context $context
     *
     * @return Value
     */
    public function prepareValue(Context $context)
    {
        return new Value($this->evaluate($context));
    }


}
