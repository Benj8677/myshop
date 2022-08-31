<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
