<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPasswordFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class EditPasswordController extends AbstractController
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
     * @Route("/edit/password", name="edit_password")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function index(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(EditPasswordFormType::class, $this->user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $savePwd = $this->user->getPassword();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->refresh($this->user);

            $this->user->refreshToken();
            $entityManager->persist($this->user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $email = (new TemplatedEmail())
                ->from('my-quizz@my-quizz.sekhmset.me')
                ->to($this->user->getEmail())
                ->subject('Validation nouveau mot de passe')
                // path to your Twig template
                ->htmlTemplate('email/confirmEditPassword.html.twig')
                ->context([
                    'user' => $this->user,
                    'pwd' => $savePwd,
                ]);

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Un mail de confirmation vous a été envoyé pour valider votre nouveau mot de passe'
            );

            return $this->redirectToRoute('user_profil');
        }
        return $this->render('user_profil/passwordEdit.html.twig', [
                'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation_password_edit/{token}/{user}/{pwd}", name="confirmed_edit_password")
     * @param Request $request
     * @param $token
     * @param User $user
     * @param string $pwd
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmation_password_edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, $token, User $user, string $pwd) {

        if($token === $user->getConfirmationToken()){

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $this->user,
                    $pwd
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre nouveau mot de passe a bien été enregistré ! :) '
            );

        } else {
            $this->addFlash(
                'danger',
                'Lien de validation invalide !'
            );
        }
        return $this->redirectToRoute('user_profil');
    }
}
