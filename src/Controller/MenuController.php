<?php

namespace App\Controller;



use App\Entity\Product;
use App\Factory\OrderFactory;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/menu", name="menu_")
 */
class MenuController extends AbstractController
{


    /**
     * @Route("/", name="main")
     */
    public function index(ProductRepository $productRepository): Response
    {

        return $this->render('menu/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="detail")
     */
    public function details(Product $product, Request $request, CartManager $manager): Response
    {
        //!!!!!!!!!!!!!!!!HAVE SOME SECURITY ISSUES
        $form = $this->createForm(AddToCartType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $item = $form->getData();
            $item->setProduct($product);
            $cart = $manager->getCurrentCart($this->getUser());
            $cart->addItem($item);
            $cart->setUpdatedAt(new \DateTime());
            $this->getUser()->setCart($cart);
            $manager->save($cart);
            return $this->redirectToRoute('menu_main', ['id' => $product->getId()]);
        }

        return $this->render('menu/detail.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

}
