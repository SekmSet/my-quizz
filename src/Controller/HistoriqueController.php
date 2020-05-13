<?php

namespace App\Controller;

use App\Entity\Historique;
use App\Repository\UserRepository;
use App\Services\HistoriqueServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HistoriqueController extends AbstractController
{
    /**
     * @var HistoriqueServices
     */
    private HistoriqueServices $historiqueServices;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    private ?\Symfony\Component\Security\Core\User\UserInterface $user;

    /**
     * HistoriqueController constructor.
     * @param HistoriqueServices $historiqueServices
     */
    public function __construct(HistoriqueServices $historiqueServices, Security $security, UserRepository $userRepository)
    {
        $this->historiqueServices = $historiqueServices;
        $this->user = $security->getUser();
        $this->userRepository = $userRepository;

    }


    /**
     * @Route("/historique", name="historique")
     */
    public function index()
    {
        if ($this->user !== null) {
            $user = $this->userRepository->find($this->user->getId());
        } else {
            $user = null;
        }

        $historiques = $this->historiqueServices->get_my_history($user);

        return $this->render('historique/index.html.twig', [
            'historiques' => $historiques,
        ]);
    }


    /**
     * @Route("/historique/{historique}", name="historique_show_quizz")
     * @param Historique $historique
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function quiz_result(Historique $historique){
        return $this->render('historique/show.html.twig', [
            'historique' => $historique,
        ]);
    }
}
