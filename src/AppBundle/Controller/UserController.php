<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
//Para poder utilizar el objeto response
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\EditUserType;

class UserController extends Controller {

    //Para poder utilizar los mensajes Flash

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function loginAction(Request $request) {
        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:User:login.html.twig', array(
                    'last_username' => $lastUsername,
                    'error' => $error
        ));
    }

    public function registerAction(Request $request) {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                //$user_repo = $em->getRepository('BackendBundle:User');
                //saber si el email y el username no existen
                $query = $em->createQuery('SELECT u FROM BackendBundle:User u WHERE u.email = :email OR u.username = :username')
                        ->setParameter('email', $form->get("email")->getData())
                        ->setParameter('username', $form->get("username")->getData())
                ;
                $user_isset = $query->getResult();

                if (count($user_isset) == 0) {
                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($form->get("password")->getData(), $user->getSalt());

                    $user->setPassword($password);
                    $user->setRole('ROLE_TEACHER');
                    $user->setImame('null');

                    $em->persist($user);

                    $flush = $em->flush();

                    if ($flush == null) {
                        $status = "Usuario creado correctamente";
                        $this->session->getFlashBag()->add("status", $status);
                        return $this->redirect('users');
                    } else {
                        $status = "Usuario no registrado correctamente";
                    }
                } else {
                    $status = "Usuario ya existe!!";
                }
            } else {
                $status = "Usuario no registrado correctamente";
            }

            //Lanzar Mensaje Flash
            $this->session->getFlashBag()->add("status", $status);
        }


        return $this->render('AppBundle:User:register-user.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function usernameTestAction(Request $request) {
        $username = $request->get('username');
        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository('BackendBundle:User');
        $user_isset = $user_repo->findOneBy(array(
            'username' => $username
        ));

        $result = "used";

        if (count($user_isset) >= 1 && is_object($user_isset)) {
            $result = "used";
        } else {
            $result = "unused";
        }

        return new Response($result);
    }

    public function editUserAction(Request $request) {
        $user = $this->getUser();
        $user_image = $user->getImame();
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                //Consulta
                $query = $em->createQuery('SELECT u FROM BackendBundle:User u WHERE u.email = :email OR u.username = :username')
                        ->setParameter('email', $form->get('email')->getData())
                        ->setParameter('username', $form->get('username')->getData());
                //Resultado de la consulta
                $user_isset = $query->getResult();

                if (($user->getEmail() == $user_isset[0]->getEmail() && $user->getUsername() == $user_isset[0]->getUsername()) || count($user_isset) == 0) {
                    //Codificar contraseña
                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);

                    $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());
                    //Seteo los datos que no tengo $form->handleRequest($request) para guardar la info en la db
                    $user->setPassword($password);

                    //Subir la imagen al servido

                    $file = $form["imame"]->getData();
                    //Si el archivo no esta vacio y no es null
                    if (!empty($file) && $file != null) {
                        //sacar la extension del archivo
                        $ext = $file->guessExtension();
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            //nombre del archivo
                            $file_name = $user->getUsername() . time() . '.' . $ext;
                            //movemos el fichero al directorio correspondiente
                            $file->move("uploads/users", $file_name);
                            //Seteamos la imagen 
                            $user->setImame($file_name);
                        }
                    } else {
                        $user->setImame($user_image);
                    }

                    $em->persist($user);
                    $flush = $em->flush();

                    if ($flush == null) {
                        $status = "Has modificado correctamente tus datos";
                    } else {
                        $status = "No has tus datos correctamente";
                    }
                } else {
                    $status = "El usuario ya existe";
                }
            } else {
                $status = "No se han actualizado tus datos";
            }
            //Lanzar Mensaje Flash
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('users');
        }
        return $this->render('AppBundle:User:edit-user.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    
    public function editUserAdminAction(Request $request, $id = null) {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository('BackendBundle:User');
        $user_edit = $user_repo->find($id);

        $form = $this->createForm(UserType::class, $user_edit);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                //Codificar contraseña
                $factory = $this->get("security.encoder_factory");
                $encoder = $factory->getEncoder($user_edit);

                $password = $encoder->encodePassword($form->get('password')->getData(), $user_edit->getSalt());
                //Seteo los datos que no tengo $form->handleRequest($request) para guardar la info en la db
                $user_edit->setPassword($password);



                $em->persist($user_edit);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "Has modificado correctamente los datos";
                } else {
                    $status = "No has los datos correctamente";
                }
            } else {
                $status = "No se han actualizado los datos";
            }
            //Lanzar Mensaje Flash
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('users');
        }
        return $this->render('AppBundle:User:edit-user-admin.html.twig', array(
                    'form' => $form->createView()
        ));
    }
    
    public function usersListAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT u FROM BackendBundle:User u ORDER BY u.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 6);

        // parameters to template
        return $this->render('AppBundle:User:users.html.twig', array('pagination' => $pagination));
    }

    public function userDeleteAction(Request $request, $id = null) {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository('BackendBundle:User');
        $user_delete = $user_repo->find($id);

        if ($user->getRole() == 'ROLE_ADMIN') {

            $em->remove($user_delete);

            $flush = $em->flush();

            if ($flush == null) {
                $status = "Usuario eliminado";
            } else {
                $status = "Usuario no eliminado";
            }
        } else {
            $status = "Usuario no eliminado";
        }


        return new Response($status);
    }

     public function userAction(Request $request) {
        
        $user = $this->getUser();
        $user_id = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT c FROM BackendBundle:Course c WHERE c.user = $user_id ORDER BY c.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 5);

        
        return $this->render('AppBundle:User:user.html.twig',array(
            'user'=> $user,
            'pagination' => $pagination
        ));
        
    }
    
}
