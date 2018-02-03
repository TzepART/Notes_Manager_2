<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoteRepository")
 */
class Note
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=256)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="notes")
     */
    private $user;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="notes")
     * @ORM\Column(nullable=true)
     */
    private $category;

    /**
     * @var NoteLabel
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\NoteLabel", inversedBy="note", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $noteLabel;


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
     * Set title
     *
     * @param string $title
     *
     * @return Note
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Note
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return NoteLabel
     */
    public function getNoteLabel()
    {
        return $this->noteLabel;
    }

    /**
     * @param NoteLabel $noteLabel
     * @return $this
     */
    public function setNoteLabel(NoteLabel $noteLabel)
    {
        $this->noteLabel = $noteLabel;
        return $this;
    }



}

