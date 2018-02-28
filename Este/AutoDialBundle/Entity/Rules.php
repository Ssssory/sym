<?php

namespace AutoDialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rules
 *
 * @ORM\Table(name="rules")
 * @ORM\Entity(repositoryClass="AutoDialBundle\Repository\RulesRepository")
 */
class Rules
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
     * @ORM\Column(name="dial", type="string", length=7)
     */
    private $dial;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;


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
     * Set dial
     *
     * @param string $dial
     *
     * @return Rules
     */
    public function setDial($dial)
    {
        $this->dial = $dial;

        return $this;
    }

    /**
     * Get dial
     *
     * @return string
     */
    public function getDial()
    {
        return $this->dial;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Rules
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Rules
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

