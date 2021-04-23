<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {

        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/home/info", name="info")
     */
    public function info(): Response
    {
        return $this->render('home/info.html.twig', [
            'firstname' => 'Messanges',
            'name' => 'Georges',
        ]);
    }




}
