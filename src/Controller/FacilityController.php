<?php

namespace App\Controller;

use App\Entity\Facility;
use App\Entity\Type;
use App\Form\FacilityType;
use App\Repository\TypeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacilityController extends AbstractController
{
    /**
     * @Route("user/facility/type", name="user_facility_type")
     */
    public function typeChoice(
        Request $request,
        TypeRepository $typeRepository,
        EntityManagerInterface $entityManager
    ) : Response {

        return $this->render('facility/facility_type_choice.html.twig', [
            'types' => $typeRepository->findAll()
        ]);
    }

    /**
     * @Route("user/facility/legal/{name}", name="user_facility_legal")
     */
    public function index(
        Type $type,
        Request $request,
        EntityManagerInterface $entityManager
    ) : Response {

        $facility = new Facility();
        $facilityForm = $this
            ->createForm(FacilityType::class, $facility)
            ->remove('user')
            ->remove('type');

        $facilityForm->handleRequest($request);

        if ($facilityForm->isSubmitted() && $facilityForm->isValid()) {
            $user = $this->getUser();
            $facility->setStatus(Facility::WAITING);
            $facility->setIsValid(false);
            $facility->setcreatedAt(new DateTime());
            $facility->setUser($user);
            $facility->setType($type);
            $entityManager->persist($facility);
            $entityManager->flush();
        }

        return $this->render('facility/facility.html.twig', [
            'facilityForm' => $facilityForm->createView(),
            'user' => $this->getUser(),
        ]);
    }
}
