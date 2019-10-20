<?php
namespace Core;

abstract class Site
{
    public $name;
    protected $config;
    protected $parser;

    abstract public function getLoginData(): array;
    abstract public function getLoginHeaders(): array;
    abstract public function login();

    public function __construct( array $config, Parser $parser)
    {
        $this->config = $config['sites'][$this->name];
        $this->parser = $parser;
    }

    public function getConfig($name)
    {
        return $this->config[$name];
    }

    public function getLoginUrl(): string
    {
        return $this->config['login']['url'];
    }

    public function parse()
    {
        return $this->parser->parse($this->config['topics']);
    }

}