<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
//Para poder utilizar el objeto response
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\Assist;
use BackendBundle\Entity\Course;
use BackendBundle\Entity\Student;
use BackendBundle\Entity\User;

class AssistController extends Controller {

    //Para poder utilizar los mensajes Flash

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function assistAction(Request $request) {

        //Curso al que esta inscrito 
        $course_id = $request->get('course');
        //alumno
        $student_id = $request->get('student');
        //Clase
        $class = $request->get('class');
        //asistio?
        $asistio = $request->get('asistio');

        //entity manager
        $em = $this->getDoctrine()->getManager();
        //repo del curso
        $course_repo = $em->getRepository('BackendBundle:Course');
        //busco en el repo del curso que pase por parametro
        $courses = $course_repo->find($course_id);
        //Repo del Student
        $student_repo = $em->getRepository('BackendBundle:Student');
        //busco en el repo del student que pase por parametro
        $student = $student_repo->find($student_id);

        $assist = new Assist();
        $assist->setCourse($courses);
        $assist->setStudent($student);
        $assist->setAssists($asistio);
        $assist->setClass($class);

        $em->persist($assist);
        $flush = $em->flush();

        if ($flush == null) {
            $status = "Asistencia creada ";
        } else {
            $status = "Asistencia no creada";
        }

        return new Response($status);
    }

    public function unassistAction(Request $request) {

        //Curso al que esta inscrito 
        $course_id = $request->get('course');
        //alumno
        $student_id = $request->get('student');
        //Clase
        $class = $request->get('class');
        //asistio?
        $asistio = $request->get('asistio');

        //entity manager
        $em = $this->getDoctrine()->getManager();
        //repo del curso
        $assist_repo = $em->getRepository('BackendBundle:Assist');
        //busco en el repo del assist que pase por parametro
        $assists = $assist_repo->findOneBy(array(
            'course' => $course_id,
            'student' => $student_id,
            'class' => $class
        ));

        $em->remove($assists);
        $flush = $em->flush();

        if ($flush == null) {
            $status = "Asistencia eliminada ";
        } else {
            $status = "No se ha eliminado la asistencia";
        }

        return new Response($status);
    }

    public function checkAsistAction(Request $request, $id = null) {
        $em = $this->getDoctrine()->getManager();
        //$course_id = $request->get('course');
        $assist_repo = $em->getRepository('BackendBundle:Assist')->findByCourse(array(
            'course'=> $id
        ));

        $serializer = $this->container->get('serializer');
        $reports = $serializer->serialize($assist_repo, 'json');
        return new Response($reports);
    }

}
