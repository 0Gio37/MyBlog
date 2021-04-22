<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private string $confirm;

    public function __construct(EntityManagerInterface $entityManager, string $confirm='Opération effectuée avec succes !')
    {
        $this->entityManager = $entityManager;
        $this->confirm = $confirm;
    }


    /**
     * @Route("/article", name="article")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'confirm' => $this->confirm,
        ]);
    }

    /**
     * @Route("/article/list", name="list_articles")
     */
    public function articleList(): Response
    {
        $listArticle = $this->entityManager->getRepository(Article::class)->findAll();
        return $this->render('article/list-articles.html.twig', [
            'listArticle' => $listArticle,
        ]);
    }

    /**
     * @Route("/article/formulaire", name="form_crea_article")
     */
    public function newArticle(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return $this->redirectToRoute('article');
        }

        return $this->render('article/formulaire.html.twig', [
            'form_article' => $form->createView()
        ]);
    }

    /**
     * @Route ("/article/formulaire-modif-article/{id}", name="modif_article")
     */
    public function modifArticle(Request $request, $id): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();
            return $this->redirectToRoute('article');
        }

        return $this->render('article/formulaire.html.twig', [
            'form_article' => $form->createView()
        ]);
    }

    /**
     * @Route ("/article/supp/{id}", name="supp_article")
     */
    public function suppArticle($id): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);
        $el = $this->getDoctrine()->getManager();
        $el->remove($article);
        $el->flush();

        $listArticle = $this->entityManager->getRepository(Article::class)->findAll();
        return $this->render('article/list-articles.html.twig', [
            'listArticle' => $listArticle,
        ]);



    }




}
