<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentForm;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request ,ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        $query = $articleRepository -> createQueryBuilder('a')
                                    -> orderBy('a.id', 'DESC')
                                    -> getQuery();
        $pagination = $paginator -> paginate(
            $query,
            $request -> query -> getInt('page', 1),
            4
        );
        return $this->render('home/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
    
    #[Route('/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment->setArticle($article);

        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCratedAt(new \DateTimeImmutable());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été publié avec succès !');

            return $this->redirectToRoute(
                'app_article_show',
                ['id' => $article->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('home/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView(),
        ]);
    }
}

