<?php
namespace Homepage\Model;

use Homepage\Model\FPDF;

class FPDFBehaviorProfiles extends FPDF
{
    private $strPostion             = '';
    private $strAddressText         = '';
    private $strJobText             = '';
    private $strPropertiesText      = '';
    private $strDemotivatedText     = '';
    private $strPositiveText        = '';
    private $strDepartment          = '';
    private $strCreateDate          = '';
    private $strLogoPath            = '';
    private $strLogoUrl             = '';
    private $strName                = '';
    private $strIdentifier          = '';
    private $strLocation            = '';
    private $strFunction            = '';
    private $intBuildYear           = '';

    private $arrMonth               = Array(
        '1'      => 'Januar',
        '2'      => 'Februar',
        '3'      => 'März',
        '4'      => 'April',
        '5'      => 'Mai',
        '6'      => 'Juni',
        '7'      => 'Juli',
        '8'      => 'August',
        '9'      => 'September',
        '10'     => 'Oktober',
        '11'     => 'November',
        '12'     => 'Dezember',
    );

    /**
     *
     * @param string $value
     */
    public function setPostion($value) {
        $this->strPostion           = $value;
    }
    
    /**
     *
     * @param string $value
     */
    public function setAddressText($value) {
        $this->strAddressText       = $value;
    }
    
    /**
     *
     * @param string $value
     */
    public function setJobText($value) {
        $this->strJobText           = $value;
    }
    
    /**
     * 
     * @param string $value
     */
    public function setPropertiesText($value) {
        $this->strPropertiesText    = $value;
    }
    
    /**
     *
     * @param string $value
     */
    public function setDemotivatedText($value) {
        $this->strDemotivatedText    = $value;
    }

    /**
     *
     * @param string $value
     */
    public function setPositiveText($value) {
        $this->strPositiveText    = $value;
    }

    /**
     *
     * @param string $value
     */
    public function setDepartment($value) {
        $this->strDepartment        = $value;
    }
    
    /**
     *
     * @param string $value
     */
    public function setCreateDate($value) {
        $this->strCreateDate        = $value;
    }
    
    /**
     * 
     * @param string $path
     * @param string $link
     */
    public function setLogo($path, $link) {
        $this->strLogoPath          = $path;
        $this->strLogoUrl           = $link;
    }



    /**
     *
     * @param int $value
     */
    public function setIdentifier($value) {
        $this->strIdentifier        = $value;
    }

    /**
     *
     * @param int $value
     */
    public function setName($value) {
        $this->strName              = $value;
    }
    
    /**
     *
     * @param int $value
     */
    public function setLocation($value) {
        $this->strLocation          = $value;
    }



    /**
     *
     * @param int $value
     */
    public function setFunction($value) {
        $this->strFunction          = $value;
    }
    
    /**
     *
     * @param int $value
     */
    public function setBuildYear($value) {
        $this->intBuildYear         = $value;
    }


    function TextWithDirection($x, $y, $txt, $direction='R')
    {
        $txt=str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
        if ($direction=='R')
            $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET', 1, 0, 0, 1, $x*$this->k, ($this->h-$y)*$this->k, $txt);
        elseif ($direction=='L')
            $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET', -1, 0, 0, -1, $x*$this->k, ($this->h-$y)*$this->k, $txt);
        elseif ($direction=='U')
            $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET', 0, 1, -1, 0, $x*$this->k, ($this->h-$y)*$this->k, $txt);
        elseif ($direction=='D')
            $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET', 0, -1, 1, 0, $x*$this->k, ($this->h-$y)*$this->k, $txt);
        else
            $s=sprintf('BT %.2f %.2f Td (%s) Tj ET', $x*$this->k, ($this->h-$y)*$this->k, $txt);
        if ($this->ColorFlag)
            $s='q '.$this->TextColor.' '.$s.' Q';
        $this->_out($s);
    }

    public function buildBehaviorProfiles()
    {
        
        $this->SetFont('Arial', '', 15);
        $this->SetDrawColor(0,0,0);
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        $this->SetLineWidth(1);
        $this->SetLeftMargin(25);
        $this->SetRightMargin(20);
        $this->SetAutoPageBreak(true);
         
        $intPageW   = ($this->w - $this->lMargin) - $this->rMargin;
        
        $this->Image($this->strLogoPath, $intPageW - 23.5, 15, 50, 9, 'PNG', $this->strLogoUrl, 'R');
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(0, 30, utf8_decode("powered bei www.JOBquick.com"), 0, 'R');

        $this->y     = $this->y-5;
        
        $this->SetFont('Arial', '', 12);
        $this->MultiCell($intPageW, 5, utf8_decode("Verhaltensprofil für"), 0, 'L');
        $this->SetFont('Arial', 'B', 16);
        $this->MultiCell($intPageW, 5, utf8_decode($this->strPostion), 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->MultiCell($intPageW, 4, utf8_decode("Abteilung: ".$this->strDepartment."\n".
                                                   "Standort: ".$this->strLocation."\n".
                                                   "Funktion: ".$this->strFunction."\n".
                                                   "Erstellt am ".date('d. ', strtotime($this->strCreateDate)).$this->arrMonth[date('n', strtotime($this->strCreateDate))].date(' Y G:i', strtotime($this->strCreateDate))." Uhr von ".$this->strName), 0, 'L');
        
        $this->y     = $this->y+10;
        
        $this->SetFont('Arial', 'B', 16);
        $this->MultiCell($intPageW, 5, 'Ansprache Kandidat', 0, 'L');
        
        $this->SetFont('Arial', '', 12);
        $this->MultiCell($intPageW, 5, utf8_decode($this->strAddressText), 0, 'L');
        
        $this->y     = $this->y+10;

        $this->SetFont('Arial','B',16);
        $this->MultiCell($intPageW, 5, 'Kurzbezeichnung der Aufgabe');
        
        $this->SetFont('Arial', '', 12);
        $this->MultiCell($intPageW, 5, utf8_decode($this->strJobText), 0, 'L');
        
        $this->y     = $this->y+10;
        
        $this->SetFont('Arial','B',16);
        $this->MultiCell($intPageW, 5, 'Beschreibende Eigenschaften');
        
        $this->SetFont('Arial', '', 12);
        $this->MultiCell($intPageW, 5, utf8_decode($this->strPropertiesText), 0, 'L');
        
        $this->y     = $this->y+10;
        
        $this->SetFont('Arial','B',16);
        $this->MultiCell($intPageW, 5, 'Was frustriert und demotiviert den Kandidaten');
        
        $this->SetFont('Arial', '', 12);
        $this->MultiCell($intPageW, 5, utf8_decode($this->strDemotivatedText), 0, 'L');

        $this->y     = $this->y+10;

        $this->SetFont('Arial','B',16);
        $this->MultiCell($intPageW, 5, 'Wird motiviert durch Worte und Darstellungen wie');

        $this->SetFont('Arial', '', 12);
        $this->MultiCell($intPageW, 5, utf8_decode($this->strPositiveText), 0, 'L');
    }
    
    public function Footer()
    {
        $strYearText = '';
        
        if(date("Y") != $this->intBuildYear){
            $this->intBuildYear = date("Y");
        }
        $this->SetY(-15);
        $this->SetFont('Arial','',10);
        $this->Cell(0,10,utf8_decode("© ".$this->intBuildYear."$strYearText Thomas International GmbH - powered bei www.stellenanzeigen-texten.de"),0,0,'L');

        $this->SetFont('Arial','',8);
        $this->TextWithDirection(12, 288, utf8_decode($this->strIdentifier), 'U');
    }
}
