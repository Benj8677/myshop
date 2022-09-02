<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cs): Response
    {
        $cartWithData = $cs->getCartWithData();
        $cs->setNbProduct();
        $cs->setTotalCart();

        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
        ]);
    }

    #[Route('/cartend', name: 'app_cart_end')]
    public function end(CartService $cs): Response
    {
        $cartWithData = $cs->getCartWithData();
        $cs->setNbProduct();
        $cs->setTotalCart();

        return $this->render('cart/end.html.twig', [
            'items' => $cartWithData,
        ]);
    }

    #[Route('/cart/add/{id}', name:'cart_add')]
    public function add($id, CartService $cs)
    {
        $cs->add($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/minus/{id}', name:'cart_min')]
    public function decrement($id, CartService $cs)
    {
        $cs->minus($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name:'cart_remove')]
    public function remove($id, CartService $cs)
    {
        $cs->remove($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/removeAll', name:'cart_destroy')]
    public function removeAll(CartService $cs)
    {
        $cs->removeAll();
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/valid', name:'cart_valid')]
    public function validation(RequestStack $rs, CartService $cs, EntityManagerInterface $manager)
    {
        if ($this->getUser())
        {
            $session = $rs->getSession();
            $cartWithData = $cs->getCartWithData();
            $commande = new Commande;
            $qte = [];

            foreach ($cartWithData as $cart)
            {
                $commande->addProduit($cart['product']);
                $qte[$cart['product']->getId()] = $cart['quantity'];
            }

            $commande->setDateEnregistrement(new \DateTime());
            $commande->setUser($this->getUser());
            $commande->setQuantite($qte);
            $commande->setMontant($session->get('totalPrice'));
            $commande->setEtat(0);

            $manager->persist($commande);
            $manager->flush();

            $cs->removeAll();

            return $this->redirectToRoute('app_cart_end');
        }
    }
}
