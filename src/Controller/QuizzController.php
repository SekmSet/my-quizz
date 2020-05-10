<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Services\CategorieServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuizzController extends AbstractController
{
    /**
     * @var CategorieServices
     */
    private $categorieServices;

    /**
     * QuizzController constructor.
     * @param CategorieServices $categorieServices
     */
    public function __construct(CategorieServices $categorieServices)
    {
        $this->categorieServices = $categorieServices;
    }

    /**
     * @Route("/quizz", name="quizz")
     */
    public function index()
    {
        $categories = $this->categorieServices->get_categories();

        return $this->render('quizz/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/quizz/{categorie}", name="show_quizz")
     * @param Categorie $categorie
     */
    public function show(Categorie $categorie){


        return $this->render('quizz/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }
}
