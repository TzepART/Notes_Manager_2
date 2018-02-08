<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Circle;
use AppBundle\Entity\Note;
use AppBundle\Entity\NoteLabel;
use AppBundle\Entity\Sector;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;


class LoadNoteLabelsData extends Fixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const REFERENCE_PREFIX = 'app_note_label_';
    const COUNT_NOTE_LABELS = 220;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var Note[]
     */
    private $notes = [];

    
    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->faker = FakerFactory::create('ru_RU');
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        for($i=0;$i < LoadNotesData::COUNT_NOTES; $i++) {
            $this->notes[] = $this->getReference(LoadNotesData::REFERENCE_PREFIX.$i);
        }

        for($i = 0; $i < self::COUNT_NOTE_LABELS; $i++){
            $noteLabel = new NoteLabel();

            $note = $this->getRandomNote();

            $circles = $note->getUser()->getCircles();
            /** @var Circle $randCircle */
            $randCircle = $circles->get(array_rand($circles->toArray()));

            $sectors = $randCircle->getSectors();

            /** @var Sector $randSector */
            $randSector = $sectors->get(array_rand($sectors->toArray()));

            $angle = rand((int) $randSector->getBeginAngle(),(int) $randSector->getEndAngle());
            $radius = mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax();

            $noteLabel->setNote($note)
                ->setSector($randSector)
                ->setRadius($radius)
                ->setAngle($angle);

            $note->setNoteLabel($noteLabel)
                ->setCategory($randSector->getCategory());

            $manager->persist($noteLabel);
            $manager->persist($note);

            if ($this->referenceRepository) {
                $this->addReference(self::REFERENCE_PREFIX . $i, $noteLabel);
            }

            $manager->flush();
        }
    }

    /**
     * @return Note
     */
    private function getRandomNote()
    {
        $randKey = array_rand($this->notes);

        $randNote = $this->notes[$randKey];
        unset($this->notes[$randKey]);

        return $randNote;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}