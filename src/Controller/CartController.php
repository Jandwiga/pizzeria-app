<?php

namespace App\Controller;

use App\Form\CartType;
use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartManager $manager, Request $request): Response
    {
        $user = $this->getUser();
        $cart = $manager->getCurrentCart($user);
        $form = $this->createForm(CartType::class, $cart);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $cart->setUpdatedAt(new \DateTime());
            $manager->save($cart);
            return $this->render('cart/index.html.twig', [
                'cart' => $cart,
                'form' => $form->createView(),
            ]);
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $manager->getCurrentCart($user),
            'form' => $form->createView(),
        ]);
    }
}
