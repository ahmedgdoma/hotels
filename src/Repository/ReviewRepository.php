<?php

namespace App\Repository;

use App\Entity\Review;
use App\Repository\traits\GroupTrait;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    use GroupTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }


    /**
     * @param $hotel_id
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
    public function findOverTimeScore($hotel_id, $start_date, $end_date)
    {
        $diff_date = $start_date->diffInDays($end_date);
        $reviews = $this->findInDateRange($start_date, $end_date);
        $reviews = $this->findWhereHotelID($reviews, $hotel_id);
        $reviews =  $this->groupFoundScores($reviews, $diff_date);
        return $reviews->getQuery()
            ->getResult();

    }

    /**
     * @param $hotel_id
     * @param $start_date
     * @param $end_date
     * @param bool $where
     * @return mixed
     */
    public function findBenchMarK($hotel_id, $start_date, $end_date, $where = true)
    {
        $hotel_reviews = $this->findInDateRange($start_date, $end_date);
        $hotel_reviews = $this->findWhereOrWhereNotHotelID($hotel_reviews, $hotel_id, $where);
        $hotel_reviews = $this->groupByHotelID($hotel_reviews);
        return $hotel_reviews->getQuery()
            ->execute();

    }


    /**
     * @param $start_date
     * @param $end_date
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function findInDateRange($start_date, $end_date)
    {

        $reviews =  $this->createQueryBuilder('r')
            ->setParameter('start_date', $start_date)
            ->setParameter('end_date', $end_date)
            ->andWhere('DATE(r.created_date) >= :start_date')
            ->andWhere('DATE(r.created_date) <= :end_date');
        return $reviews;

    }


    /**
     * @param $reviews
     * @param $hotel_id
     * @param $where
     * @return mixed
     */
    private function findWhereOrWhereNotHotelID($reviews, $hotel_id, $where){
       if($where){
           return $this->findWhereHotelID($reviews, $hotel_id);
       }else{
           return $reviews;
       }
    }


    /**
     * @param $reviews
     * @param $hotel_id
     * @return mixed
     */
    private function findWhereHotelID($reviews, $hotel_id){
       return  $reviews
           ->setParameter('hotel_id', $hotel_id)
           ->andWhere('r.hotel = :hotel_id');
    }






}
