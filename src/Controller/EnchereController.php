<?php

namespace App\Controller;

use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnchereController extends AbstractController
{

    #[Route('/enchere', name: 'app_enchere')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); //verif auth

        $client = HttpClient::create(['verify_peer' => false, 'verify_host' => false]); //creation clien qui peut faire des requettes vers l'api sans verif certif ssl
        $res  = $client->request('GET', 'https://localhost:44362/Fournisseur/AllFounrisseurs');

        $allfournisseur = $res->toArray();

        $user = $this->getUser();//recup du nom d'user
        $Nom_Fournisseur=null;
        foreach ( $allfournisseur as $fournisseur)// relation entre user symfony et le fournisseur
        {
                if ($fournisseur['prenom'] == 'Mauris Eratus' ){
                    $Nom_Fournisseur = $fournisseur['prenom'];
                }
            if (strval($user->getUserIdentifier()) == 'romain')
            {
                $Nom_Fournisseur = (strval($user->getUserIdentifier()));
            }
        }
        if ($Nom_Fournisseur===null) // si le user ne correspond à  aucun fournisseur alors on revien sur la page de base
        {
            return $this->redirectToRoute('app_page');
        }

        //recuperation du panier global
        $res2 = $client->request('GET','https://localhost:44362/PanierGlobal/AllPanierGlobal');
        $allpanierglobal= $res2 ->toArray();
        $dernierPanierGlobal = end($allpanierglobal);
dump($dernierPanierGlobal['id']);
        //recuperation des reference que propose le fournisseur
        $res3 = $client->request('GET', 'https://localhost:44362/PanierGlobalDetail/GetReferenceByPanierGlobalAndFournisseur?ID_PANIER_GLOBAL=1&ID_FOURNISSEUR=1'); //appel a l'api
        $refbypanier = $res3->toArray();

        dump($refbypanier);
        dump($dernierPanierGlobal['id']);


        return $this->render('enchere/index.html.twig', [
            'controller_name' => 'EnchereController',
         // test de connection a l'api   'status' => strval($res->getStatusCode()),// creer un parametre status converti en string pour affichage en html
            'allfournisseur' => $allfournisseur,
            'refbypanier' => $refbypanier,
            'Nom_Fournisseur' => $Nom_Fournisseur
        ]);
    }

    #[Route('/enchere/ajouter', name: 'ajouter_enchere')]
    public function AjouterEnchere(): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); //verif auth

        $user = $this->getUser();
        if (strval($user->getUserIdentifier()) != 'romain')
        {
            return $this->redirectToRoute('app_page');
        }





        return $this->render('enchere/ajouter_enchere.html.twig', [
            'controller_name' => 'PageController',
            'user_name' => strval($user->getUserIdentifier()),

        ]);
    }

}
