<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_index');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     */
    public function forgottenPassword(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneByEmail($email);
            /* @var $user User */
            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('app_forgotten_password');
            }
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            $mailer->sendPasswordMail($email, $token);
            $this->addFlash('success', 'Email envoyé !');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     * @return RedirectResponse|Response
     */
    public function resetPassword(
        Request $request,
        string $token,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        $form = $this->createForm(RegistrationFormType::class)
            ->remove('email')
            ->remove('firstname')
            ->remove('lastname')
            ->remove('phone');

        $form->handleRequest($request);

        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $userRepository->findOneByResetToken($token);
            /* @var $user User */
            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('app_login');
            }
            $first = $form->get('plainPassword')->get('first')->getNormData();
            $second = $form->get('plainPassword')->get('second')->getNormData();

            if ($first != $second) {
                $this->addFlash('danger', 'Champs non identiques');
                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            } else {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $user->setResetToken(null);
                $entityManager->flush();
                $this->addFlash('success', 'Mot de passe mis à jour');
            }

            return $this->redirectToRoute('app_login');
        } else {
            return $this->render('security/reset_password.html.twig', ['token' => $token,
                'registrationForm' => $form->createView()]);
        }
    }

    /**
     * @Route("/logout", name="app_logout")
     * @throws Exception
     */
    public function logout() : void
    {
        throw new Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/delete_user", name="delete_user")
     */
    public function deleteUser(EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_login');
    }
}
