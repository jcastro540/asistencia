<?php

namespace BackendBundle\Entity;

/**
 * Assist
 */
class Assist
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $assists;
    
    /**
     * @var string
     */
    
    private $class;
    /**
     * @var \BackendBundle\Entity\Course
     */
    private $course;

    /**
     * @var \BackendBundle\Entity\Student
     */
    private $student;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set assists
     *
     * @param string $assists
     *
     * @return Assist
     */
    public function setAssists($assists)
    {
        $this->assists = $assists;

        return $this;
    }

    /**
     * Get assists
     *
     * @return string
     */
    public function getAssists()
    {
        return $this->assists;
    }
    
    /**
     * Set class
     *
     * @param string $class
     *
     * @return class
     */
    
    
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set course
     *
     * @param \BackendBundle\Entity\Course $course
     *
     * @return Assist
     */
    public function setCourse(\BackendBundle\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \BackendBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set student
     *
     * @param \BackendBundle\Entity\Student $student
     *
     * @return Assist
     */
    public function setStudent(\BackendBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \BackendBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
