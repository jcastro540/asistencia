<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class AssistExtension extends \Twig_Extension {

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFilters() {
        return array(
           new \Twig_SimpleFilter('assist', array($this, 'assistFilter'))
        );        
    }
    
    public function assistFilter($student, $course, $class) {
        $assist_repo = $this->doctrine->getRepository('BackendBundle:Assist');
        $assist_course = $assist_repo->findOneBy(array(
            'student' => $student,
            'course' => $course,
            'class'=>$class
        ));
        
        if(!empty($assist_course) && is_object($assist_course)){
           $result = true;
        }else{
            $result = false;
        }
        
        return $result;
    }
       
    public function getName() {
        return 'assist_extension';
    }
    
}
