<?php

namespace Movie\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Stdlib\ArraySerializableInterface;

class MovieImage implements
    InputFilterAwareInterface,
    ArraySerializableInterface
{

    public $image_id;
    public $movie_id;
    public $url;
    public $width;
    public $height;
    public $created_date;
    protected $inputFilter;

    public function exchangeArray(array $array)
    {

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Zend\Stdlib\Exception\BadMethodCallException("Should Not be Used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                    'name' => 'movie_id',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Int'),
                    ),
                )));

            $inputFilter->add($factory->createInput(array(
                    'name' => 'title',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 255,
                            ),
                        ),
                    ),
                )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
