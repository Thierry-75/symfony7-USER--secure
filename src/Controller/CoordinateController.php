<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoordinateController extends AbstractController
{
    #[Route('/coordinate', name: 'app_coordinate')]
    public function index(): Response
    {
        return $this->render('coordinate/index.html.twig', [
            'controller_name' => 'CoordinateController',
        ]);
    }
}
