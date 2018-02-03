<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NoteLabel
 *
 * @ORM\Table(name="note_label")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoteLabelRepository")
 */
class NoteLabel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="angle", type="float")
     */
    private $angle;

    /**
     * @var float
     *
     * @ORM\Column(name="radius", type="float")
     */
    private $radius;

    /**
     * @var int
     *
     * @ORM\Column(name="circle", type="int")
     */
    private $circle;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="int")
     */
    private $note;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set angle
     *
     * @param float $angle
     *
     * @return NoteLabel
     */
    public function setAngle($angle)
    {
        $this->angle = $angle;

        return $this;
    }

    /**
     * Get angle
     *
     * @return float
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * Set radius
     *
     * @param float $radius
     *
     * @return NoteLabel
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * Get radius
     *
     * @return float
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * Set circle
     *
     * @param int $circle
     *
     * @return NoteLabel
     */
    public function setCircle($circle)
    {
        $this->circle = $circle;

        return $this;
    }

    /**
     * Get circle
     *
     * @return int
     */
    public function getCircle()
    {
        return $this->circle;
    }

    /**
     * Set note
     *
     * @param int $note
     *
     * @return NoteLabel
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return int
     */
    public function getNote()
    {
        return $this->note;
    }
}

