<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\DishRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderDishController extends AbstractController
{
    /**
     * @Route("/orders", name="orders")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([
           'tisch' => 'table1' 
        ]);
        // find in the records based on a give criteria which will be in an array
        return $this->render('order_dish/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/order/{id}", name="order")
     */
    public function order(DishRepository $dishRepository, $id)
    {
        $dish = $dishRepository->find($id);
        $order = new Order();
        $order->setTisch("table1");
        $order->setName($dish->getName());
        $order->setBnummer($dish->getId());
        $order->setPrice($dish->getPrice());
        $order->setStatus("often");

        // Entity Manager
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $this->addFlash('order', $order->getName(). ' was added to the order');
        return $this->redirect($this->generateUrl('menu'));

    }

    /**
     * @Route("/status/{id},{status}", name="status")
     */
    public function status($id, $status)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);
        $order->setStatus($status);
        // di na kailangan ng persists kapag edit
        $em->flush();
        return $this->redirect($this->generateUrl("orders"));
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function delete(OrderRepository $or, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $or->find($id);
        $em->remove($order);
        $em->flush();
        return $this->redirect($this->generateUrl("orders"));
    }
}
