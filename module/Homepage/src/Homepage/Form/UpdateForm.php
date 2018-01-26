<?php
namespace Homepage\Form;

use Zend\Form\Form;

class UpdateForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'user_firmenname',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'user_anrede',
            'type' => 'select',  
            'options' => array(
                'value_options' => array(
                    '1' => ' ',
                    '2' => 'Frau', 
                    '3' => 'Herr'
                ),
             ),    
            'attributes' => array(
                'class' => 'form-control'
            ),
           
        ));
        
        $this->add(array(
            'name' => 'user_titel',
            'type' => 'select',  
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'value_options' => array(
                    '1' => ' ',
                    '2' => 'Doktor', 
                    '3' => 'Professor'
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'user_vorname',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_nachname',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_land',
            'type' => 'select',  
            'options' => array(
                'value_options' => array(
                    '1' => ' ',
                    '2' => 'Deutschland', 
                    '3' => 'Österreich',
                ),
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_strasse',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_hausnummer',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
        ));
        
        $this->add(array(
            'name' => 'user_plz',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_ort',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            )
        ));
        
        $this->add(array(
            'name' => 'user_email',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_passwort',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_laendervorwahl',
            'type' => 'select',  
            'options' => array(
                'value_options' => array(
                    '1' => '12345', 
                    '2' => '123456',
                ),
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_vorwahl',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_telefon',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-default'
            ),
        )); 
    }
}
