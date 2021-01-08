<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthentificatorAuthenticator;
use App\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthentificatorAuthenticator $authenticator,
        UserPasswordEncoderInterface $encoder,
        Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager
    ): ?Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_index');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setActive(false);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $email = $user->getEmail();
            $token = $tokenGenerator->generateToken();
            $user->setConfirmToken($token);
            $entityManager->persist($user);
            $entityManager->flush();
            $mailer->sendConfirmationEmail($email, $token);
            return $this->redirectToRoute('app_confirm_mail');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm_account/{token}", name="app_confirm_account")
     */
    public function confirmAccount(
        string $token,
        EntityManagerInterface $entityManager,
        LoginFormAuthentificatorAuthenticator $authenticator,
        GuardAuthenticatorHandler $guardHandler,
        Request $request,
        UserRepository $userRepository
    ) : ?Response {
        $user = $userRepository->findOneBy(['confirmToken' => $token]);
        /* @var $user User */
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $user->setConfirmToken(null);
        $user->setActive(true);
        $this->addFlash('notice', 'Compte créé avec succès');
        $entityManager->flush();
        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main'
        );
    }

    /**
     * @Route("/confirm-mail", name="app_confirm_mail")
     */
    public function confirmMail() : Response
    {
        $this->addFlash('notice', 'Email envoyé');
        return $this->render('registration/confirm_mail.html.twig');
    }
}
