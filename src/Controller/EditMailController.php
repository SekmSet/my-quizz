<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditMailFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class EditMailController extends AbstractController
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
     * @Route("/edit/mail", name="edit_mail")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request,MailerInterface $mailer)
    {
        $form = $this->createForm(EditMailFormType::class, $this->user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saveEmail = $this->user->getEmail();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->refresh($this->user);

            $this->user->refreshToken();
            $entityManager->persist($this->user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $email = (new TemplatedEmail())
                ->from('my-quizz@my-quizz.sekhmset.me')
                ->to($saveEmail)
                ->subject('Validation adresse mail')
                // path to your Twig template
                ->htmlTemplate('email/confirmNewEmail.html.twig')
                ->context([
                    'user' => $this->user,
                    'mail' => $saveEmail,
                ]);

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Un mail de confirmation vous a été envoyé à votre nouvelle adresse mail'
            );

            return $this->redirectToRoute('user_profil');
        }
        return $this->render('user_profil/mailEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation_email_edit/{token}/{user}/{mail}", name="app_confirmation_edit_email")
     * @param Request $request
     * @param $token
     * @param User $user
     * @param string $mail
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmation_email_edit(Request $request, $token, User $user, string $mail) {
        if($token === $user->getConfirmationToken()){
            $user->setEmail($mail);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Email validée ! :) '
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
