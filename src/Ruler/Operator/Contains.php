<?php

namespace Ruler\Operator;

use Ruler\Context;

/**
 * A comparison operator to determine if one variable is contained within another.  This will work for strings, arrays,
 * and Traversable objects.  This operator will return true if the left and right are equal.
 *
 * @author Adam Englander <adam.englander@sellingsource.com>
 */
class Contains extends \Ruler\Operator\ComparisonOperator
{
	/**
	 * Evaluate whether the first value contains the second value in the current Context.
	 *
	 * @param Context $context Context with which to evaluate this ComparisonOperator
	 *
	 * @return boolean
	 */
	public function evaluate(Context $context)
	{
		$contains = false;

		$left = $this->left->prepareValue($context);
		$leftValue = $this->normalizeValue($left->getValue());
		$right = $this->right->prepareValue($context);
		$rightValue = $this->normalizeValue($right->getValue());

		if ($left->equalTo($right)) {
			$contains = true;
		} else if (is_null($leftValue) || is_null($rightValue)) {
			$contains = false;
		} else if (is_array($leftValue)) {
			if (is_array($rightValue)) {
				$contains = count(array_intersect($leftValue, $rightValue)) == count($rightValue);
			} else {
				$contains = in_array($rightValue, $leftValue);
			}
		} else if (!is_object($leftValue) && !is_object($rightValue)) {
			$contains = (preg_match("/{$rightValue}/", $leftValue) === 1);
		}
		return $contains;
	}

	/**
	 * Take the incoming value and convert traversable objects into arrays
	 * @param $value
	 * @return array|string
	 */
	private function normalizeValue($value) {
		$normalizedValue = null;
		if (is_object($value) && $value instanceof \Traversable) {
			$normalizedValue = array();
			foreach ($value as $item) {
				$normalizedValue[] = $item;
			}
		} else {
			$normalizedValue = $value;
		}
		return $normalizedValue;
	}
}
