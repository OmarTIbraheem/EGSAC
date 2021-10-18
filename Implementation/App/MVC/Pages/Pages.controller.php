<?php

    require_once 'Pages.view.php';
    require_once 'Pages.model.php';

    class PageCtrl
    {
        public $Header;
        public $Nav;
        public $Pages;

        public function __construct($Data)
        {
            $this->Pages = $Data;
            
            $Page = new Page();
            $this->Header = $Page->GetHeader();
            $this->Nav = $Page->GetNav();
            new Pages($this->Header, $this->Nav, $this->Pages);
        }
    }

    if (isset($_REQUEST['Page']))
    {
        // Get Page Name From Link
        $PageName = $_REQUEST['Page'];
            
        // Get All Pages From Database
        $Page = new Page();
        $Pages = $Page->GetPages();
        
        // Makes "PageName" Points To "PageHTML"
        foreach ($Pages as $P)
        {
            $Temp = [
                $P[0] => $P[1]
            ];
            $Data[] = $Temp;
        }
        $Data = call_user_func_array('array_merge', $Data);
        $Data = array($Data, $PageName);
        new PageCtrl($Data);
    }

?>