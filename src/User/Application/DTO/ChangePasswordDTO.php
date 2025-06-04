<?php

namespace App\User\Application\DTO;

class ChangePasswordDTO
{
    public function __construct(
        public readonly string $oldPassword,
        public readonly string $newPassword
    ) {}
}
