<?php

namespace Movie\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\ArraySerializableInterface;

class Movie implements
    InputFilterAwareInterface,
    ArraySerializableInterface
{

    const NULL_MOVIE_ID = 0;

    public $movie_id;
    public $title;
    public $created_date;
    protected $inputFilter;

    /**
     * Used by ResultSet to pass each database row to the entity
     */
    public function exchangeArray(array $data)
    {
        $movie_id = (isset($data['movie_id'])) ? $data['movie_id'] :(isset($data['id'])) ? $data['id'] : null;
        $this->movie_id = $movie_id;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
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
