<?php

namespace ABARROTES\Admin\User\Domain\Entities;

class User
{
    private $id;
    private $name;
    private $firstName;
    private $lastName;
    private $phone;
    private $email;
    private $password;
    private $role;
    private $status;
    private string $token;

    public function __construct($id, $name, $firstName, $lastName, $phone, $email, $role, $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->email = $email;
        $this->role = $role;
        $this->status = $status;
        $this->token = "";
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function firstName()
    {
        return $this->firstName;
    }

    public function lastName()
    {
        return $this->lastName;
    }

    public function phone()
    {
        return $this->phone;
    }

    public function email()
    {
        return $this->email;
    }

    public function role()
    {
        return $this->role;
    }

    public function status()
    {
        return $this->status;
    }

    public function password()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setToken(string $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function toArray()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'firstName' => $this->firstName(),
            'lastName' => $this->lastName(),
            'phone' => $this->phone(),
            'email' => $this->email(),
            'role' => $this->role(),
            'status' => $this->status(),
            'token' => $this->getToken()
        ];
    }
}
