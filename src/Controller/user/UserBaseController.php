<?php

namespace App\Controller\user;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserBaseController
 * @package App\Controller\user
 * @Route ("/user", name="user_")
 */
class UserBaseController extends AbstractController
{
    /**
     * @return Response
     * @Route ("/", name="index")
     */
    public function index():Response
    {
        return $this->render('home/user/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
