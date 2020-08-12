<?php

namespace App\DTO;

use Carbon\Carbon;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidateRequest extends AbstractRequest
{

    protected $start_date;

    protected $end_date;

    /**
     * @param array $options - array of options to be resolved
     */
    public function __construct(array $options)
    {
        // this is only for example,
        // I usually have UserSession object wich implements UserInterface
        // UserSession isn't doctrine entity, it just data container which hydrates from JWT token
        // It contains user Id, some other information (isAdmin flag for example) and so on.

        parent::__construct($options);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['start_date', 'end_date']);
        $resolver->setNormalizer('start_date', function (Options $options, $value) {
            try {
                Carbon::createFromFormat('Y-m-d', $value);
                $value = Carbon::parse($value);
                $this->setStartDate($value);
            } catch (\Exception $exception){
                $this->setStartDate(false);
            }

        });
        $resolver->setNormalizer('end_date', function (Options $options, $value) {
            try {
                Carbon::createFromFormat('Y-m-d', $value);
                $value = Carbon::parse($value);
                $this->setEndDate($value);
            } catch (\Exception $exception){
                $this->setStartDate(false);
            }

        });
        unset($this->options);

    }


    public function getStartDate()
    {
        return $this->start_date;
    }

    public function getEndDate()
    {
        return $this->end_date;
    }
    public function setStartDate($value)
    {
        return $this->start_date = $value;
    }

    public function setEndDate($value)
    {
        return $this->end_date = $value;
    }
}
