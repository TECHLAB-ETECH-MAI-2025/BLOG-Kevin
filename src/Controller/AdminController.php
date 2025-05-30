<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin/index', name: 'app_admin')]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $userCount = $userRepository->count([]);
        $verifiedCount = $userRepository->count(['isVerified' => true]);
		$adminCount = $userRepository->countAdmins();

        return $this->render('admin/index.html.twig', [
			'userCount' => $userCount,
			'verifiedCount' => $verifiedCount,
			'adminCount' => $adminCount,
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/new', name: 'app_admin_users_new')]
    public function newUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(AdminUserForm::class, $user,[
                'is_new_user' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encoder le mot de passe
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setIsVerified(true);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été créé avec succès.');

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/user_form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/users/{id}/edit', name: 'app_admin_users_edit')]
		public function editUser(User $user, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
		{
			$form = $this->createForm(AdminUserForm::class, $user);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				// Si un nouveau mot de passe est fourni, l'encoder
				if ($plainPassword = $form->get('plainPassword')->getData()) {
					$user->setPassword(
						$passwordHasher->hashPassword(
							$user,
							$plainPassword
						)
					);
				}

				$entityManager->flush();

				$this->addFlash('success', 'L\'utilisateur a été modifié avec succès.');

				return $this->redirectToRoute('app_admin_users');
			}

			return $this->render('admin/user_form.html.twig', [
				'form' => $form->createView(),
				'user' => $user,
			]);
		}
}
