<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 2/14/2018
 * Time: 4:32 AM.
 */

namespace App\Interfaces;

interface IOrder
{
    /**
     * Return array with current status of the order
     * ['class'] = CSS Class representing the status
     * ['text']  = Text explaining the status.
     *
     * @return array
     */
    public function status();

    /**
     * Refreshes the order.
     *
     * @return mixed
     */
    public function recheck();

    /**
     * Returns the current step of the order.
     *
     * @return int - Current step
     */
    public function step();

    /**
     * Checks if Order is of type $type.
     *
     * @param $type - The name of the type
     *
     * @return bool - Is of type $type
     */
    public function type($type);

    /**
     * Checks if Order is in a state that allows confirmation generation.
     *
     * @param $flashError - Should the message error be flashed?
     *
     * @return bool - If should generate confirmation
     */
    public function canGenerateConfirmation($flashError = false);
}
