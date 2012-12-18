<?php

namespace Movie\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Debug\Debug as Zend_Debug;

/**
 * Description of IndexController
 *
 * @author Kathryn
 */
class IndexController extends AbstractActionController 
{
    protected $movieTable;

    public function indexAction()
    {
        $userIdentity = $this->zfcUserAuthentication()->getIdentity();
        $userMovies = $this->getMovieTable()->findAllOwnedByUser(
          $userIdentity->getId()
        );

        /** @var \ZendService\Amazon\Amazon $amazon **/
        $amazon = $this->getServiceLocator()->get('ZendService\Amazon\Amazon');
        $items = $amazon->itemSearch(
            array(
                'AssociateTag'   => $this->getServiceLocator()->get('amazon_associate_tag'),
                'SearchIndex'   => 'DVD',
                'Keywords'      => 'Spirit',
                'ResponseGroup' => 'Medium'
            )
        );
        
        foreach($items as $item) {
            echo "<img src='".$item->SmallImage->Url."' />";
        }
        return new ViewModel(array(
            'movies' => $userMovies,
        ));
    }

    public function getMovieTable()
    {
        if (!$this->movieTable) {
            $sm = $this->getServiceLocator();
            $this->movieTable = $sm->get('MovieSource');
        }
        return $this->movieTable;
    }
}
