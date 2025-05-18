<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentForm;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'articles' => $articleRepository -> findAll()
        ]);
    }
    
    #[Route('/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'un nouveau commentaire
        $comment = new Comment();
        $comment->setArticle($article);

        // Création du formulaire
        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCratedAt(new \DateTimeImmutable());

            // Enregistrement du commentaire
            $entityManager->persist($comment);
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Votre commentaire a été publié avec succès !');

            // Redirection pour éviter le rechargement du formulaire
            return $this->redirectToRoute(
                'app_article_show',
                ['id' => $article->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        // Affichage de la vue
        return $this->render('home/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView(),
        ]);
    }
}

