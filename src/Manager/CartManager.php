<?php
namespace App\Manager;

use App\Entity\Order;
use App\Entity\User;
use App\Factory\OrderFactory;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CartManager
 * @package App\Manager
 */
class CartManager
{
    /**
     * @var CartSessionStorage
     */
    private $cartSessionStorage;

    /**
     * @var OrderFactory
     */
    private $cartFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CartManager constructor.
     *
     * @param OrderFactory $orderFactory
     * @param EntityManagerInterface $entityManager
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        OrderFactory $orderFactory,
        EntityManagerInterface $entityManager,
        OrderRepository $orderRepository
    ) {
        $this->cartFactory = $orderFactory;
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param User|null $user
     * @return Order
     */
    public function getCurrentCart(?User $user): Order
    {
        $cart = $user->getCart();

        if (!$cart) {
            $cart = $this->cartFactory->create();
        }

        return $cart;
    }

    /**
     * Persists the cart in database and session.
     *
     * @param Order $cart
     */
    public function save(Order $cart): void
    {
        // Persist in database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        // Persist in session
    }
}