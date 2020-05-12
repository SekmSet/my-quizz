<?php

namespace App\Controller;

use App\Dto\UserCategorieReponse;
use App\Dto\UserCategorieResult;
use App\Entity\Categorie;
use App\Entity\QuizUserReponse;
use App\Entity\QuizUserResult;
use App\Form\QuizUserAnswerType;
use App\Form\QuizUserResultType;
use App\Form\UserCategorieReponseType;
use App\Form\UserCategorieResultType;
use App\Services\CategorieServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @return Response
     */
    public function show(Request $request, Categorie $categorie)
    {
        if ($request->isMethod('POST')) {
            print_r($request->request->all());
            //print_r($request->request->get('2'));

            // parcourir les question

            // parcourir les réponse

            // remplire un tableau d'erreur
        }
        return $this->render('quizz/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }
}
