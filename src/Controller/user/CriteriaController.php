<?php

namespace App\Controller\user;

use App\Repository\CriteriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserBaseController
 * @package App\Controller\user
 * @Route ("/user", name="user_")
 */
class CriteriaController extends AbstractController
{
    /**
     * @Route ("/criteria", name="criteria")
     */
    public function criteria(CriteriaRepository $criteriaRepository) :Response
    {
        $criterias = $criteriaRepository
            ->findAll();

        return $this->render(
            'home/user/criteria.html.twig',
            ['criterias' => $criterias]
        );
    }
}
