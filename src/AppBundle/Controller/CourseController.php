<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//Para poder utilizar el objeto response
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\Course;
use BackendBundle\Entity\User;
use AppBundle\Form\CreateCourseType;

class CourseController extends Controller {

    //Para poder utilizar los mensajes Flash

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function courseListAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $dql = ('SELECT c FROM BackendBundle:Course c ORDER BY c.id DESC');
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 6);

        // parameters to template
        return $this->render('AppBundle:Course:courses-list.html.twig', array('pagination' => $pagination));
    }

    public function myCourseListAction(Request $request) {
        $user = $this->getUser();
        $user_id = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT c FROM BackendBundle:Course c WHERE c.user = $user_id ORDER BY c.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 6);

        // parameters to template
        return $this->render('AppBundle:Course:mycourses-list.html.twig', array('pagination' => $pagination));
    }

    public function createCourseAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $course = new Course();

        $form = $this->createForm(CreateCourseType::class, $course);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                /*
                 * $data['title']
                 * $data['body']
                 */
                $startdate = $form->get('startdate')->getData();

                $course = $form->getData();
                $em->persist($course);

                $flush = $em->flush();

                if ($flush == null) {
                    $status = "Curso creado satisfactoriamente";
                    $this->session->getFlashBag()->add("status", $status);
                    return $this->redirectToRoute('home');
                } else {
                    $status = "No se ha podido crear el Curso";
                }

                $response['success'] = true;
            } else {

                $response['success'] = false;
                $response['cause'] = 'whatever';
            }
            // $this->session->getFlashBag()->add("status", $status);
            // return new JsonResponse($response);
        }


        return $this->render('AppBundle:Course:register-course.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function editCourseAction(Request $request, $id = null) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $course_repo = $em->getRepository('BackendBundle:Course');
        $course_edit = $course_repo->find($id);

        $form = $this->createForm(CreateCourseType::class, $course_edit);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->persist($course_edit);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "Curso actulizado correctamente";
                } else {
                    $status = "No se ha podido acualizar el curso";
                }
            } else {
                $status = "No se ha podido acualizar el curso";
            }

            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('home');
        }

        return $this->render('AppBundle:Course:edit-course.html.twig', array(
                    'form' => $form->createView()
        ));
    }
    
    public function deleteCourseAction(Request $request, $id= null) {
        $em = $this->getDoctrine()->getManager();
        $course_repo = $em->getRepository('BackendBundle:Course');
        $course_delete = $course_repo->find($id);
        
        $em->remove($course_delete);
        
        $flush = $em->flush();

        
        if ($flush == null) {
            $status = "Curso eliminado correctamente";
        }else{
            $status = "El curso no se ha podido eliminar";
        }
        
        return new Response($status);
    }

}
