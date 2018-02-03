<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;


class LoadUsersData extends Fixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const REFERENCE_PREFIX = 'app_user_';

    const DATA_LIST = [
        [
            'email' => 'user01@mail.com',
            'username' => 'user0com1mal',
            'password' => 'qwe123',
            'roles' => [],
        ],
        [
            'email' => 'user02@mail.com',
            'username' => 'user0com2mal',
            'password' => 'qwe123',
            'roles' => [],
        ],
        [
            'email' => 'user03@mail.com',
            'username' => 'user0com3mal',
            'password' => 'qwe123',
            'roles' => [],
        ],
        [
            'email' => 'user04@mail.com',
            'username' => 'user0com4mal',
            'password' => 'qwe123',
            'roles' => [],
        ],
        [
            'email' => 'user05@mail.com',
            'username' => 'user0com5mal',
            'password' => 'qwe123',
            'roles' => [],
        ],
    ];


    /**
     * @var ContainerInterface
     */
    private $container;


    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        foreach (self::DATA_LIST as $i => $data) {
            $user = new User();
            $pass = $this->container->get('security.password_encoder')->encodePassword($user, $data['password']);

            $user
                ->setUsername($data['username'])
                ->setEmail($data['email'])
                ->setPassword($pass)
                ->setEnabled(true)
                ->setRoles($data['roles']);

            $manager->persist($user);

            if ($this->referenceRepository) {
                $this->addReference(self::REFERENCE_PREFIX . $i, $user);
            }

            $manager->flush();
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}