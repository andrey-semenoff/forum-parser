<?php
namespace Core;

interface Producer
{
    public function send(Message $message);
}