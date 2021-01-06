<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use ArrayObject;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    const USERNAME = 'user_1';
    const EMAIL = 'user_1@yopmail.fr';
    const PASSWORD = 'Pa$$w0rd*2021';
    const ROLES = ['ROLE_USER'];

    public function testUserEntity()
    {
        $user = new User();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(null, $user->getId());
        $this->assertEquals(null, $user->getUsername());
        $this->assertEquals(null, $user->getEmail());
        $this->assertEquals(null, $user->getPassword());        

        $user->setUsername(SELF::USERNAME);
        $this->assertEquals(SELF::USERNAME, $user->getUsername());
        $user->setEmail(SELF::EMAIL);
        $this->assertEquals(SELF::EMAIL, $user->getEmail());
        $user->setPassword(SELF::PASSWORD);
        $this->assertEquals(SELF::PASSWORD, $user->getPassword());
        $this->assertEquals(SELF::ROLES, $user->getRoles());
        $this->assertEmpty($user->getSalt());

        $task = new Task();
        $user->addTask($task);        
        $this->assertCount(1, $user->getTasks());

        $user->removeTask($task);
        $this->assertCount(0, $user->getTasks());

        $user->eraseCredentials();

    }
}
