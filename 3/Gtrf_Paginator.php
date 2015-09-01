<?php
/**
 *  Класс-связка между моделью и помощником вида, в архитектуре ZF+Doctrine (singleton);
 *  
 */
Class Gtrf_Paginator
{
    private $_pageNumber = null;
    private $_itemsPerPage = 10;
    private $_pager  = null;
    private $_url = '';

    private static $_instance = null;

    private function __construct(){
    }

    /**
     *
     * @return Gtrf_Paginator
     */
    public static function getInstance()
    {
        if( !self::$_instance ) {
            self::$_instance = new Gtrf_Paginator;
        }
        return self::$_instance;
    }

    public function setItemsPerPage($number)
    {
        $number = (int)$number;
        $this->_itemsPerPage = $number > 0 ? $number : 10;
        return self::$_instance;
    }

    public function setPageNumber( $pageNumber )
    {
        $pageNumber = (int)$pageNumber;
        $this->_pageNumber = $pageNumber > 0 ? $pageNumber : 1;
        return self::$_instance;
    }

    public function getPageNumber()
    {
        if( !$this->_pageNumber ) {
            $this->setPageNumber( Zend_Controller_Front::getInstance()->getRequest()->getParam('page', 1) );
        }
        return $this->_pageNumber;
    }

    public function setUrl($url)
    {
        $this->_url = preg_replace( '|\/$|', '', preg_replace( '|page\/\d+/?|is', '', $url )) . '/page/{%page_number}';
        return self::$_instance;
    }

    public function getUrl()
    {
        if( !$this->_url ) {
            $this->setUrl(Zend_Controller_Front::getInstance()->getRequest()->getRequestUri());
        }
        return $this->_url;
    }

    public function getPager()
    {
        if( !( $this->_pager instanceof Doctrine_Pager ) ) throw new Exception("Component Paginator not executed");
        return $this->_pager;
    }

    public static function execute($query , $params=array() , $countQuery = null)
    {
        $instance =self::getInstance();
        $instance->_pager  = new Doctrine_Pager( $query , $instance->getPageNumber() , $instance->_itemsPerPage );
        if ($countQuery) {
            $instance->_pager->setCountQuery($countQuery);
        }
        return $instance->_pager->execute($params);
    }

    public function getNumResults() {
        return (int) $this->getPager()->getNumResults();
    }
}