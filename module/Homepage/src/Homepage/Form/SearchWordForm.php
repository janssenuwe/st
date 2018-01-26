<?php
namespace Homepage\Form;

use Zend\Form\Form;
class SearchWordForm extends Form
{
   public function __construct($name = null)
    {
        parent::__construct('searchform');
        $this->setAttribute('method', 'post');
  

        $this->add(array(
            'name' => 'wort_name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'wort_ersatz',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'wort_bearbeitet',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes'    => array(
                'type'      => 'submit',
                'value'     => 'Suchen',
                'id'        => 'search_submitbutton',
                'class'     => 'btn btn-default'
            ),
        )); 
    }
}
