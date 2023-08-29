<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\GameList;
use App\Repository\GameListRepository;
use App\Form\GameListType;

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
        $profileName = $user->getUsername();
        $gameList = new GameList();
        $form = $this->createForm(GameListType::class, $gameList);
        $displayForm = false;
        if ($this->getUser() && $this->getUser()->getUsername() === $profileName) {
            $gameList->setOwner($this->getUser()->getUsername());
            $displayForm = true;
        }

        return $this->render('list/profile.html.twig', [
            'user' => $profileName,
            'lists' => $listRepository->findBy(['owner' => $profileName], ['createdAt' => 'DESC']),
            'display_form' => $displayForm,
            'list_form' => $form,
        ]);
    }
}
