<?php

namespace AutoDialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dial
 *
 * @ORM\Table(name="dial")
 * @ORM\Entity(repositoryClass="AutoDialBundle\Repository\DialRepository")
 */
class Dial
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string", length=1)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="date_call_start", type="string", length=30)
     */
    private $dateCallStart;

    /**
     * @var string
     *
     * @ORM\Column(name="date_call_end", type="string", length=30)
     */
    private $dateCallEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="date_create", type="string", length=30)
     */
    private $dateCreate;

    /**
     * @var string
     *
     * @ORM\Column(name="date_start", type="string", length=30)
     */
    private $dateStart;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255)
     */
    private $comment;


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
     * @return Dial
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
     * Set active
     *
     * @param string $active
     *
     * @return Dial
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set dateCallStart
     *
     * @param string $dateCallStart
     *
     * @return Dial
     */
    public function setDateCallStart($dateCallStart)
    {
        $this->dateCallStart = $dateCallStart;

        return $this;
    }

    /**
     * Get dateCallStart
     *
     * @return string
     */
    public function getDateCallStart()
    {
        return $this->dateCallStart;
    }

    /**
     * Set dateCallEnd
     *
     * @param string $dateCallEnd
     *
     * @return Dial
     */
    public function setDateCallEnd($dateCallEnd)
    {
        $this->dateCallEnd = $dateCallEnd;

        return $this;
    }

    /**
     * Get dateCallEnd
     *
     * @return string
     */
    public function getDateCallEnd()
    {
        return $this->dateCallEnd;
    }

    /**
     * Set dateCreate
     *
     * @param string $dateCreate
     *
     * @return Dial
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return string
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateStart
     *
     * @param string $dateStart
     *
     * @return Dial
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return string
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Dial
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}

