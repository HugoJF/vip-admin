<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 2/9/2018
 * Time: 4:14 PM.
 */

namespace App\Events;

interface IMailableEvent
{
    public function subject();

    public function user();

    public function preHeader();

    public function preLinkMessages();

    public function postLinkMessages();

    public function url();

    public function link();
}
