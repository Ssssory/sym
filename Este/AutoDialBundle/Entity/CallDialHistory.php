<?php

namespace AutoDialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CallDialHistory
 *
 * @ORM\Table(name="call_dial_history")
 * @ORM\Entity(repositoryClass="AutoDialBundle\Repository\CallDialHistoryRepository")
 */
class CallDialHistory
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
     * @ORM\Column(name="dial_base", type="string", length=20, nullable=true)
     */
    private $dialBase;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=30)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=30, nullable=true)
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="all_time", type="string", length=50, nullable=true)
     */
    private $allTime;

    /**
     * @var string
     *
     * @ORM\Column(name="nums", type="string", length=50, nullable=true)
     */
    private $nums;

    /**
     * @var string
     *
     * @ORM\Column(name="other", type="string", length=100, nullable=true)
     */
    private $other;


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
     * @return CallDialHistory
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
     * Set phone
     *
     * @param string $phone
     *
     * @return CallDialHistory
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
     * Set start
     *
     * @param \DateTime $start
     *
     * @return CallDialHistory
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set answer
     *
     * @param string $answer
     *
     * @return CallDialHistory
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
     * Set allTime
     *
     * @param string $allTime
     *
     * @return CallDialHistory
     */
    public function setAllTime($allTime)
    {
        $this->allTime = $allTime;

        return $this;
    }

    /**
     * Get allTime
     *
     * @return string
     */
    public function getAllTime()
    {
        return $this->allTime;
    }

    /**
     * Set nums
     *
     * @param string $nums
     *
     * @return CallDialHistory
     */
    public function setNums($nums)
    {
        $this->nums = $nums;

        return $this;
    }

    /**
     * Get nums
     *
     * @return string
     */
    public function getNums()
    {
        return $this->nums;
    }

    /**
     * Set other
     *
     * @param string $other
     *
     * @return CallDialHistory
     */
    public function setOther($other)
    {
        $this->other = $other;

        return $this;
    }

    /**
     * Get other
     *
     * @return string
     */
    public function getOther()
    {
        return $this->other;
    }
}

