<?php

namespace App\Interfaces;

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
