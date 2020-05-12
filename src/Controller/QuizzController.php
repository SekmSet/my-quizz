<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Historique;
use App\Entity\User;
use App\Entity\UserReponse;
use App\Repository\UserRepository;
use App\Services\CategorieServices;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class QuizzController extends AbstractController
{
    /**
     * @var CategorieServices
     */
    private $categorieServices;
    private ?\Symfony\Component\Security\Core\User\UserInterface $user;
    private UserRepository $userRepository;

    /**
     * QuizzController constructor.
     * @param CategorieServices $categorieServices
     * @param Security $security
     * @param UserRepository $userRepository
     */
    public function __construct(CategorieServices $categorieServices, Security $security, UserRepository $userRepository)
    {
        $this->categorieServices = $categorieServices;
        $this->user = $security->getUser();
        $this->userRepository = $userRepository;
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
            if ($this->user !== null) {
                $user = $this->userRepository->find($this->user->getId());
            } else {
                $user = null;
            }

            $historique = new Historique();
            $historique->setUser($user);
            $historique->setCategorie($categorie);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($historique);

            foreach($categorie->getQuestions() as $question){
                foreach($question->getReponses() as $reponse){
                    if(array_key_exists($question->getId(), $request->request->all()) &&
                        (int) $request->request->get($question->getId()) === $reponse->getId()) {

                        $userReponse = new UserReponse();
                        $userReponse->setHistorique($historique);
                        $userReponse->setReponse($reponse);
                        $historique->addReponse($userReponse);
                        $entityManager->persist($userReponse);
                    }
                }
            }

            $nbrQuestionExpected = count($categorie->getQuestions());
            $nbrQuestionReceived = count($historique->getReponses());

            if($nbrQuestionExpected !== $nbrQuestionReceived){
                $this->addFlash(
                    'danger',
                    'De la triche ? '
                );

                return $this->redirectToRoute('show_quizz', [
                    'categorie' => $categorie->getId(),
                ]);
            }

            $entityManager->flush();

            return $this->redirectToRoute('quizz');
        }
        return $this->render('quizz/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }
}
