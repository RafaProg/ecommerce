<?php

namespace Hcode;

use Hcode\Page;

class PageAdmin extends Page {
    
    public function __construct($opts = array(), $tplDir = "/views/admin/") {
        
        parent::__construct($opts, $tplDir);
    
    }
    
}
