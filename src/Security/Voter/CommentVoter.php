<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, $comment): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $comment instanceof \App\Entity\Comment;
    }

    protected function voteOnAttribute(string $attribute, $comment, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                // User author only can edit his comment
                return $user->getId() === $comment->getAuthor()->getId();
                break;
            case self::DELETE:
                // Admin or User author can delete comment
                return ($user->getId() === $comment->getAuthor()->getId() || in_array('ROLE_ADMIN', $user->getRoles()));
                break;
        }

        return false;
    }
}
