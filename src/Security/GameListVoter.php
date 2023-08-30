<?php
namespace App\Security;

use App\Entity\GameList;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GameListVoter extends Voter
{
    const EDIT = 'edit';
    const VIEW = 'view';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::VIEW])) {
            return false;
        }

        if (!$subject instanceof GameList) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // User must be logged in; if not deny access
            return false;
        }

        /** @var GameList $gameList */
        $gameList = $subject;

        return match($attribute) {
            self::VIEW => $this->canView($gameList, $user),
            self::EDIT => $this->canEdit($gameList, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(GameList $gameList, User $user): bool
    {
        if ($this->canEdit($gameList, $user)) {
            return true;
        }

        return (in_array($user->getUsername(), $gameList->getUsers()));
    }

    private function canEdit(GameList $gameList, User $user): bool
    {
        return $user->getUsername() === $gameList->getOwner();
    }
}