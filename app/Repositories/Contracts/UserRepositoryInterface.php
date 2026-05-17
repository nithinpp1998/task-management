<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function all();
    public function find(int $id);
    public function findByEmail(string $email);
    public function create(array $data);
    public function update(int $id, array $data);
    public function updatePassword(int $id, string $password);
    public function unverifyEmail(int $id);
    public function delete(int $id);
    public function getUsersByRole(string $role);
}
