<?php
namespace Homepage\Form;

use Zend\Form\Form;
class SearchForm extends Form
{
   public function __construct($name = null)
    {
        parent::__construct('searchform');
        $this->setAttribute('method', 'post');


        $this->add(array(
            'name' => 'entry',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'user_name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'user_adresse',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'user_email',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'user_firma',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                
                'id' => 'search_submitbutton',
                'class' => 'btn btn-default'
            ),
        )); 
    }
}
