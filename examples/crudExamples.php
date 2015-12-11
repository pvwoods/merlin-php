<?php

namespace Merlin;

require_once '../Merlin/Merlin.php';

//creates an instance of the Merlin crud engine
$crud = new MerlinCrud('blackbird', 'dev', 'guest_dev', 'c73c11c411b449e6a16755efd219cf18', 'guest@blackbird.am');

// creates a Crud parameter and adds a subject
$c = new Crud();
$c->addSubject(
        array(
            'data' =>
            array(
                array(
                    'id' => '123',
                    'title' => 'shirt',
                    'images' => array('https://upload.wikimedia.org/wikipedia/commons/8/8c/Polo_Shirt_Basic_Pattern.png'),
                    'thumbnails' => array('https://upload.wikimedia.org/wikipedia/commons/8/8c/Polo_Shirt_Basic_Pattern.png')
                )
            )
        )
);

//uploads the subject
$r = $crud->upload($c);
var_dump($r);

// create the same subject but change the title
$c = new Crud();
$c->addSubject(
        array(
            'data' =>
            array(
                array(
                    'id' => '123',
                    'title' => 'plain shirt'
                )
            )
        )
);

// update the subject
$r = $crud->update($c);
var_dump($r);

//delete the subject
$r = $crud->delete($c);
var_dump($r);

