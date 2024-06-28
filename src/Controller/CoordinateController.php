<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\Coordinate;
use App\Form\CoordinateEditType;
use App\Form\CoordinateType;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CoordinateController extends AbstractController
{
    #[Route('/coordinate/new', name: 'app_coordinate.new',methods:['GET','POST'])]
    public function new(Request $request,EntityManagerInterface $em, ValidatorInterface $validator,ImageService $imageService): Response
    {  
        if($this->getUser()== null){
        return $this->redirectToRoute('app_login');
        }
         if($this->getUser()->getCoordinate() !=null){
            return $this->redirectToRoute('app_main');
         }
  
        $coordinate = new Coordinate();
        $form_coordinate = $this->createForm(CoordinateType::class,$coordinate);
        $form_coordinate->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($coordinate);
            if(count($errors)>0){
                return $this->render('/coordinate/new_coordinate.html.twig',['form_coordinate'=>$form_coordinate->createView() ,'errors'=>$errors]);
            }
            if($form_coordinate->isSubmitted() && $form_coordinate->isValid()){
                $avatar = $form_coordinate->get('avatar')->getData();
                $folder = "avatars";
                $fichier = $imageService->add($avatar,$folder,36,36);
                $img = new Avatar();
                $img->setName($fichier);
                $coordinate->setAvatar($img);
                $coordinate->setUser($this->getUser());
                $coordinate->setCompleted(true);

                $em->persist($coordinate);
                $em->flush();
                $this->addFlash('success','vos coordonnées ont été enregistrées !');
                return $this->redirectToRoute('app_main');
            }
        }
        return $this->render('/coordinate/new_coordinate.html.twig', ['form_coordinate'=>$form_coordinate->createView()]);     
    }
    #[Route('/coordinate/edit/{id}',name:'app_coordinate.edit',methods:['GET','POST'])]
    public function edit(Coordinate $coordinate,EntityManagerInterface $em,Request $request,ValidatorInterface $validator, ImageService $imageService): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('app_login');
        }
        $this->denyAccessUnlessGranted('coordinate_edit',$coordinate);
        
        $form_coordinate = $this->createForm(CoordinateEditType::class,$coordinate);
        $form_coordinate->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($coordinate);
            if(count($errors)>0){
                return $this->render('/coordinate/edit_coordinate.html.twig', ['form_coordinate'=> $form_coordinate->createView(),'errors' => $errors]);
            }
            if($form_coordinate->isSubmitted() && $form_coordinate->isValid()){
       
                $em->persist($coordinate);
                $em->flush();
                $this->addFlash('success','Votre profil a été modifié !');
                return $this->redirectToRoute('app_main');
            }
        }
        return $this->render('/coordinate/edit_coordinate.html.twig',['form_coordinate'=> $form_coordinate->createView(),'coordinate'=>$coordinate]);
    }

    #[Route('/coordinate/delete/avatar/{id}',name:'app_coordinate_avatar_delete',methods:['DELETE'])]
    public function deleteAvatar(Avatar $avatar,Request $request,EntityManagerInterface $em,ImageService $imageService): JsonResponse
    {
        // recup request
        $data = json_decode($request->getContent(),true);
        if($this->isCsrfTokenValid('delete' . $avatar->getId(), $data['_token'])){
            $nom = $avatar->getName();
            if($imageService->delete($nom,'avatars',36,36)){
                $em->remove($avatar);
                $em->flush();
                return new JsonResponse(['success'=>true],200);
            }
            //echec delete
            return new JsonResponse(['error'=>'Error deleted'],400);
        }
        // invalid token
        return new JsonResponse(['error'=>'Invalid token'],400);
    }
}
