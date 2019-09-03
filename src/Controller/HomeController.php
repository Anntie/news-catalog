<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class HomeController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function home()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        return $this->render('home.html.twig', compact('articles'));
    }

    /**
     * @Route("/articles/{slug}", methods={"GET"})
     * @ParamConverter("article", options={"mapping"={"slug"="slug"}})
     * @param Article $article
     * @return Response
     */
    public function showArticle(Article $article): Response
    {
        return $this->render('article.html.twig', compact('article'));
    }

}