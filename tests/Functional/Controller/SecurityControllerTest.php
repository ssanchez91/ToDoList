<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\UserFixtures;
use App\Tests\AuthenticationTrait;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;
    use AuthenticationTrait;

    private $client;

    private $userId;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        $this->entityManager->beginTransaction();
        $this->loadFixtures([UserFixtures::class]);
        $this->userId = $this->entityManager->getRepository('App:user')->findOneBy(['username' => 'user_1'])->getId();
    }

    public function testShowLogin()
    {
        $this->client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    public function testLoginWithBadCredentials()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'user_1',
            'password' => 'wrongPassword'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/login');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testLoginSuccess()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'user_1',
            'password' => 'user_1'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }

    public function testLoginWithWrongUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'user_wrong',
            'password' => 'password'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/login');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testLogout()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:User')->find($this->userId));
        $this->client->request('GET', '/logout');
        $this->assertTrue($this->client->getResponse()->isRedirection());
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testLoginWithWrongToken()
    {
        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');
        $this->client->request(
            'POST',
            '/login',
            [
                'username' => 'user_1',
                'password' => '',
                '_token' => $csrfToken,
            ]
        );
        $this->assertResponseRedirects('/login');
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }
    
    public function testLoginToAdminPageWithUser()
    {
        $this->login($this->client, $this->entityManager->getRepository('App:User')->find($this->userId));
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testRedirectResponseonAuthenticationSuccess()
    {
        $this->client->request('GET', '/tasks');
        $crawler = $this->client->followRedirect();

        $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'user_1',
            'password' => 'user_1'
        ]);
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirection());
        $crawler = $this->client->followRedirect();
        $this->assertSame(0, $crawler->filter('div.alert.alert-danger')->count());
    }
}
