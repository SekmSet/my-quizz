<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfilFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserProfilController extends AbstractController
{

    /**
     * @var User
     */
    private $user;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->user = $security->getUser();
    }

    /**
     * @Route("/user/profil", name="user_profil")
     * @return Response
     */
    public function index()
    {
        $form = $this->createForm(UserProfilFormType::class, $this->user);

        return $this->render('user_profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/profil/edit", name="user_edit_profil")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserProfilFormType::class, $this->user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!empty($form->get('password')->getData())) {
                $this->user->setPassword(
                    $passwordEncoder->encodePassword(
                        $this->user,
                        $form->get('password')->getData()
                    )
                );
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('user_profil');
        }

        return $this->render('user_profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
