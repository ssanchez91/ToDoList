<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\TaskFixtures;
use App\DataFixtures\UserFixtures;
use App\Tests\AuthenticationTrait;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    const USER_ID = 1;
    const ADMIN_ID = 5;
    const TASK_ID = 8;
    const ANONYMOUS_TASK_ID = 2;

    use FixturesTrait;
    use AuthenticationTrait;

    /**
     * client variable
     *
     * @var KernelBrowser
     */
    private $client;

    private $entityManager;

    private $userId;

    private $adminId;

    private $task;

    private $anonymousTask;


    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        $this->entityManager->beginTransaction();
        $this->loadFixtures([UserFixtures::class, TaskFixtures::class]);
        $this->userId = $this->entityManager->getRepository('App:user')->findOneBy(['username' => 'user_1'])->getId();
        $this->adminId = $this->entityManager->getRepository('App:user')->findOneBy(['username' => 'user_5'])->getId(); 
        $this->task = $this->entityManager->getRepository('App:Task')->findBy(['Author' => $this->userId])[0];
        $this->anonymousTask = $this->entityManager->getRepository('App:Task')->findBy(['Author' => null])[0];
    }

    public function testListTasks()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:user')->find($this->userId));
        $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();       
    }

    public function testCreateTask()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:user')->find($this->userId));
        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'Voici une nouvelle tâche',
            'task[content]' => 'Ceci est la description de la nouvelle tâche'
        ]);

        $this->client->submit($form);
        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals("La tâche a bien été ajoutée.", current($flashes['success']));
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEditTask()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:User')->find($this->userId));       

        $crawler = $this->client->request('GET', '/tasks'.'/'.$this->task->getId().'/edit');
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => $this->task->getTitle(),
            'task[content]' => $this->task->getContent().' + la modification.'
        ]);

        $this->client->submit($form);
        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals("La tâche a bien été modifiée.", current($flashes['success']));
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');

        $taskUpdated = $this->entityManager->getRepository('App:Task')->find($this->task->getId());
        $this->assertSame($this->task->getContent().' + la modification.', $taskUpdated->getContent());
    }

    public function testToggleTaskDone()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:User')->find($this->userId));
        $this->client->request('GET', '/tasks/'.$this->task->getId().'/toggle');
        
        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $title =$this->task->getTitle();
        $this->assertEquals("La tâche $title a bien été marquée comme faite.", current($flashes['success']));
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testToggleTaskToDo()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:User')->find($this->userId));
        $this->task->toggle(!$this->task->isDone());

        $this->client->request('GET', '/tasks/'.$this->task->getId().'/toggle');        
        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $title =$this->task->getTitle();
        $this->assertEquals("La tâche $title a bien été marquée comme non terminée.", current($flashes['success']));
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
    
    public function testDeleteTaskSuccess()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:User')->find($this->userId));
        $this->client->request('GET', '/tasks/'.$this->task->getId().'/delete');
     
        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals("La tâche a bien été supprimée.", current($flashes['success']));
     
        $this->assertResponseRedirects('/tasks', Response::HTTP_FOUND);
    }

    public function testDeleteTaskForbidden()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:User')->find($this->adminId));
        $this->client->request('GET', '/tasks/'.$this->task->getId().'/delete');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN, 'Vous n\'êtes pas autorisé à supprimer cette tâche.');
    }

    public function testDeleteTaskAnonymousByUser()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:User')->find($this->userId));
        $this->client->request('GET', '/tasks/'.$this->anonymousTask->getId().'/delete');       
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN, 'Vous n\'êtes pas autorisé à supprimer cette tâche.');
    }

    public function testDeleteTaskAnonymousByAdmin()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:User')->find($this->adminId));
        $this->client->request('GET', '/tasks/'.$this->anonymousTask->getId().'/delete');       
        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals("La tâche a bien été supprimée.", current($flashes['success']));
        
        $this->assertResponseRedirects('/tasks', Response::HTTP_FOUND);
    }    


    //  /**
    //  * run after test wich use entityManager.
    //  *
    //  * @return void
    //  */
    // protected function tearDown(): void
    // {
    //     parent::tearDown();
    //     $this->entityManager->close();
    //     $this->entityManager = null;
    // }
}
