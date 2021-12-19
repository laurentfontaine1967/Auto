<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NosmarquesController extends AbstractController
{
    /**
     * @Route("/nosmarques", name="nosmarques")
     */
    public function index(): Response
    {
        return $this->render('nosmarques.html.twig', [
            'controller_name' => 'NosmarquesController',
        ]);
    }
}