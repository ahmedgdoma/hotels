<?php

namespace App\Controller;

use App\Controller\Traits\serializerTrait;
use App\DTO\ValidateRequest;
use App\Entity\Hotel;
use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;


class ApiController extends AbstractController
{
    use serializerTrait;

    /**
     * @param $hotel_id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return ApiController|\Symfony\Component\HttpFoundation\Response
     */
    public function overtime($hotel_id, Request $request, SerializerInterface $serializer)
    {

        $DtoRequest = new ValidateRequest($request->query->all());
        if(!$DtoRequest->getStartDate() || !$DtoRequest->getEndDate()){
            return new JsonResponse('enter valid dates');
        }
        $start_date = $DtoRequest->getStartDate();
        $end_date = $DtoRequest->getEndDate();


        /* get data from database */
        $reviews = $this->getDoctrine()
            ->getRepository(Review::class)
            ->findOverTimeScore($hotel_id, $start_date, $end_date);
        return $this->overtimeSerializer($reviews, $serializer);
    }

    /**
     * @param $hotel_id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function benchMark($hotel_id, Request $request, SerializerInterface $serializer)
    {

        $DtoRequest = new ValidateRequest($request->query->all());
        if(!$DtoRequest->getStartDate() || !$DtoRequest->getEndDate()){
            return new JsonResponse('enter valid dates');
        }
        $start_date = $DtoRequest->getStartDate();
        $end_date = $DtoRequest->getEndDate();


        /* get data from database */
        $hotel_reviews = $this->getDoctrine()
            ->getRepository(Review::class)
            ->findBenchMarK($hotel_id, $start_date, $end_date);
        $other_reviews = $this->getDoctrine()
            ->getRepository(Review::class)
            ->findBenchMarK($hotel_id, $start_date, $end_date, false);
        return $this->benchMarkSerializer($hotel_reviews, $other_reviews,  $serializer);

    }


}
