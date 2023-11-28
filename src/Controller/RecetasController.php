<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetasController extends AbstractController
{
    #[Route('/recetas', name: 'app_recetas')]
    public function index(): Response
    {
        return $this->render('recetas/index.html.twig', [
            'controller_name' => 'RecetasController',
        ]);
    }
}
