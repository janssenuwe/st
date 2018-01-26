<?php
namespace Auth\Form;

use Zend\Form\Form;

class CompleteRegistrationForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');

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
            'name' => 'user_laendervorwahl',
            'type' => 'select',  
            'options' => array(
                'empty_option' => 'Bitte wählen',
                'value_options' => array(
                    '0049' => '0049 (Deutchland)',
                    '0664' => '0664 (Österreich)',
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
            'name' => 'user_submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-default'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'user_agb',
            'options' => array(
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => 'no'
            )
        ));
/*
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'user_datenschutz',
            'options' => array(
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => 'no'
            )
        ));
*/
    }
}