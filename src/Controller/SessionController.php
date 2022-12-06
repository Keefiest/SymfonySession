<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="app_session")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $sessions = $doctrine->getRepository(Session::class)->findBy([], ["title" => "ASC"]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }
    /**
     * @Route("/session/{id}/delete", name="delete_session")
     */
    public function delete(ManagerRegistry $doctrine, Session $session){

        $entityManager = $doctrine->getManager();
        $entityManager->remove($session);
        $entityManager->flush();
        return $this->redirectToRoute('app_session');
    }
     /**
     * @Route("/session/add", name="add_session")
     * @Route("/session/{id}/edit", name="edit_session")
     */    
    public function add(ManagerRegistry $doctrine, Session $session = null, Request $request): Response
    {
        if(!$session){
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $session = $form->getData();
            $entityManager = $doctrine->getManager();
            // prepare
            $entityManager->persist($session);
            // insert into (execute)
            $entityManager->flush();

            return $this->redirectToRoute('app_session');

        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('session/add.html.twig', [
            'formAddSession' => $form->createView(),
            'edit' => $session->getId()
        ]);

    }
    /**
     * @Route("/session/addModule/{idSession}/{idProgramme}", name="add_sprogramme")
     */
    public function addProgramme(ManagerRegistry $doctrine, Request $request, Session $session, Programme $programme){
        $session->addProgramme($programme);
        $entityManager = $doctrine->getManager();
        // prepare
        $entityManager->persist($session);
        // insert into(execute)
        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
    /**
     * @Route("/session/delModule/{idSession}/{idProgramme}", name="del_sprogramme")
     */
    public function delProgramme(ManagerRegistry $doctrine, Request $request, Session $session, Programme $programme){
        $session->removeProgramme($programme);
        $entityManager = $doctrine->getManager();
        // prepare
        $entityManager->persist($session);
        // insert into(execute)
        $entityManager->flush();
        
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
    /**
     * @Route("/session/addStagiaire/{idSession}/{idStagiaire}", name="add_sstagiaire")
     */
    public function addStagiaire(ManagerRegistry $doctrine, Request $request, Session $session, Stagiaire $stagiaire){

    }
    /**
     * @Route("/session/delStagiaire/{idSession}/{idStagiaire}", name="del_sstagiaire")
     */
    public function delStagiaire(ManagerRegistry $doctrine, Request $request, Session $session, Stagiaire $stagiaire){

    }
    /**
     * @Route("/session/{id}", name="show_session")
     */
    public function show(Session $session): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session
        ]);
    }
}
