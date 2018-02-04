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
        $this->container = static::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();
        $this->faker = FakerFactory::create();
    }

    /**
     * @param bool $isMobile
     * @return null
     */
    private function logIn($isMobile = true)
    {
        if($isMobile){
            $updHeaders = ['HTTP_USER_AGENT' => CommonTestsClass::MOBILE_USER_AGENT];

            $crawler = $this->client->request('GET', '/kosmogonki/login',[],[],$updHeaders);

            $form = $crawler->filter('#login_form')->form();

            $form['_username'] = 'user08@mail.com';
            $form['_password'] = 'qwe123';

            $this->client->submit($form);
            $this->client->followRedirect();

            $this->container = static::$kernel->getContainer();
            $this->em = $this->container->get('doctrine')->getManager();

        }else{
            $updHeaders = [];

            $crawler = $this->client->request('GET', '/kosmogonki/login',[],[],$updHeaders);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
            $countLoginForm = $crawler->filter('input[type="submit"]')->count();
            $this->assertEquals($countLoginForm,0);
        }

        return null;
    }

    private function setUpByClient(Client $client)
    {
        $this->client = $client;
        $this->container = static::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();
    }
}
