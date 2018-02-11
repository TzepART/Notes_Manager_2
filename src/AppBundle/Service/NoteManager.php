<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.02.18
 * Time: 12:54
 */

namespace AppBundle\Service;

use AppBundle\Entity\Category;
use AppBundle\Entity\Circle;
use AppBundle\Entity\Note;
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

    /**
     * @param ListNotesModel $listNotesModel
     * @param User $user
     * @return bool
     */
    public function updateListNotesModelByUser(ListNotesModel $listNotesModel, User $user)
    {
        $circles = $this->em->getRepository(Circle::class)->findBy(['user' => $user]);
        $incomingNotes = $this->em->getRepository(Note::class)->findBy(['user' => $user, 'category' => null]);

        if (!$listNotesModel->isSelectedIncomingCategory()) {
            if ($listNotesModel->getSelectNote() instanceof Note) {
                $selectCategory = $listNotesModel->getSelectNote()->getCategory();
                $listNotesModel->setSelectCategory($selectCategory)
                    ->setSelectCircle($selectCategory->getSector()->getCircle());

            } elseif ($listNotesModel->getSelectCategory() instanceof Category) {
                $selectNote = $listNotesModel->getSelectCategory()->getNotes()->first();
                $listNotesModel
                    ->setSelectCircle($listNotesModel->getSelectCategory()->getSector()->getCircle())
                    ->setSelectNote($selectNote);

            } elseif ($listNotesModel->getSelectCircle() instanceof Circle) {
                /** @var Category $selectCategory */
                $selectCategory = $listNotesModel->getSelectCircle()->getSectors()->first()->getCategory();
                $selectNote = $selectCategory->getNotes()->first();
                $listNotesModel
                    ->setSelectCategory($selectCategory)
                    ->setSelectNote($selectNote);
            }else{
                /** @var Circle $selectCircle */
                /** @var Category $selectCategory */
                $selectCircle = $circles[0];
                $selectCategory = $selectCircle->getSectors()->first()->getCategory();
                $selectNote = $selectCategory->getNotes()->first();
                $listNotesModel
                    ->setSelectCircle($selectCircle)
                    ->setSelectCategory($selectCategory)
                    ->setSelectNote($selectNote);
            }
        }

        $listNotesModel
            ->setCircles($circles)
            ->setIncomingNotes($incomingNotes);


        return true;
    }

}
