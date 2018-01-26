<?php
namespace Homepage\Form;

use Zend\Form\Form;
class WordForm extends Form
{
   public function __construct($name = null)
   {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');
   
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'ersatz',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'update_submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Aktualisieren',
                'id' => 'submitbutton',
                'class' => 'btn btn-default',
            ),
        )); 
        
        $this->add(array(
            'name' => 'delete_submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Löschen',
                'id' => 'submitbutton',
                'class' => 'btn btn-default',
            ),
        )); 
    }
}
    
