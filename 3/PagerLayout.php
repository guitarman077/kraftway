<?php

class Helper_PagerLayout extends Zend_View_Helper_Abstract
{
    private static $_called = false;
    private $_pager  = null;
    private $_layout = null; 
    
    private $_selectedTemplate  = '<li class="active"><a href="{%url}">{%page}</a></li>';
    private $_separatorTemplate = '';
    private $_template = '<li><a href="{%url}">{%page}</a></li>';
    
    
    /**
    * @param array $options - опции пагинатора:
    * style = 'sliding' (default) | 'jumping'  - способ скрытия лишних ссылок на страницы 
    * chunk = 10 - сколько одновременно показывать ссылок на страницы
    * 
    * @return Helper_PagerLayout
    */
    public function PagerLayout($options=array())
    {
        $chunk = isset($options['chunk']) ? $options['chunk'] : 10;
        $style = isset($options['style']) ? $options['style'] : 'Sliding';
          
        $rangeClass = "Doctrine_Pager_Range_" . ucfirst($style); 
        $range = new $rangeClass(array("chunk"=>$chunk));
        
        $this->_pager = $pager = Shared_Paginator::getInstance()->getPager();
        $url   = Shared_Paginator::getInstance()->getUrl();
        $this->_layout = $layout = new Doctrine_Pager_Layout( $pager, $range, $url );
        
        $layout->setSelectedTemplate( $this->_selectedTemplate );
        $layout->setSeparatorTemplate( $this->_separatorTemplate );
        $layout->setTemplate( $this->_template );
        
        if( $pager->getLastPage() == 1 ) return ''; 
        
        $return = $this->jsCode() 
                . '<div class="pull-right pagination-wrap">'
                . '<ul class="pagination">'
                . $this->prevPage()                 //prev page
                . $layout->display(array(), true)   // pages   work
                . $this->nextPage()                // next page
                . '</ul>'
                . '</div>';

        self::$_called = true;
        return $return;
    }

    private function nextPage()
    {
        // Next page
        if( $this->_pager->getPage() != $this->_pager->getNextPage() ) {
            $this->_layout->addMaskReplacement('page', '&raquo;', true);
            $o['page_number'] = $this->_pager->getNextPage();
            $str = $this->_layout->processPage($o);
            $this->_layout->removeMaskReplacement('page');
            return $str;
        }
        return '';
    }
    
    private function prevPage()
    {
        // Previous page
        if( $this->_pager->getPage() != $this->_pager->getPreviousPage() ) {
            $this->_layout->addMaskReplacement('page', '&laquo;', true);
            $o['page_number'] = $this->_pager->getPreviousPage();
            $str = $this->_layout->processPage($o);
            $this->_layout->removeMaskReplacement('page'); 
            return $str;    
        }
        return '';  
    }
    
    private function jsCode()
    {
        if (TRUE || self::$_called) return '';
        
/*        return <<<DOC
        <script type="text/javascript">
        $(function(){
            $(document).keydown(function(e){
               if( e.ctrlKey ){ if( e.keyCode == 39 ) { window.location=$(".nextpage a").attr('href'); }  if( e.keyCode == 37 ) { window.location=$(".prevpage a").attr('href'); }
               }
            });
        });
        </script>
DOC;*/
    }
}    