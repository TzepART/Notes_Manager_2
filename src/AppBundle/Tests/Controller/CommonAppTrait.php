<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\CommonTestsClass;
use Sonata\UserBundle\Model\User;
use Symfony\Component\BrowserKit\Client;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Doctrine\ORM\EntityManager;



trait CommonAppTrait
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManager
     */
    protected $em;


    public function setUp()
    {
        $this->client = static::createClient();
        $this->faker = FakerFactory::create();
    }

    /**
     * @return null
     */
    private function logIn()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->filter('#login_form')->form();

        $form['_username'] = 'user0com1mal';
        $form['_password'] = 'qwe123';

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->container = static::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();

        return null;
    }

}
