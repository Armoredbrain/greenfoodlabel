<?php

namespace App\Controller\admin;

use App\Entity\Facility;
use App\Repository\FacilityRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index() : Response
    {
        return $this->redirectToRoute("admin_processing");
    }

    /**
     * @Route("/processing", name="processing")
     */
    public function waiting(FacilityRepository $facilityRepository) : Response
    {
        $facilities = $facilityRepository->findBy(
            ['status' => Facility::PROCESSING],
            ['createdAt' => 'DESC']
        );

        return $this->render('home/admin/processing.html.twig', [
            "facilities" => $facilities
        ]);
    }

    /**
     * @Route("/validated", name="validated")
     */
    public function validated(FacilityRepository $facilityRepository) : Response
    {
        $facilities = $facilityRepository->findBy(
            ['status' => Facility::VALID],
            ['createdAt' => 'DESC']
        );

        return $this->render('home/admin/validated.twig', [
            "facilities" => $facilities
        ]);
    }

    /**
     * @Route("/refused", name="refused")
     */
    public function refused(FacilityRepository $facilityRepository) : Response
    {
        $facilities = $facilityRepository->findBy(
            ['status' => Facility::REFUSE],
            ['createdAt' => 'DESC']
        );

        return $this->render('home/admin/refused.html.twig', [
            "facilities" => $facilities
        ]);
    }

    /**
     * @Route("/users", name="users")
     */
    public function users(UserRepository $userRepository) : Response
    {
        return $this->render('home/admin/usersList.html.twig', [
            "users" => $userRepository->findAll(),
        ]);
    }
}
