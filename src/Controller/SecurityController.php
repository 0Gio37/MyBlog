<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

         if($this->isGranted('ROLE_ADMIN')) {
             return $this->redirectToRoute('admin');
         }



        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user =$form->getData();

            // encode le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $userMail = $user->getEmail();

            $this->entityManager->persist($user);
            //dd($userMail);
            $this->entityManager->flush();

            $message = (new TemplatedEmail())
                -> from('admin@myblog.fr')
                -> to($userMail)
                -> subject('Inscription réussie')
                ->htmlTemplate('mail/registerok.html.twig')
                ->context([
                    'user'=> $user,
                    'mail'=> $userMail
                ]);
            $mailer->send($message);
            return $this->redirectToRoute('info');
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
