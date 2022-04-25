<?php

namespace App\Controller;

use App\Entity\EnchereFournisseur;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Enchere;
use App\Form\AddEnchereType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EnchereRepository;

class EnchereController extends AbstractController
{

    #[Route(path: '/enchere', name: 'app_enchere')]
    public function index(EnchereRepository $enchereRepo): Response
    {
        $this->denyAccessUnlessGranted(attribute: 'IS_AUTHENTICATED_FULLY'); //verif auth

        $client = HttpClient::create(defaultOptions: ['verify_peer' => false, 'verify_host' => false]); //creation clien qui peut faire des requettes vers l'api sans verif certif ssl
        $res  = $client->request(method: 'GET', url: 'https://localhost:44362/Fournisseur/AllFounrisseurs');

        $allfournisseur = $res->toArray();

        date_default_timezone_set('Europe/Paris');
        $dateactuelle = date('W');

        $user = $this->getUser();//recup du nom d'user
        $societe=null;
        foreach ( $allfournisseur as $fournisseur)// relation entre user symfony et le fournisseur
        {
                if ($fournisseur['prenom'] == strval(value: $user->getUserIdentifier() )){
                    $societe_Fournisseur = $fournisseur['societe'];
                    $Id_fournisseur = $fournisseur['id'];
                }
            if (strval(value: $user->getUserIdentifier()) == 'romain')
            {
                $societe_Fournisseur = (strval(value: $user->getUserIdentifier()));
                $Id_fournisseur = 0;
            }
        }
        if ($societe_Fournisseur===null) // si le user ne correspond à  aucun fournisseur alors on revien sur la page de base
        {
            return $this->redirectToRoute(route: 'app_page');
        }

        //recuperation du panier global
        $res2 = $client->request(method: 'GET', url: 'https://localhost:44362/PanierGlobal/AllPanierGlobal');
        $allpanierglobal= $res2 ->toArray();
        $dernierPanierGlobal = end(array: $allpanierglobal);

        $derniereenchere = $enchereRepo->findOneBy([], ['id' => 'DESC']);



        //recuperation des reference que propose le fournisseur
        $res3 = $client->request(method: 'GET', url: 'https://localhost:44362/PanierGlobalDetail/GetReferenceByPanierGlobalAndFournisseur?ID_PANIER_GLOBAL='.$dernierPanierGlobal['id'].'&ID_FOURNISSEUR='.$Id_fournisseur.''); //appel a l'api
        $refbypanier = $res3->toArray();



        return $this->render(view: 'enchere/index.html.twig', parameters: [
            'controller_name' => 'EnchereController',
         // test de connection a l'api   'status' => strval($res->getStatusCode()),// creer un parametre status converti en string pour affichage en html
            'allfournisseur' => $allfournisseur,
            'idfournisseur' => $Id_fournisseur,
            'refbypanier' => $refbypanier,
            'societe_Fournisseur' => $societe_Fournisseur,
            'encheres' => $derniereenchere,
            'enchere' => $enchereRepo ->findAll(),
            'dateactuelle' => $dateactuelle
        ]);
    }

    #[Route(path: '/enchere/ajouter_enchere', name: 'ajouter_enchere')]
    public function AjouterEnchere(\Symfony\Component\HttpFoundation\Request $request,EntityManagerInterface $entityManager,EnchereRepository $enchereRepo): Response
    {

        $this->denyAccessUnlessGranted(attribute: 'IS_AUTHENTICATED_FULLY'); //verif auth

        $user = $this->getUser();
        if (strval(value: $user->getUserIdentifier()) != 'romain')
        {
            return $this->redirectToRoute(route: 'app_page');
        }
        $client = HttpClient::create(defaultOptions: ['verify_peer' => false, 'verify_host' => false]); //creation clien qui peut faire des requettes vers l'api sans verif certif ssl
        //recuperation du panier global
        $res2 = $client->request(method: 'GET', url: 'https://localhost:44362/PanierGlobal/AllPanierGlobal');
        $allpanierglobal= $res2 ->toArray();
        $dernierPanierGlobal = end(array: $allpanierglobal);

        $encheres = $enchereRepo ->findAll();
        dump($encheres);
        foreach ($encheres as $temp){
            if (  $dernierPanierGlobal['id'] == $encheres['idPanierGlobal']){
                $tru=1;
            }
            dump($encheres);
        }

        $enchere = new Enchere();
        $form = $this->createForm(type: AddEnchereType::class, data: $enchere);
        $form->handleRequest(request: $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enchere = $form->getData();

            $entityManager->persist(entity: $enchere);
            $entityManager->flush();

            return $this->redirectToRoute(route: 'app_enchere');
        }
        return $this->render(view: 'enchere/ajouter_enchere.html.twig', parameters: [

            'allpanierglobal' => $allpanierglobal,
            'dernierPanierGlobal' => $dernierPanierGlobal,
            'form' => $form->createView(),

        ]);

    }

    #[Route(path: '/encherir', name: 'encherir')]
    public function encherir(Request $request, EntityManagerInterface $entityManager) {
        $parameters = json_decode($request->getContent(), true);




        $enchereFournisseur=new EnchereFournisseur();
        $enchereFournisseur->idEnchere = 1;
        $enchereFournisseur->prix = $parameters['prix'];
        $enchereFournisseur->fournisseur = "test";
        $enchereFournisseur->produit = $parameters['reference'];

        $entityManager->persist($enchereFournisseur);
        $entityManager->flush();


        return new JsonResponse(array('name' => $parameters['prix']));
    }

}
