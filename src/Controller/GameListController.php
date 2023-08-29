<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\GameList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameListController extends AbstractController
{
    #[Route('/{slug}', name: 'game_list')]
    public function showList(
        GameList $gameList,
    ): Response
    {
        return $this->render('game_list/index.html.twig', [
            'list' => $gameList,
            ]);
    }
}
