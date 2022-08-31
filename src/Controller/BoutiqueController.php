<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoutiqueController extends AbstractController
{
    #[Route('/', name: 'app_boutique')]
    public function index(ProduitRepository $repo): Response
    {
        $produits = $repo->findBy([], ['dateEnregistrement' => 'DESC'], 4);
        return $this->render('boutique/index.html.twig', [
            'tabProduits' => $produits,
        ]);
    }
    
    #[Route('/produit/{id}', name: 'app_show')]
    public function show($id, ProduitRepository $repo): Response
    {
        $produit =  $repo->find($id);

        
        return $this->renderForm('boutique/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/compte', name: 'app_compte')]
    public function compte(): Response
    {
        $user = $this->getUser();
        return $this->render('boutique/compte.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/produits', name: 'app_liste')]
    public function liste(Request $request, ProduitRepository $repo,): Response
    {
        $tri = ($request->query->get('tri'));
        if ($tri==null)
        {
            $produits = $repo->findBy([], ['dateEnregistrement' => 'DESC']);
        }
        else
        {
            $tri = explode('-', $tri);
            $produits = $repo->findBy([], [$tri[0] => $tri[1]]);
        }

        return $this->render('boutique/produits.html.twig', [
            'tabProduits' => $produits,
        ]);
    }
}
