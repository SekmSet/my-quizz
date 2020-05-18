<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $email = (new TemplatedEmail())
                ->from('my-quizz@my-quizz.sekhmset.me')
                ->to($user->getEmail())
                ->subject('Inscription My Quizz')
                // path to your Twig template
                ->htmlTemplate('email/confirmAccount.html.twig')
                ->context([
                    'user' => $user,
                ]);

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Email de confirmation envoyé, confirmer votre inscription avant de vous connecter '
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation_email_register/{token}/{user}", name="app_confirmation_email_register")
     * @param Request $request
     */
    public function confirmation_email_register(Request $request, $token, User $user) {
        if($token === $user->getConfirmationToken()){
            $user->setActivated(1);

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
        return $this->redirectToRoute('home');
    }
}
