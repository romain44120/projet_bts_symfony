<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_page')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); //verif auth
        $user = $this->getUser();
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',

            'user_name' => strval($user->getUserIdentifier()),
        ]);
    }

}
