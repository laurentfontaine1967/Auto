<?php

namespace App\Controller;



use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CguController extends AbstractController
{

    /**
     *@Route("/cgu", name="cgu")
     */
    public function ShowCgu()
    {

        return $this->render('cgu.html.twig');
    }
}