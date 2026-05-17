<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_user_is_admin(): void
    {
        $user = new User();
        $user->role = 'admin';
        
        $this->assertTrue($user->isAdmin());
    }
    
    public function test_user_is_not_admin(): void
    {
        $user = new User();
        $user->role = 'user';
        
        $this->assertFalse($user->isAdmin());
    }
}
