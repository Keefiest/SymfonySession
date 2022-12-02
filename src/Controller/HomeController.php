<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Category::class)->findBy([], ["categoryName" => "ASC"]);
        return $this->render('home/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/home/inscription-connexion", name="app_inscription-connexion")
     */
    public function inscriptionConnexion(): Response
    {
        return $this->render('home/incription-connexion.html.twig', []);
    }
    /**
     * @Route("/category/add", name="add_category")
     */    
    public function add(ManagerRegistry $doctrine, Category $category = null, Request $request): Response
    {
        if(!$category){
            $category = new Category();
        }
        
        $form = $this->createForm(CategoryType::class, $category);
        
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $entityManager = $doctrine->getManager();
            // prepare
            $entityManager->persist($category);
            // insert into (execute)
            $entityManager->flush();

            return $this->redirectToRoute('app_home');

        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('home/add.html.twig', [
            'formAddCategory' => $form->createView(),
            'edit' => $category->getId()
        ]);

    }
    /**
     * @Route("/category/{id}/delete", name="delete_category")
     */
    public function delete(ManagerRegistry $doctrine, Category $category){

        $entityManager = $doctrine->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/home/{id}", name="show_category")
     */
    public function show(Category $category, ManagerRegistry $doctrine): Response{

        return $this->render('home/show.html.twig', [
            'category' => $category
        ]);
    }


    // public function show(Module $module, ManagerRegistry $doctrine): Response{
    //     $modules = $doctrine->getRepository(Module::class)->findBy(["category" => "1"], ["moduleName" => "ASC"]);
    //     return $this->render('home/show.html.twig', [
    //         'module' => $module,
    //         'modules' => $modules,
    //     ]);
    // }
}
