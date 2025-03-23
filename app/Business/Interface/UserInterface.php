<?php declare(strict_types=1);
namespace App\Business\Interface;

use App\Business\Enum\UserRole;

interface UserInterface
{
    public function getRole(): UserRole;
    public function getId(): int;
}
