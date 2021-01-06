<?php

namespace Tests\Unit\Entity;


use App\Entity\Task;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    /**
     * constant represent title task
     */
    const TITLE_TASK = "This is the title of the task.";

    /**
     * constant represent content task
     */
    const CONTENT_TASK = "This is the content of the task.";
    
    /**
     * testEntityTask function
     *
     * @return void
     */
    public function testEntityTask()
    {
        $task = new Task();

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals(null, $task->getId());
        $this->assertEquals(null, $task->getTitle());
        $this->assertEquals(null, $task->getContent());
        $this->assertInstanceOf(DateTime::class, $task->getCreatedAt());
        $this->assertNotTrue($task->isDone());

        $task->setTitle(SELF::TITLE_TASK);
        $this->assertEquals(SELF::TITLE_TASK, $task->getTitle());
        $task->setContent(SELF::CONTENT_TASK);
        $this->assertEquals(SELF::CONTENT_TASK, $task->getContent());
        $task->setCreatedAt(new DateTime());
        $this->assertInstanceOf(DateTime::class, $task->getCreatedAt());
        $task->toggle(true);
        $this->assertTrue($task->isDone());

        $user = new User();
        $task->setAuthor($user);
        $this->assertEquals($user, $task->getAuthor());
        
    }



    
}