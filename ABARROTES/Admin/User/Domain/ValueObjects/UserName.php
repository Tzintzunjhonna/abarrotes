<?php

namespace abarrotes\Admin\User\Domain\ValueObjects;

use InvalidArgumentException;

class UserName
{
    private string $value;

    public function __construct(string $name)
    {
        $this->validate($name);
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param string $email
     * @throws InvalidArgumentException
     */
    private function validate(string $name): void
    {
        if (strlen($name) > 255) {
            throw new InvalidArgumentException("Name must be: {$name}", 422);
        }
    }
}
