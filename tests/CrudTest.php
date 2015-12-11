<?php

namespace Merlin;

require_once '../Merlin/Merlin.php';

class CrudTest extends \PHPUnit_Framework_TestCase {
    private $engine;

    function __construct() {
        $this->engine = new MerlinCrud('blackbird', 'dev', 'guest_dev', 'c73c11c411b449e6a16755efd219cf18', 'guest@blackbird.am');
    }

    public function testCrud() {
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

        $r = $this->engine->upload($c);
        $this->assertEquals($r->status, 200);
        $r = $this->engine->update($c);
        $this->assertEquals($r->status, 200);
        $r = $this->engine->delete($c);
        $this->assertEquals($r->status, 200);
    }

}
