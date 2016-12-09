<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class TotalStudentExtension extends \Twig_Extension {

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFilters() {
        return array(
           new \Twig_SimpleFilter('totalstudent', array($this, 'totalstudentFilter'))
        );        
    }
    
    public function totalStudentFilter($course) {
        $student_repo = $this->doctrine->getRepository('BackendBundle:Student');
        $total_assist_course = $student_repo->findByCourses(array(            
            'courses' => $course
        ));
        $resultCount = count($total_assist_course);
        
        return $resultCount;
    }
       
    public function getName() {
        return 'totalstudent_extension';
    }
    
}
