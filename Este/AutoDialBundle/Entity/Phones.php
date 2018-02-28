<?php

namespace AutoDialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phones
 *
 * @ORM\Table(name="phones")
 * @ORM\Entity(repositoryClass="AutoDialBundle\Repository\PhonesRepository")
 */
class Phones
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
     * @ORM\Column(name="active", type="string", length=1)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="dial", type="string", length=10)
     */
    private $dial;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fio", type="string", length=12, nullable=true)
     */
    private $fio;

    /**
     * @var string
     *
     * @ORM\Column(name="addr", type="string", length=255, nullable=true)
     */
    private $addr;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=30)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="opt1", type="string", length=100, nullable=true)
     */
    private $opt1;

    /**
     * @var string
     *
     * @ORM\Column(name="opt2", type="string", length=100, nullable=true)
     */
    private $opt2;

    /**
     * @var string
     *
     * @ORM\Column(name="opt3", type="string", length=100, nullable=true)
     */
    private $opt3;


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
     * Set active
     *
     * @param string $active
     *
     * @return Phones
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
     * Set dial
     *
     * @param string $dial
     *
     * @return Phones
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Phones
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fio
     *
     * @param string $fio
     *
     * @return Phones
     */
    public function setFio($fio)
    {
        $this->fio = $fio;

        return $this;
    }

    /**
     * Get fio
     *
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * Set addr
     *
     * @param string $addr
     *
     * @return Phones
     */
    public function setAddr($addr)
    {
        $this->addr = $addr;

        return $this;
    }

    /**
     * Get addr
     *
     * @return string
     */
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Phones
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set opt1
     *
     * @param string $opt1
     *
     * @return Phones
     */
    public function setOpt1($opt1)
    {
        $this->opt1 = $opt1;

        return $this;
    }

    /**
     * Get opt1
     *
     * @return string
     */
    public function getOpt1()
    {
        return $this->opt1;
    }

    /**
     * Set opt2
     *
     * @param string $opt2
     *
     * @return Phones
     */
    public function setOpt2($opt2)
    {
        $this->opt2 = $opt2;

        return $this;
    }

    /**
     * Get opt2
     *
     * @return string
     */
    public function getOpt2()
    {
        return $this->opt2;
    }

    /**
     * Set opt3
     *
     * @param string $opt3
     *
     * @return Phones
     */
    public function setOpt3($opt3)
    {
        $this->opt3 = $opt3;

        return $this;
    }

    /**
     * Get opt3
     *
     * @return string
     */
    public function getOpt3()
    {
        return $this->opt3;
    }
}

