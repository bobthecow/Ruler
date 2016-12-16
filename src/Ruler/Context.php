<?php

/*
 * This file is part of the Ruler package, an OpenSky project.
 *
 * (c) 2011 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ruler;

/**
 * Ruler Context.
 *
 * The Context contains facts with which to evaluate a Rule or other Proposition.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 */
class Context extends \Pimple
{

    /**
     * Context constructor.
     *
     * Optionally, bootstrap the context by passing an array of fact names and
     * values.
     *
     * @param array $values (default: array())
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);
    }

    /**
     * @see Pimple::offsetGet()
     *
     * Adds support for VariableMethod.
     */
    public function &offsetGet($id)
    {
        if (!array_key_exists($id, $this->values)) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        $isFactory = is_object($this->values[$id]) && method_exists($this->values[$id], '__invoke');
        if ($isFactory) {
            $result = $this->values[$id]($this);
        } else {
            $result =& $this->values[$id];
        }

        return $result;
    }
}