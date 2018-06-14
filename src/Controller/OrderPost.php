<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\UserType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderPost extends Controller
{
    /**
     * @param Order $request
     * @return mixed
     */
    public function __invoke(Order $data)
    {
        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository(User::class);

        $data->setDriver($userRepository->getRandomDriver());

        $em->persist($data);
        $em->flush();

        return [
            'status' => 'OK',
            'description' => 'Return a saved order!',
            'data' => $data,
        ];
    }
}
