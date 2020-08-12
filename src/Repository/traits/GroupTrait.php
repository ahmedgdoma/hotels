<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 8/12/20
 * Time: 2:49 AM
 */

namespace App\Repository\traits;


use App\Entity\Hotel;
use Doctrine\ORM\Query\Expr\Join;


trait GroupTrait
{
    /**
     * group score by Date | week | month
     * @param $reviews
     * @param $diff_date
     * @return mixed
     */
    private function groupFoundScores($reviews, $diff_date){
        if($diff_date <= 29){
            return $this->groupFoundScoresByDay($reviews);
        }if($diff_date > 29 && $diff_date <= 89){
            return $this->groupFoundScoresByWeek($reviews);
        }if( $diff_date > 89){
            return $this->groupFoundScoresByMonth($reviews);
        }
    }

    /**
     * @param $reviews
     * @return mixed
     */
    private function groupFoundScoresByDay($reviews){
        return $reviews
            ->select('DATE_FORMAT(r.created_date, \'%Y-%m-%d\') as day, AVG(r.score) as dayScore, COUNT(r.score) as count')
            ->groupBy('day')
            ->orderBy('day');


    }


    /**
     * @param $reviews
     * @return mixed
     */
    private function groupFoundScoresByWeek($reviews){
        return $reviews
            ->select('YEARWEEK(r.created_date) as week, AVG(r.score) as weeklyScore, COUNT(r.score) as count')
            ->groupBy('week')
            ->orderBy('week');

    }

    /**
     * @param $reviews
     * @return mixed
     */
    private function groupFoundScoresByMonth($reviews){
        return $reviews
            ->select('DATE_FORMAT(r.created_date, \'%Y-%m\') as month, AVG(r.score) as monthScore, COUNT(r.score) as count')
            ->groupBy('month')
            ->orderBy('month');


    }

    /**
     * @param $reviews
     * @return mixed
     */
    private function groupByHotelID($reviews){
        return $reviews->select(['AVG(r.score) as hotel_score, IDENTITY(r.hotel) as hotel_id'])
            ->join('App\Entity\Hotel','h' , 'with', 'r.hotel = h.id')
            ->groupBy('r.hotel')
            ->orderBy('hotel_score');
    }
}