<?php

namespace Fgsl\Form;

use Zend\Form\Element\Select;
use Zend\Form\Form;

abstract class AbstractForm extends Form
{
    protected function addElement($name, $type, $label = null, $attributes = array(), $options = array())
    {
        if ($type == 'select') {
            $element = new Select($name);
            $element->setLabel($label)
                    ->setAttributes($attributes)
                    ->setOptions($options);
        } else {
            $attributes['type'] = $type;

            if ($type == 'submit') {
                $attributes['value'] = $label;
            } else {
                $options['label'] = $label;
            }

            $element = array(
                'name'          => $name,
                'attributes'    => $attributes,
                'options'       => $options
            );
        }

        $this->add($element);
    }
}