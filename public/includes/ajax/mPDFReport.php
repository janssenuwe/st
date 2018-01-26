<?php
require_once('../../mpdf/mpdf.php');

class mPDFReport extends mPDF
{
    function Header($content='')
    {
        if($this->page == 1)
            return;

        parent::Header($content);
    }

    function Footer()
    {
        if($this->page == 1)
            return;

        parent::Footer();
    }
}