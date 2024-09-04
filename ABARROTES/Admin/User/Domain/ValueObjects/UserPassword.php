<?php

namespace abarrotes\Admin\User\Domain\ValueObjects;

use InvalidArgumentException;

class UserPassword
{
    private string $value;

    public function __construct(string $password = null)
    {
        $this->validate($password);
        $this->value = $password;
    }

    public function value():? string
    {
        return $this->value;
    }

    /**
     * @param string $email
     * @throws InvalidArgumentException
     */
    private function validate(string $password = null): void
    {
        if ($password) {
            //throw new InvalidArgumentException("Name must be: {$name}", 422);
        }
    }
    
}
