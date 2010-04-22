<?php

class Controller extends Controller_Core
{
    protected $links;
    protected $validation;

    public function  __construct()
    {
        parent::__construct();
        $this->validation = new Validation($_POST);
        $this->links = array('Главная' => url::base());
    }
    
    public function get_validation()
    {
        return $this->validation;
    }

    public function get_breadcrumbs()
    {
        $breadcrumbs = array();
        
        $get_breadcrumbs = html::breadcrumb($this->links);
        while (current($get_breadcrumbs))
        {
            // Check if we have reached the last crumb
            if (key($get_breadcrumbs) < (count($get_breadcrumbs) - 1))
            {
                // If we haven't, add a breadcrumb separator
                $breadcrumbs .= current($get_breadcrumbs) . ' / ';
            }
            else
            {
                // If we have, remove the anchor from the breadcrumb and make it bold
                $breadcrumbs .= strip_tags("<strong>" . current($get_breadcrumbs) . "</strong>", "<strong>");
            }
            next($get_breadcrumbs);
        }
        return $breadcrumbs;
    }
}