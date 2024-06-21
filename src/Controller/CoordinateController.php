<?php

namespace App\Controller;

use App\Entity\Coordinate;
use App\Form\CoordinateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CoordinateController extends AbstractController
{
    #[Route('/coordinate/new', name: 'app_coordinate.new',methods:['GET','POST'])]
    public function new(Request $request,EntityManagerInterface $em, ValidatorInterface $validator): Response
    {   if($this->getUser()== null){
        return $this->redirectToRoute('app_login');
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
}
