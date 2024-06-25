<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\Coordinate;
use App\Form\AvatarType;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvatarController extends AbstractController
{
    #[Route('/avatar/edit/{id}', name: 'app_avatar.edit',methods:['GET','POST'])]
    public function edit(Avatar $avatar,Coordinate $coordinate,Request $request,EntityManagerInterface $em,ValidatorInterface $validatorInterface,ImageService $imageService): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('app_login');
        }
        $coordinate = $em->getRepository(Coordinate::class)->findCoordinateByAvatar($avatar->getCoordinate());
        $form_avatar= $this->createForm(AvatarType::class,$avatar);
        $form_avatar->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validatorInterface->validate($avatar);
            if(count($errors)> 0){
                return $this->render('/avatar/edit_avatar.html.twig',['form_avatar'=>$form_avatar->createView(),'coordinate'=>$coordinate,'errors'=>$errors]);
            }
            if($form_avatar->isSubmitted() && $form_avatar->isValid()){
                $folder = "avatars";
                $nom = $avatar->getName();
                $imageService->delete($nom,$folder,36,36);
                $image = $form_avatar->get('avatar')->getData();
                $fichier = $imageService->add($image,$folder,36,36);
                $avatar->setName($fichier);
                $em->persist($avatar);
                $em->flush();
                $this->addFlash('success','Your avatar has been modified');
                return $this->redirectToRoute('app_main');
            }
        }
        return $this->render('/avatar/edit_avatar.html.twig', ['form_avatar'=>$form_avatar->createView(),'coordinate'=>$coordinate]);
    }
}
