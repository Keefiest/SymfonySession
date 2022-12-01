<?php

namespace App\Controller;

use App\Entity\Formateur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{  
    /**
     * @Route("/formateur", name="app_formateur")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $formateurs = $doctrine->getRepository(Formateur::class)->findBy([], ["nom" => "ASC"]);
        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs,
        ]);
    }
}
