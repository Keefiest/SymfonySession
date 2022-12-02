<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
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
