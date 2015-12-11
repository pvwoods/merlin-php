<?php

namespace Merlin;

require_once '../Merlin/Merlin.php';

class MerlinTest extends \PHPUnit_Framework_TestCase {
    private $engine;
    private $crud;

    function __construct() {
        $this->engine = new MerlinSearch('blackbird', 'dev', 'guest_dev');
        $this->crud = new MerlinCrud('blackbird', 'dev', 'guest_dev', 'c73c11c411b449e6a16755efd219cf18', 'guest@blackbird.am');
    }

    public function testHosts() {
        $engineDev = new MerlinSearch('blackbird', 'dev', 'guest_dev');
        $this->assertEquals($engineDev->getHost(), 'search-dev.search.blackbird.am');
        $engineStage = new MerlinSearch('blackbird', 'stage', 'guest_dev');
        $this->assertEquals($engineStage->getHost(), 'search-staging.search.blackbird.am');
        $engineProd = new MerlinSearch('blackbird', 'prod', 'guest_dev');
        $this->assertEquals($engineProd->getHost(), 'search-prod.search.blackbird.am');
    }

    public function testSimpleQ() {
        $r = $this->engine->search(new Search('dress'));
        $this->assertEquals($r->results->numfound, 1);
        $this->assertEquals($r->results->hits[0]->id, '111f49eacc7dbc9ab2df53f8ce52ec64');
    }

    public function testSimpleQFields() {
        $s = new Search('dress');
        $s->addField('images');
        $r = $this->engine->search($s);
        $keys = array_keys(get_object_vars($r->results->hits[0]));
        $this->assertEquals(count($keys), 1);
        $this->assertContains('images', $keys);
    }

    public function testSort() {
        $s = new Search('');
        $s->addSort(new Sort('price', Sort::ORDER_ASC));
        $r = $this->engine->search($s);
        $this->assertEquals($r->results->numfound, 5);
        $this->assertEquals($r->results->hits[0]->price, '59.0 USD');
        $this->assertEquals($r->results->hits[4]->price, '178.0 USD');
    }

    public function testFiter() {
        $s = new Search('');
        $s->addFilter((new Filter('tags', '=', 'Women'))->addOr('tags', '=', 'dress pants'));
        $r = $this->engine->search($s);
        $this->assertEquals($r->results->hits[0]->id, 'f346904e7dcd43c521bff2e6dcfae21a');
        $this->assertEquals($r->results->hits[1]->id, 'c05ef333b5dbd9f31123a65221762395');
    }

    public function testMultiSearch() {
        $s = new MultiSearch();
        $s->addQc(new Qc('Halogen', new Filter('tags', '=', 'Women')));
        $s->addQc(new Qc('Weekday', new Filter('tags', '=', 'dress pants')));
        $r = $this->engine->multiSearch($s);

        $this->assertEquals($r->results->hits[1]->id, 'f346904e7dcd43c521bff2e6dcfae21a');
        $this->assertEquals($r->results->hits[0]->id, 'c05ef333b5dbd9f31123a65221762395');
    }

    public function testVrec() {
        $v = new Vrec('f346904e7dcd43c521bff2e6dcfae21a', null, '20', new Filter('price', '<=', '79.99'));
        $r = $this->engine->vrec($v);
        $this->assertEquals($r->results->doc->id, 'f346904e7dcd43c521bff2e6dcfae21a');
        $this->assertEquals($r->results->numfound, 0);
    }

    public function testTypeahead() {
        $t = new Typeahead('hal');
        $r = $this->engine->typeahead($t);
        $this->assertEquals($r->results->numfound, 16);
    }

    public function testFeedback() {
        $f = new Feedback('dfK4ZYyKt5', '53232');
        $r = $this->engine->feedbackClick($f);
    }

}
