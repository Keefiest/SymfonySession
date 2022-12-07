<?php

namespace App\Controller;

use App\Entity\Session;
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
    // afficher les stagiaires non inscrit
    public function findNonInscrits($session_id){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();
        
        $qb = $sub;
        $qb->select('s')
            ->from('App/Entity/Stagiaire','s')
            ->leftJoin('s.sessions', 'se')
            ->where('se.id = :id');

        $sub = $em->createQueryBuilder();
        $sub->select('st')
            ->from('App/Entity/Stagiaire','st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            ->setParameter('id', $session_id)
            ->orderBy('st.nom');
        $query = $sub->getQuery();
        return $query->getResult();

        
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
     * @Route("/session/delStagiaire/{idSession}/{idStagiaire}", name="del_sessionstagiaire")
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
     * @Route("/session/{id}", name="show_session"), requirements={"id"="\d+"}
     */
    public function show(Session $session, SessionRepository $sr): Response
    {
        $nonInscrits = $sr->findNonInscrits($session->getId()); 
        
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'nonInscrits' => $nonInscrits
        ]);
    }
    
}
