<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdersDriver extends Controller
{

    public function __invoke($driver, $date)
    {
        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository(Order::class)->findByDriverAndDate($driver, $date);

        return [
            'status' => 'OK',
            'description' => 'Return orders to complete on: '.$date,
            'data' => $orders,
        ];
    }
}