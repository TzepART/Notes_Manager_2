<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Note;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;


class LoadNotesData extends Fixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const REFERENCE_PREFIX = 'app_note_';


    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Generator
     */
    private $faker;


    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->faker = FakerFactory::create();
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 80; $i++) {
            $note = new Note();
            $userNumber = $i%7;

            $note->setTitle($this->faker->word)
                ->setText($this->faker->text(200))
                ->setUser($this->getReference(LoadUsersData::REFERENCE_PREFIX . $userNumber));

            $manager->persist($note);

            if ($this->referenceRepository) {
                $this->addReference(self::REFERENCE_PREFIX . $i, $note);
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
        return 2;
    }
}