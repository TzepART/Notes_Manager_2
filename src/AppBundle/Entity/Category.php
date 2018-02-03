<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;


    /**
     * @var Note[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Note", mappedBy="category", cascade={"persist"})
     */
    protected $notes;

    /**
     * @var Sector
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Sector", inversedBy="category", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $sector;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add note
     * @param Note $note
     * @return $this
     */
    public function addNote(Note $note)
    {
        $this->notes[] = $note;
        return $this;
    }

    /**
     * Remove note
     * @param Note $note
     */
    public function removeNote(Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param Note[] $notes
     * @return $this
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
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
    public function setSector($sector)
    {
        $this->sector = $sector;
        return $this;
    }

}

