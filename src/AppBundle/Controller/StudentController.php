<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//Para poder utilizar el objeto response
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\Course;
use BackendBundle\Entity\User;
use BackendBundle\Entity\Student;
use BackendBundle\Entity\Assist;
use AppBundle\Form\StudentType;

class StudentController extends Controller {

    //Para poder utilizar los mensajes Flash

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function studentListAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $course_id = $request->get('course');
        $course_repo = $em->getRepository('BackendBundle:Course');
        $course = $course_repo->find($course_id);
        
        
        $query1 = $em->createQuery('SELECT a FROM BackendBundle:Assist a ORDER BY a.id ASC');
        $myArray = $query1->getArrayResult();
        //$user_id = $user->getId();
        $dql = "SELECT s FROM BackendBundle:Student s WHERE s.courses = $course_id ORDER BY s.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 50);

        // parameters to template
        return $this->render('AppBundle:Student:student-list.html.twig', array(
                    'pagination' => $pagination,
                    'course_id' => $course_id,
                    'course' => $course,
                    'assist'=> $myArray                    
                        )
        );
    }

    public function createStudentAction(Request $request) {
        $user = $this->getUser();
        //obtengo el id del curso pasado por parametro
        $course_id = $request->get('course');
        //entity manager
        $em = $this->getDoctrine()->getManager();
        //repo del curso
        $course_repo = $em->getRepository('BackendBundle:Course');
        //busco en el repo del curso el curso que pase por parametro
        $courses = $course_repo->find($course_id);
        //creo el nuevo estudianto
        $student = new Student();
        //genero el formulario y paso le paso la nueva instancia del estudiante        
        $form = $this->createForm(StudentType::class, $student);
        //obtengo los valores del formulario
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                //seteo el user que creo el estudiante
                $student->setUser($user);
                //seteo el curso al que pertenece el student
                $student->setCourses($courses);
                //lo persisto en em
                $em->persist($student);
                //lo vuelco a la db
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "Alumno creado correctamente";
                } else {
                    $status = "No se ha podido crear el alumno";
                }
            } else {
                $status = "No se ha podido crear el alumno";
            }

            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('student_course_list', array('course' => $course_id));
        }


        return $this->render('AppBundle:Student:register-student.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function editStudentAction(Request $request, $id = null) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $student_repo = $em->getRepository('BackendBundle:Student');
        $student_edit = $student_repo->find($id);
        $student_course = $student_edit->getCourses()->getId();
        
        $form = $this->createForm(StudentType::class, $student_edit);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->persist($student_edit);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "Estudiante actualizado correctamente";
                } else {
                    $status = "No se ha podido actualizar el estudiante";
                }
            } else {
                $status = "No se ha podido actualizar el estudiante";
            }

            $this->session->getFlashBag()->add("status", $status);
            //return $this->redirectToRoute('home');
             return $this->redirectToRoute('student_course_list', array('course' => $student_course));
        }

        return $this->render('AppBundle:Student:edit-student.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function removeStudentAction(Request $request, $id=null) {
        $em = $this->getDoctrine()->getManager();
        $student_repo = $em->getRepository('BackendBundle:Student');
        $student_delete = $student_repo->find($id);
        
        $em->remove($student_delete);
        
        $flush = $em->flush();
        
        if ($flush == null) {
            $status = "Estudiante eliminado correctamente";
        }else{
            $status = "El estudiante no se ha podido eliminar";
        }
        
        return new Response($status);
    }
    
    public function allStudentsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $student_repo = $em->getRepository('BackendBundle:Student');
        $all_student = $student_repo->findAll();
        
        return $this->render('AppBundle:Student:all-student-list.html.twig', array(
            'students'=> $all_student
        ));
    }
    
     public function myStudentsAction(Request $request) {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $student_repo = $em->getRepository('BackendBundle:Student');
        $all_student = $student_repo->findByUser($user);
        
        return $this->render('AppBundle:Student:my-student-list.html.twig', array(
            'students'=> $all_student
        ));
    }
    
}
