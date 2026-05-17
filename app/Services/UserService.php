<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function createUser(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function getUsersByRole(string $role)
    {
        return $this->userRepository->getUsersByRole($role);
    }
    
    public function findByEmail(string $email)
    {
        return $this->userRepository->findByEmail($email);
    }
    
    public function updatePassword(int $id, string $password)
    {
        return $this->userRepository->updatePassword($id, $password);
    }

    public function updateProfile(int $id, array $data)
    {
        $user = $this->userRepository->find($id);
        
        // Emulate Laravel Breeze's email verification logic when email changes
        if (isset($data['email']) && $user->email !== $data['email']) {
            $this->userRepository->unverifyEmail($id);
        }

        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(int $id)
    {
        return $this->userRepository->delete($id);
    }
}
