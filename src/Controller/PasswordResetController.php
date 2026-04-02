<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordResetController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function request(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer,
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $user->resetToken = $token;
                $user->resetTokenExpiresAt = new \DateTime('+1 hour');
                $em->flush();

                $resetUrl = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $emailMessage = (new Email())
                    ->from('noreply@bowlingcenter-brooklyn.nl')
                    ->to($user->email)
                    ->subject('Reset your password — Bowlingcenter Brooklyn')
                    ->html($this->renderView('email/password_reset.html.twig', [
                        'user' => $user,
                        'resetUrl' => $resetUrl,
                    ]));

                $mailer->send($emailMessage);
            }

            // Always show success to prevent email enumeration
            $this->addFlash('success', 'If an account exists with that email, a password reset link has been sent.');
            return $this->redirectToRoute('app_forgot_password');
        }

        return $this->render('security/forgot_password.html.twig');
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function reset(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if (!$user || $user->resetTokenExpiresAt < new \DateTime()) {
            $this->addFlash('danger', 'This password reset link is invalid or has expired.');
            return $this->redirectToRoute('app_forgot_password');
        }

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            if (strlen($password) < 6) {
                $this->addFlash('danger', 'Password must be at least 6 characters.');
                return $this->render('security/reset_password.html.twig', ['token' => $token]);
            }

            if ($password !== $confirmPassword) {
                $this->addFlash('danger', 'Passwords do not match.');
                return $this->render('security/reset_password.html.twig', ['token' => $token]);
            }

            $user->password = $passwordHasher->hashPassword($user, $password);
            $user->resetToken = null;
            $user->resetTokenExpiresAt = null;
            $em->flush();

            $this->addFlash('success', 'Password reset successfully. You can now log in.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', ['token' => $token]);
    }
}
