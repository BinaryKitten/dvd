<?php

namespace Movie\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
         return new ViewModel(array(
            'movies' => $this->getMovieTable()->fetchAll(),
        ));
    }

    public function getMovieTable()
    {
        if (!$this->movieTable) {
            $sm = $this->getServiceLocator();
            $this->movieTable = $sm->get('Movie\Model\MovieTable');
        }
        return $this->movieTable;
    }
}
