<?php

namespace App\Infrastructure\Http\Symfony\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTodoDto
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\NotBlank]
        public string $name
    )
    {
    }
}