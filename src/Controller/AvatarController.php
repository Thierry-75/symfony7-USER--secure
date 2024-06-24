<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AvatarController extends AbstractController
{
    #[Route('/avatar/edit/{id}', name: 'app_avatar.edit',methods:['GET','POST'])]
    public function edit(Avatar $avatar,Request $request,EntityManagerInterface $em,ValidatorInterface $validatorInterface,ImageService $imageService): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('avatar/index.html.twig', [
            'controller_name' => 'AvatarController',
        ]);
    }
}
