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

    protected $movieSource;

    public function indexAction()
    {
        //User Will always be logged in due to Event/onBootstrap in Movie\Module
        $userIdentity = $this->zfcUserAuthentication()->getIdentity();
        $userMovies = $this->getMovieTable()->findAllOwnedByUser(
            $userIdentity->getId()
        );

        $amazon = $this->getServiceLocator()->get('MovieSource\Amazon');
        $movies = $amazon->findBy('titan');

//        \Zend\Debug\Debug::dump($movies);
        echo '<ul>';
        foreach($movies as $amazonMovie) {
            printf('<li><img src="%1$s" alt="Small cover for %2$s" /><strong>%2$s</strong></li>', $amazonMovie->MediumImage->Url, $amazonMovie->Title);
        }
        echo '</ul>';
        return new ViewModel(array('movies' => $userMovies,));
    }

    public function getMovieTable()
    {
        if (!$this->movieSource) {
            $sm = $this->getServiceLocator();
            $this->movieSource = $sm->get('MovieSource');
        }
        return $this->movieSource;
    }

}
