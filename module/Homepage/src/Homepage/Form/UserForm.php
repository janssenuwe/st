<?php
namespace Homepage\Form;

use Zend\Form\Form;
class UserForm extends Form
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
                'empty_option' => 'Bitte wählen',
                'value_options' => array(
                    'Frau' => 'Frau', 
                    'Herr' => 'Herr'
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
                'empty_option' => '-',
                'value_options' => array(
                    'Doktor'    => 'Doktor', 
                    'Professor' => 'Professor'
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
                'empty_option' => 'Bitte wählen',
                'value_options' => array(
                    'DE'  => 'Deutschland',
                    'AT'  => 'Österreich',
                    'CH'  => 'Schweiz',
                    'LI'  => 'Liechtenstein',
                    'IT'  => 'Italien'
                ),
            ),
            'attributes' => array(
                'class' => 'form-control dropdown-toggle',
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
                'empty_option' => 'Bitte wählen',
                'value_options' => array(
                    '0049' => '0049 (Deutschland)',
                    '0664' => '0664 (Österreich)',
                    '0041' => '0041 (Schweiz)',
                    '0423' => '0423 (Liechtenstein)',
                    '0039' => '0039 (Italien)',
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

        
//        $this->add(array(
//            'type' => 'Zend\Form\Element\Captcha',
//            'name' => 'user_captcha',
//            'options' => array(
//                    'label' => 'Sicherheitsfrage *',
//                    'captcha' => new Captcha\Dumb(),
//            ),
//        ));
        
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