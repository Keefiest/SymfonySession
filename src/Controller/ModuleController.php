<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Entity\Programme;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    
    /**
     * @Route("/module", name="app_module")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $modules = $doctrine->getRepository(Module::class)->findBy([], ["moduleName" => "ASC"]);
        return $this->render('module/index.html.twig', [
            'modules' => $modules,
        ]);
    }
    /**
     * @Route("/module/add", name="add_module")
     */    
    public function add(ManagerRegistry $doctrine, Module $module = null, Request $request): Response
    {
        if(!$module){
            $module = new Module();
        }

        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $module = $form->getData();
            $entityManager = $doctrine->getManager();
            // prepare
            $entityManager->persist($module);
            // insert into (execute)
            $entityManager->flush();

            return $this->redirectToRoute('app_module');

        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('module/add.html.twig', [
            'formAddModule' => $form->createView(),
            'edit' => $module->getId()
        ]);

    }
    /**
     * @Route("/module/{id}/delete", name="delete_module")
     */
    public function delete(ManagerRegistry $doctrine, Module $module){

        $entityManager = $doctrine->getManager();
        $entityManager->remove($module);
        $entityManager->flush();
        return $this->redirectToRoute('app_module');
    }
    /**
     * @Route("/module/{id}", name="show_module")
     */
    public function show(Module $module): Response
    {
        return $this->render('module/show.html.twig', [
            'module' => $module
        ]);
    }
}
