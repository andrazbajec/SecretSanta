<?php

namespace Controllers;

class Authentication
{
    private $user = null;

    /**
     * @param string|null $name
     * @param string|null $username
     * @param string|null $password
     *
     */
    public function register(?string $name, ?string $username, ?string $password)
    {
        if (isset($name) && isset($username) && isset($password)) {
            $db = new Database();
            $db->connect();
        } else {
            return null;
        }
    }
}