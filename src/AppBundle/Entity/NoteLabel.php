<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * NoteLabel
 *
 * @ORM\Table(name="note_label")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoteLabelRepository")
 */
class NoteLabel
{
    use Timestampable;

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
     * @var Sector
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sector", inversedBy="noteLabels")
     */
    private $sector;

    /**
     * @var Note
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Note", inversedBy="noteLabel")
     * @ORM\JoinColumn(name="note_id", referencedColumnName="id",)
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
     * @return Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * @param Sector $sector
     * @return $this
     */
    public function setSector(Sector $sector)
    {
        $this->sector = $sector;
        return $this;
    }

    /**
     * @return Note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param Note $note
     * @return $this
     */
    public function setNote(Note $note)
    {
        $this->note = $note;
        return $this;
    }

}

