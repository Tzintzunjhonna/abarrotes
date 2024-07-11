<?php

namespace ABARROTES\Admin\User\Domain\ValueObjects;

use InvalidArgumentException;

class UserEmail
{
    private string $value;

    public function __construct(string $email)
    {
        $this->validate($email);
        $this->value = $email;
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param string $email
     * @throws InvalidArgumentException
     */
    private function validate(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email is not valid: {$email}", 422);
        }
    }
}
