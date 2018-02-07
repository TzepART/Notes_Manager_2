<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\CommonTestsClass;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Faker\Factory as FakerFactory;
use Faker\Generator;

/**
 * Class SecurityControllerTest
 * @package AppBundle\Tests\Controller
 */
class SecurityControllerTest extends WebTestCase
{
    const ERROR_PASSWORD = "Неверный пароль";
    const ERROR_EXIST_LOGIN = "fos_user.username.already_used";
    const ERROR_EXIST_EMAIL = "fos_user.email.already_used";
    const ERROR_LOGIN = "Invalid credentials.";

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Generator
     */
    private $faker;


    public function setUp()
    {
        $this->client = static::createClient();
        $this->faker = FakerFactory::create();
    }


    public function testSuccessRegisterUser2()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUsersData',
        ));

        $crawler = $this->client->request('GET', '/register/');

        $form = $crawler->filter('#register_form')->form();

        $email = $this->faker->email;
        $userName = $this->faker->userName;

        $form = $this->setUserData($form,$email,$userName);

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();
        $this->assertEquals(301, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        return [$email,$userName];
    }

    /**
     * @depends testSuccessRegisterUser2
     * */
    public function testFailRegisterUserByExistEmail2($data)
    {
        $crawler = $this->client->request('GET', '/register/');
        $email = $data[0];
        $userName = $data[1]."_2";

        $form = $crawler->filter('#register_form')->form();

        $form = $this->setUserData($form,$email,$userName,self::ERROR_EXIST_EMAIL);

        $this->client->submit($form);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(strpos($this->client->getResponse()->getContent(),self::ERROR_EXIST_EMAIL) > 0);
        return $data;
    }

    /**
     * @depends testFailRegisterUserByExistEmail2
     * */
    public function testFailRegisterUserByExistLogin2($data)
    {
        $email = $data[0]."444";
        $userName = $data[1];

        $crawler = $this->client->request('GET', '/register/');

        $form = $crawler->filter('#register_form')->form();

        $form = $this->setUserData($form,$email,$userName,self::ERROR_EXIST_LOGIN);

        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(strpos($this->client->getResponse()->getContent(),self::ERROR_EXIST_LOGIN) > 0);
    }

    public function testFailRegisterUserByPassword2()
    {
        $crawler = $this->client->request('GET', '/register/');

        $form = $crawler->filter('#register_form')->form();

        $email = $this->faker->email;
        $userName = $this->faker->userName;

        $form = $this->setUserData($form,$email,$userName,self::ERROR_PASSWORD);

        $this->client->submit($form);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(strpos($this->client->getResponse()->getContent(),self::ERROR_PASSWORD) > 0);

    }

    public function testFailLoginNotExistsUserByUserName()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUsersData',
        ));
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->filter('#login_form')->form();

        $form['_username'] = 'userNotExist';
        $form['_password'] = 'qwe123';

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();
        $this->assertTrue(strpos($this->client->getResponse()->getContent(),self::ERROR_LOGIN) > 0);

    }


    public function testFailLoginNotCorrectPass()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUsersData',
        ));
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->filter('#login_form')->form();

        $form['_username'] = 'user0com1mal';
        $form['_password'] = 'qwe123NotCorrect';

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();
        $this->assertTrue(strpos($this->client->getResponse()->getContent(),self::ERROR_LOGIN) > 0);
    }

    public function testSuccessLogin()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUsersData',
        ));
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->filter('#login_form')->form();

        $form['_username'] = 'user0com1mal';
        $form['_password'] = 'qwe123';

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        return $this->client;
    }


    /**
     * @depends testSuccessLogin
     * @var Client $client
     * */
    public function testSuccessLogout(Client $client)
    {
        $client->request('GET', '/logout');

        $this->assertTrue($client->getResponse()->isRedirect());

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }


    protected function setUserData($form, $email, $userName, $errorType = ""){

        switch ($errorType){
            case self::ERROR_EXIST_EMAIL:
                $form['fos_user_registration_form[username]'] = $userName;
                $form['fos_user_registration_form[email]'] = $email;
                $form['fos_user_registration_form[plainPassword][first]'] = 'qwe123';
                $form['fos_user_registration_form[plainPassword][second]'] = 'qwe123';
                break;
            case self::ERROR_EXIST_LOGIN:
                $form['fos_user_registration_form[username]'] = $userName;
                $form['fos_user_registration_form[email]'] = $email;
                $form['fos_user_registration_form[plainPassword][first]'] = 'qwe123';
                $form['fos_user_registration_form[plainPassword][second]'] = 'qwe123';
                break;
            case self::ERROR_PASSWORD:
                $form['fos_user_registration_form[username]'] = $userName;
                $form['fos_user_registration_form[email]'] = $email;
                $form['fos_user_registration_form[plainPassword][first]'] = 'qwe123';
                $form['fos_user_registration_form[plainPassword][second]'] = 'qwe123123';
                break;
            default:
                $form['fos_user_registration_form[username]'] = $userName;
                $form['fos_user_registration_form[email]'] = $email;
                $form['fos_user_registration_form[plainPassword][first]'] = 'qwe123';
                $form['fos_user_registration_form[plainPassword][second]'] = 'qwe123';
                break;
        }

        return $form;
    }


}