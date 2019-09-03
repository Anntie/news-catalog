<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/admin/api")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", methods={"GET", "HEAD"})
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        return new JsonResponse([
            'data' => array_map(function (Article $article) {
                return $article->toArray();
            }, $articles)
        ]);
    }

    /**
     * @Route("/articles", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function create(
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent());

        $article = new Article();
        $article->setTitle($data->title);
        $article->setSlug($data->slug);
        $article->setShortDescription($data->short_description);
        $article->setDescription($data->description);

        if ($data->category) {
            $category = $entityManager
                ->getRepository(Category::class)
                ->find($data->category);
            if ($category) {
                $article->setCategory($category);
            }
        }

        $errors = $validator->validate($article);
        if (count($errors) > 0) {
            return new JsonResponse([
                'errors' => (string) $errors
            ]);
        }

        $entityManager->persist($article);
        $entityManager->flush();

        return new JsonResponse([
            'data' => $article->toArray()
        ]);
    }

    /**
     * @Route("/articles/{id}", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function update(
        int $id,
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $article = $entityManager
            ->getRepository(Article::class)
            ->find($id);

        $data = json_decode($request->getContent());
        $article->setTitle($data->title);
        $article->setSlug($data->slug);
        $article->setShortDescription($data->short_description);
        $article->setDescription($data->description);

        $category = null;
        if ($data->category) {
            $category = $entityManager
                ->getRepository(Category::class)
                ->find($data->category->id);
        }
        $article->setCategory($category);

        $errors = $validator->validate($article);
        if (count($errors) > 0) {
            return new JsonResponse([
                'errors' => (string) $errors
            ]);
        }

        $entityManager->flush();

        return new JsonResponse([
            'data' => $article->toArray()
        ]);
    }

    /**
     * @Route("/articles/{id}", methods={"DELETE"})
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function delete(
        int $id,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $article = $entityManager
            ->getRepository(Article::class)
            ->find($id);

        $entityManager->remove($article);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}