<?php

namespace App\User\Application\DTO;

class RegisterUserDTO
{
    public string $email;
    public string $plainPassword;
    public string $phoneNumber;

    public function __construct(string $email, string $plainPassword, string $phoneNumber)
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->phoneNumber = $phoneNumber;
    }
}
