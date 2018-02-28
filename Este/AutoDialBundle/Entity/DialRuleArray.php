<?php

namespace AutoDialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DialRuleArray
 *
 * @ORM\Table(name="dial_rule_array")
 * @ORM\Entity(repositoryClass="AutoDialBundle\Repository\DialRuleArrayRepository")
 */
class DialRuleArray
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
     * @ORM\Column(name="dial_base", type="string", length=255)
     */
    private $dialBase;

    /**
     * @var string
     *
     * @ORM\Column(name="array", type="string", length=255)
     */
    private $array;


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
     * Set dialBase
     *
     * @param string $dialBase
     *
     * @return DialRuleArray
     */
    public function setDialBase($dialBase)
    {
        $this->dialBase = $dialBase;

        return $this;
    }

    /**
     * Get dialBase
     *
     * @return string
     */
    public function getDialBase()
    {
        return $this->dialBase;
    }

    /**
     * Set array
     *
     * @param string $array
     *
     * @return DialRuleArray
     */
    public function setArray($array)
    {
        $this->array = $array;

        return $this;
    }

    /**
     * Get array
     *
     * @return string
     */
    public function getArray()
    {
        return $this->array;
    }
}

