<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Coordinate;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class CoordinateVoter extends Voter
{
    public const COORDINATE_NEW = 'coordinate_new';
    public const COORDINATE_EDIT = 'coordinate_edit';
    public const COORDINATE_DELETE = 'coordinate_delete';

    private $security;

    public function __construct(SecurityBundleSecurity $security)
    {
        $this->security = $security;        
    }

    protected function supports(string $attribute, $coordinate): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::COORDINATE_NEW,self::COORDINATE_EDIT, self::COORDINATE_DELETE])
            && $coordinate instanceof \App\Entity\Coordinate;
    }

    protected function voteOnAttribute(string $attribute,$coordinate, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // if admin
        if($this->security->isGranted('ROLE_ADMIN'))return true;
        
        // check if coordinate have an user
        if(null === $coordinate->getUser()) return false; 

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::COORDINATE_NEW:
                return $this->canNew($coordinate,$user);

            case self::COORDINATE_EDIT:
                // logic to determine if the user can EDIT
                return $this->canEdit($coordinate,$user);
                break;

            case self::COORDINATE_DELETE:
                // logic to determine if the user can DELETE
                 return $this->canDelete($coordinate,$user);
                break;
        }

        return false;
    }

    private function canNew(Coordinate $coordinate,User $user){
        return $user === $coordinate->getUser();
    }
    private function canEdit(Coordinate $coordinate, User $user){
            return $user === $coordinate->getUser();
    }

    private function canDelete(Coordinate $coordinate, User $user){
            return $user === $coordinate->getUser();
    }
}
