<?php
/**
 * Pop PHP Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.TXT.
 * It is also available through the world-wide-web at this URL:
 * http://www.popphp.org/LICENSE.TXT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@popphp.org so we can send you a copy immediately.
 *
 * @category   Pop
 * @package    Pop_Filter
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Filter\Rule;

use Pop\Locale\Locale;

/**
 * @category   Pop
 * @package    Pop_Filter
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class BetweenInclude extends AbstractRule
{

    /**
     * Method to evaluate the rule
     *
     * @param  mixed $input
     * @return boolean
     */
    public function evaluate($input = null)
    {
        // Set the input, if passed
        if (null !== $input) {
            $this->_input = $input;
        }

        $nums = explode('|', $this->_value);

        // Set the default message
        if ($this->_condition) {
            $this->_defaultMessage = Locale::factory()->__('The value must be between or equal to %1 and %2.', $nums);
        } else {
            $this->_defaultMessage = Locale::factory()->__('The value must not be between or equal to %1 and %2.', $nums);
        }

        // Evaluate the input against the rule
        if ((($this->_input >= $nums[0]) && ($this->_input <= $nums[1])) == $this->_condition) {
            $this->_result = true;
        }

        return $this->_result;
    }

}