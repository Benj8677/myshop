<?php

namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $rs;
    private $repo;

    public function __construct(RequestStack $rs, ProduitRepository $repo)
    {
        $this->rs = $rs;
        $this->repo = $repo;
    }

    public function add($id)
    {
        $session = $this->rs->getSession();

        $cart = $session->get('cart', []);

        if (!empty($cart[$id]))
        {
            $cart[$id]++;
        }
        else
        {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
    }

    public function minus($id)
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        if (!empty($cart[$id]))
        {
            if ($cart[$id]==1)
            {
                unset($cart[$id]);
            }
            else
            {
                $cart[$id]--;
            }
        }

        $session->set('cart', $cart);
    }

    public function remove($id)
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        if (!empty($cart[$id]))
        {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);
    }

    public function removeAll()
    {
        $session = $this->rs->getSession();
        $session->remove('cart');
        $session->remove('totalPrice');
        $session->remove('totalQuantity');
        //$session->clear();
    }

    public function setNbProduct()
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);
        $qtyTotal = 0;

        foreach($cart as $quantite)
        {
            $qtyTotal += $quantite;
        }
        $session->set('totalQuantity', $qtyTotal);
    }

    public function setTotalCart()
    {
        $session = $this->rs->getSession();
        $cartWithData = $this->getCartWithData();

        $total = 0;
        foreach($cartWithData as $item)
        {
            $total += $item['quantity'] * $item['product']->getPrix();
        }
        $session->set('totalPrice', $total);
    }

    public function getCartWithData()
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        $cartWithData = [];

        foreach($cart as $id => $quantite)
        {
            $cartWithData[] = [
                'product' => $this->repo->find($id),
                'quantity' => $quantite
            ];
        }

        return $cartWithData;
    }
}