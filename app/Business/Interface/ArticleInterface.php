<?php declare(strict_types = 1);
namespace App\Business\Interface;

interface ArticleInterface
{
    public function getId(): int;
    public function getAuthorId(): int;
}
