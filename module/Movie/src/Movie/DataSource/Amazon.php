<?php

namespace Movie\DataSource;

use Movie\DataSource\DataSourceInterface;
use Movie\Model\Movie as MovieModel;
use ZendService\Amazon\Amazon as AmazonService;

/**
 * Description of Amazon
 *
 * @author k.reeve
 */
class Amazon implements DataSourceInterface
{

    /**
     * Amazon Service connection
     * @var ZendService\Amazon\Amazon
     */
    protected $amazonService;

    /**
     * The Associate tag for the user
     * @var String
     */
    protected $amazonAssociateTag = '';

    public function __construct(AmazonService $amazonService, $associateTag)
    {
        $this->amazonService = $amazonService;
        $this->amazonAssociateTag = $associateTag;
    }

    public function fetchAll()
    {
        // amazon doesn't support this ??
        return array();
    }

    public function getMovie($id)
    {
        
    }

    public function findBy($keyword)
    {
        $items = $this->amazonService->itemSearch(
            array(
                'AssociateTag' => $this->amazonAssociateTag,
                'SearchIndex' => 'DVD',
                'Keywords' => $keyword,
                'ResponseGroup' => 'Medium'
            )
        );
        $movies = array();
        foreach ($items as $amazonItem) {
            \Zend\Debug\Debug::dump($amazonItem);
            $movie = new MovieModel;
            $movie->exchangeArray(array(
                'id' => $amazonItem->ASIN,
                'title' => $amazonItem->Title
            ));
            $movies[] = $movie;
        }
        return $movies;
    }

}
