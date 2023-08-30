<?php

namespace App\Controller;

use App\Entity\Games;
use App\Entity\GameList;
use App\Entity\User;
use App\Form\GamesType;
use App\Repository\GamesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
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
        NotifierInterface $notifier,
    ): Response {
        // Modify List Members
        $defaultData = ['user' => 'Enter the username'];
        $memberForm = $this->createFormBuilder($defaultData)
            ->add('user', TextType::class)
            ->add('choice', ChoiceType::class, [
                'choices' => [
                    'Add' => true,
                    'Remove' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $memberForm->handleRequest($request);

        if (
            $memberForm->isSubmitted()
            && $memberForm->isValid()
            && $this->validateUser($memberForm->getData()['user'])
        ) {
            $data = $memberForm->getData();



            if ($data['choice']) {
                $this->addMember($gameList, $data['user']);
            }
            else {
                $this->removeMember($gameList, $data['user']);
            }
            return $this->redirectToRoute('game_list', [
                'slug' => $gameList->getSlug(),
            ]);
        } elseif ($memberForm->isSubmitted() && $memberForm->isValid()) {
            $notifier->send(new Notification("There was a problem! The user you mentioned is not in our database", ['browser']));
        }

        // Add a new Game to the list
        $game = new Games();
        $gameForm = $this->createForm(GamesType::class, $game);
        $gameForm->handleRequest($request);

        if ($gameForm->isSubmitted() && $gameForm->isValid()) {
            $game->setGameList($gameList);

            $this->entityManager->persist($game);
            $this->entityManager->flush();

            return $this->redirectToRoute('game_list', ['slug' => $gameList->getSlug()]);
        }

        return $this->render('game_list/index.html.twig', [
            'list' => $gameList,
            'games' => $gamesRepository->findBy(['gameList' => $gameList], ['score' => 'DESC']),
            'game_form' => $gameForm,
            'member_form' => $memberForm,
        ]);
    }

    private function addMember(
        GameList $gameList,
        string $member,
    ): void {
        $users = $gameList->getUsers();
        $users[] = $member;
        $gameList->setUsers($users);
        $this->entityManager->flush();
    }

    private function removeMember(
        GameList $gameList,
        string $member,
    ) :void {
        $users = $gameList->getUsers();
        $users = array_diff($users, array($member));
        $gameList->setUsers($users);
        $this->entityManager->flush();
    }

    private function validateUser(
        string $userCandidate
    ): bool {
        /**
         * Check whether user is present in database
         *
         * returns true if user is found, otherwise returns false
         */
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findBy(['username' => $userCandidate]);
        return (bool) $user;
    }
}