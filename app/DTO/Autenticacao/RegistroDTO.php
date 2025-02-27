<?php

namespace App\DTO\Autenticacao;

use Illuminate\Support\Facades\Hash;

readonly class RegistroDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    )
    {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: Hash::make($data['password'])
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
