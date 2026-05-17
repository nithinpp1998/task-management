<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function find(int $id)
    {
        return User::findOrFail($id);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return User::create($data);
    }

    public function update(int $id, array $data)
    {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }

    public function updatePassword(int $id, string $password)
    {
        $user = $this->find($id);
        $user->update(['password' => Hash::make($password)]);
        return $user;
    }

    public function getUsersByRole(string $role)
    {
        return User::where('role', $role)->get();
    }

    public function delete(int $id)
    {
        $user = $this->find($id);
        return $user->delete();
    }

    public function unverifyEmail(int $id)
    {
        $user = $this->find($id);
        $user->email_verified_at = null;
        $user->save();
        return $user;
    }
}
