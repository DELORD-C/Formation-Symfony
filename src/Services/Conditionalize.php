<?php

namespace App\Services;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Conditionalize {
    function __construct() {}

    function validate (FormInterface $form): bool
    {
        $data = $form->getData();

        foreach ($form->all() as $field) {
            $options = $field->getConfig()->getAttribute('data_collector/passed_options');
            if (
                isset($options['row_attr']) &&
                isset($options['row_attr']['option']) &&
                isset($options['row_attr']['data-cond-value']) &&
                isset($options['row_attr']['data-required']) &&
                $options['row_attr']['data-required'] == true
            ) {
                if (is_array($data)) {
                    $check = $data[$options['row_attr']['option']];
                }
                else {
                    $check = $data->{'get' . ucfirst($options['row_attr']['option'])}();
                }

                if (is_array($check)) {
                    $check = implode($check);
                }

                if (!preg_match('/' . $options['row_attr']['data-cond-value'] . '/', $check)) {
                    return false;
                }
            }
        }
        return true;
    }
}