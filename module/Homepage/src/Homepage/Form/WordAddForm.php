<?php
namespace Homepage\Form;

use Zend\Form\Form;
class WordAddForm extends Form
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
            'name' => 'insert_submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Begriff erstellen',
                'id' => 'submitbutton',
                'class' => 'btn btn-default',
                
            ),
        )); 
 
    }
}
    
