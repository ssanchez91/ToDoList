<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\TaskFixtures;
use App\DataFixtures\UserFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use FixturesTrait;

    private $client;

    private $entityManager;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        $this->entityManager->beginTransaction();
        $this->loadFixtures([UserFixtures::class, TaskFixtures::class]);
    }
    
    public function testListUsers()
    {        
        $crawler = $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateUser()
    {
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'new_user',
            'user[password][first]' => 'test!1234',
            'user[password][second]' => 'test!1234',
            'user[email]' => 'new_user@yopmail.fr',
            'user[roles]' => 'ROLE_USER'
        ]);
        
        $this->client->submit($form);

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals("L'utilisateur a bien été ajouté.", current($flashes['success']));
        $this->assertResponseRedirects('/users');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }


    public function testEditUser()
    {
        $user = $this->entityManager->getRepository('App:User')->find(2);
        $crawler = $this->client->request('GET', '/users'.'/'.$user->getId().'/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => $user->getUsername(),
            'user[password][first]' => $user->getPassword(),
            'user[password][second]' => $user->getPassword(),
            'user[email]' => 'new_email@yopmail.fr',
            'user[roles]' => $user->getRoles()[0]
        ]);

        $this->client->submit($form);

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals("L'utilisateur a bien été modifié", current($flashes['success']));
        $this->assertResponseRedirects('/users');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');        
    }
}
