<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.02.18
 * Time: 12:54
 */
namespace AppBundle\Service;

use AppBundle\Entity\Circle;
use AppBundle\Entity\Note;
use AppBundle\Entity\Sector;
use AppBundle\Entity\User;
use AppBundle\Model\ListNotesModel;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class NoteManager
 * @package AppBundle\Service
 */
class NoteManager
{

    /**
     * @var ObjectManager $em
     */
    protected $em;

    /**
     * @var ContainerInterface $container
     * */
    protected $container;


    /**
     * @param ContainerInterface $container
     * @param ObjectManager $em
     */
    public function __construct(ContainerInterface $container, ObjectManager $em)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function updateListNotesModelByUser(ListNotesModel $listNotesModel, User $user)
    {
        $circles = $this->em->getRepository(Circle::class)->findBy(['user' => $user]);
        $incomingNotes = $this->em->getRepository(Note::class)->findBy(['user' => $user, 'category' => null]);



        $listNotesModel
            ->setCircles($circles)
            ->setIncomingNotes($incomingNotes)
        ;

        return true;
    }

}
