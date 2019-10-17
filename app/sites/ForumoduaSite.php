<?php
namespace App;

class ForumoduaSite extends Site
{
    public $name = 'forumodua';

    public function __construct(array $config, Parser $parser)
    {
        parent::__construct($config, $parser);
    }

    public function getLoginData(): array
    {
        return [
            "vb_login_username" => $this->config['login']['username'],
            "vb_login_md5password" => md5($this->config['login']['password']),
            "securitytoken" => "guest",
            "do" => "login",
        ];
    }

    public function getLoginHeaders(): array
    {
        return [
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36",
        ];
    }

    public function login()
    {
        $ch = curl_init($this->getLoginUrl());
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getLoginHeaders());
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->getLoginData()));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}