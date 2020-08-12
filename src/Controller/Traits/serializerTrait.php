<?php
namespace App\Controller\Traits;
//use App\Controller\Math\Quartile;
use App\Math\Quartile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;


trait serializerTrait
{
    /**
     * @param $reviews
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    private function overtimeSerializer($reviews, SerializerInterface $serializer){
        $return_reviews = [];
        if(!empty($reviews)){
            if(array_key_exists('day', $reviews[0])){
                $return_reviews['group'] = 'daily';
                $return_reviews['count_of_days'] = count($reviews);
            }elseif (array_key_exists('week', $reviews[0])){
                $return_reviews['group'] = 'weekly';
                $return_reviews['count_of_weeks'] = count($reviews);
            }elseif (array_key_exists('month', $reviews[0])){
                $return_reviews['group'] = 'monthy';
                $return_reviews['count_of_months'] = count($reviews);
            }
            $return_reviews['reviews'] = $reviews;

        }else{
            $return_reviews['count_of_reviews'] = 0;
        }

        $serializedReviews = $serializer->serialize($return_reviews,'json');
        return JsonResponse::fromJsonString($serializedReviews);
    }


    /**
     * @param $hotel_reviews
     * @param $other_reviews
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    private function benchMarkSerializer($hotel_reviews,$other_reviews, SerializerInterface $serializer){
        $requested_hotel_id = $hotel_reviews[0]['hotel_id'];
        $scores = [];
        $sum = 0;
        foreach ($other_reviews as $key => &$review){

            array_push($scores, (float)$review['hotel_score']);
            if($requested_hotel_id == $review['hotel_id']){
                unset($other_reviews[$key]);
                continue;
            }
            $sum += $review['hotel_score'];
        }
        $quartile = new Quartile($scores);
        $Q1 = $quartile->Quartile_25();
        $Q2 = $quartile->Quartile_50();
        $Q3 = $quartile->Quartile_75();

        $return_reviews = [];
        $return_reviews['hotel_average'] = $sum / count($other_reviews);
        $return_reviews['requested_hotel'] = [];
        $return_reviews['requested_hotel']['hotel_id'] = $requested_hotel_id;
        $return_reviews['requested_hotel']['score'] = $hotel_reviews[0]['hotel_score'];
        $return_reviews['requested_hotel']['flag'] =
            ($hotel_reviews[0]['hotel_score'] < $Q1)? 'bottom':
                (($hotel_reviews[0]['hotel_score'] > $Q3)?'top':null);
        $serializedUser = $serializer->serialize($return_reviews,'json');
        return JsonResponse::fromJsonString($serializedUser);
    }

}