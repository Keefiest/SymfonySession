<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Route("/session/addStagiaire/{idSession}/add/{idStagiaire}", name="add_session_stagiaire")
     * @ParamConverter("session", options={"mapping": {"idSession": "id"}})
     * @ParamConverter("stagiaire", options={"mapping": {"idStagiaire": "id"}})
     */
    public function addStagiaire(ManagerRegistry $doctrine, Request $request, Session $session, Stagiaire $stagiaire){
        $session->addStagiaire($stagiaire);
        $entityManager = $doctrine->getManager();
        // prepare
        $entityManager->persist($session);
        // insert into(execute)
        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
    /**
     * @Route("/session/delStagiaire/{idSession}/{idStagiaire}", name="del_session_stagiaire")
     * @ParamConverter("session", options={"mapping": {"idSession": "id"}})
     * @ParamConverter("stagiaire", options={"mapping": {"idStagiaire": "id"}})
     */
    public function delStagiaire(ManagerRegistry $doctrine, Request $request, Session $session, Stagiaire $stagiaire){
        $session->removeStagiaire($stagiaire);
        $entityManager = $doctrine->getManager();
        // prepare
        $entityManager->persist($session);
        // insert into(execute)
        $entityManager->flush();
        
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
        /**
     * @Route("/session/addProgramme/{idSession}/{idModule}", name="add_session_programme")
     * @ParamConverter("session", options={"mapping": {"idSession": "id"}})
     * @ParamConverter("module", options={"mapping": {"idModule": "id"}})
     */
    public function addProgramme(ManagerRegistry $doctrine, Request $request, Session $session, Module $module, Programme $programme){

        $session->addModule($module);
        $entityManager = $doctrine->getManager();
        // prepare
        $entityManager->persist($session);
        // insert into(execute)
        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
    /**
     * @Route("/session/delProgramme/{idSession}/{idProgramme}", name="del_session_programme")
     * @ParamConverter("session", options={"mapping": {"idSession": "id"}})
     * @ParamConverter("programme", options={"mapping": {"idProgramme": "id"}})
     */
    public function delProgramme(ManagerRegistry $doctrine, Request $request, Session $session, Programme $programme){

        $entityManager = $doctrine->getManager();
        $entityManager->remove($programme);
        $entityManager->flush();
        
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
    /**
     * @Route("/session/{id}", name="show_session"), requirements={"id"="\d+"}
     * IsGranted("ROLE_USER")
     */
    public function show(Session $session, SessionRepository $sr): Response
    {
        $nonInscrits = $sr->findNonInscrits($session->getId());
        $nonAssocies = $sr->findNonAssocies($session->getId());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'nonInscrits' => $nonInscrits,
            'nonAssocies' => $nonAssocies,

        ]);
    }
    
}
