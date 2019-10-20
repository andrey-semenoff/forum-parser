<?php
namespace Core;

interface Parser
{
    public function __construct(Producer $producer);
    public function parse(array $topics);
}