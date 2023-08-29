<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\GameListRepository;

class ListController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('list/index.html.twig', [
            'controller_name' => 'ListController',
        ]);
    }

    #[Route('/profile/{username}', name: 'profile')]
    public function showProfile(
        User $user,
        GameListRepository $listRepository,
    ): Response
    {
        $username = $user->getUsername();

        return $this->render('list/profile.html.twig', [
            'user' => $username,
            'lists' => $listRepository->findBy(['owner' => $username], ['createdAt' => 'DESC']),
        ]);
    }
}
