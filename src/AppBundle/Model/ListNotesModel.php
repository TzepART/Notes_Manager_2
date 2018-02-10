<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 10/02/2018
 * Time: 13:48
 */

namespace AppBundle\Model;


use AppBundle\Entity\Category;
use AppBundle\Entity\Circle;
use AppBundle\Entity\Note;

class ListNotesModel
{

    /**
     * @var Note|null
     */
    private $selectNote = null;


    /**
     * @var Category|null
     */
    private $selectCategory = null;


    /**
     * @var Circle|null
     */
    private $selectCircle = null;

    /**
     * @var boolean
     */
    private $selectedIncomingCategory = false;

    /**
     * @var Circle[]
     */
    private $circles;

    /**
     * @var Note[]
     */
    private $incomingNotes;

    /**
     * @return Note|null
     */
    public function getSelectNote()
    {
        return $this->selectNote;
    }

    /**
     * @param Note|null $selectNote
     * @return $this
     */
    public function setSelectNote($selectNote)
    {
        if($selectNote instanceof Note){
            $selectNote->setSelected(true);
            if(!($selectNote->getCategory() instanceof Category)){
                $this->setSelectedIncomingCategory(true);
            }
        }
        $this->selectNote = $selectNote;
        return $this;
    }

    /**
     * @return Category|null
     */
    public function getSelectCategory()
    {
        return $this->selectCategory;
    }

    /**
     * @param Category|null $selectCategory
     * @return $this
     */
    public function setSelectCategory($selectCategory)
    {
        if($selectCategory instanceof Category){
            $selectCategory->setSelected(true);
        }
        $this->selectCategory = $selectCategory;
        return $this;
    }

    /**
     * @return Circle|null
     */
    public function getSelectCircle()
    {
        return $this->selectCircle;
    }

    /**
     * @param Circle|null $selectCircle
     * @return $this
     */
    public function setSelectCircle($selectCircle)
    {
        if($selectCircle instanceof Circle){
            $selectCircle->setSelected(true);
        }
        $this->selectCircle = $selectCircle;
        return $this;
    }

    /**
     * @return Circle[]
     */
    public function getCircles()
    {
        return $this->circles;
    }

    /**
     * @param Circle[] $circles
     * @return $this
     */
    public function setCircles($circles)
    {
        $this->circles = $circles;
        return $this;
    }

    /**
     * @return Note[]
     */
    public function getIncomingNotes()
    {
        return $this->incomingNotes;
    }

    /**
     * @param Note[] $incomingNotes
     * @return $this
     */
    public function setIncomingNotes($incomingNotes)
    {
        $this->incomingNotes = $incomingNotes;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSelectedIncomingCategory()
    {
        return $this->selectedIncomingCategory;
    }

    /**
     * @param bool $selectedIncomingCategory
     * @return $this
     */
    public function setSelectedIncomingCategory($selectedIncomingCategory)
    {
        $this->selectedIncomingCategory = $selectedIncomingCategory;
        return $this;
    }

}