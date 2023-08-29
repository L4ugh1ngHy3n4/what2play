<?php

namespace App\Controller;

use App\Entity\Games;
use App\Entity\GameList;
use App\Form\GamesType;
use App\Repository\GamesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameListController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/game-list/{slug}', name: 'game_list')]
    public function showList(
        GameList $gameList,
        GamesRepository $gamesRepository,
        Request $request,
    ): Response
    {
        $game = new Games();
        $form = $this->createForm(GamesType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game->setGameList($gameList);

            $this->entityManager->persist($game);
            $this->entityManager->flush();

            return $this->redirectToRoute('game_list', ['slug' => $gameList->getSlug()]);
        }


        return $this->render('game_list/index.html.twig', [
            'list' => $gameList,
            'games' => $gamesRepository->findBy(['gameList' => $gameList], ['score' => 'DESC']),
            'game_form' => $form,
            ]);
    }
}
