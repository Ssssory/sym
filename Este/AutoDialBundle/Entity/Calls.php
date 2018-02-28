<?php

namespace AutoDialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Calls
 *
 * @ORM\Table(name="calls")
 * @ORM\Entity(repositoryClass="AutoDialBundle\Repository\CallsRepository")
 */
class Calls
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
     * @ORM\Column(name="phone", type="string", length=15)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="dial", type="string", length=10)
     */
    private $dial;

    /**
     * @var string
     *
     * @ORM\Column(name="start", type="string", length=30, nullable=true)
     */
    private $start;

    /**
     * @var string
     *
     * @ORM\Column(name="end", type="string", length=30, nullable=true)
     */
    private $end;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", length=30, nullable=true)
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=30, nullable=true)
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="numbers", type="string", length=10, nullable=true)
     */
    private $numbers;

    /**
     * @var string
     *
     * @ORM\Column(name="round", type="string", length=2, nullable=true)
     */
    private $round;


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
     * Set phone
     *
     * @param string $phone
     *
     * @return Calls
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
     * Set dial
     *
     * @param string $dial
     *
     * @return Calls
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
     * Set start
     *
     * @param string $start
     *
     * @return Calls
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param string $end
     *
     * @return Calls
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set time
     *
     * @param string $time
     *
     * @return Calls
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set answer
     *
     * @param string $answer
     *
     * @return Calls
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set numbers
     *
     * @param string $numbers
     *
     * @return Calls
     */
    public function setNumbers($numbers)
    {
        $this->numbers = $numbers;

        return $this;
    }

    /**
     * Get numbers
     *
     * @return string
     */
    public function getNumbers()
    {
        return $this->numbers;
    }

    /**
     * Set round
     *
     * @param string $round
     *
     * @return Calls
     */
    public function setRound($round)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return string
     */
    public function getRound()
    {
        return $this->round;
    }
}

