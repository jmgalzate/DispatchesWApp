<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelpController extends AbstractController
{

    #[Route('/help', name: 'help')]
    public function index (): Response {
        
        return $this->render('components/Help.html.twig', [
            'title' => 'Ayuda',
            'help' => 'Esta es la página de ayuda',
        ]);
    }
}