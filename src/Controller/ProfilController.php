<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminType;
use App\Form\RegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/profil", name="profil")
     */
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);


        return $this->render('profil/index.html.twig', [
            'admin_form' => $form->createView(),
        ]);
    }
}
