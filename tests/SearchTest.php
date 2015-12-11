<?php

namespace Merlin;

require_once '../Merlin/Merlin.php';

class SearchTest extends \PHPUnit_Framework_TestCase {

    public function testSort() {
        $s = new Search('pants');
        $s->addSort(new Sort('brand', Sort::ORDER_DESC))
                ->addSort(new Sort('price', Sort::ORDER_ASC));
        $this->assertEquals($s->getUrlString(), 'q=pants&sort=' . urlencode('brand:desc,price:asc'));
    }

    public function testFilters() {
        $s = new Search('');
        $s->addFilter((new Filter('Color', '=', 'Red', null, Filter::TYPE_CNF))->addAnd('Color', '!=', 'Blue'));
        $this->assertEquals($s->getUrlString(), 'q=&filter=' . urlencode('exp=Color:Red,Color:!Blue/type=cnf'));
    }

    public function testFilterTags() {
        $s = new Search('');
        $s->addFilter((new Filter('Color', '=', 'Red', 'redandblue', Filter::TYPE_CNF))->addAnd('Color', '!=', 'Blue'));
        $this->assertEquals($s->getUrlString(), 'q=&filter=' . urlencode('exp=Color:Red,Color:!Blue/tag=redandblue/type=cnf'));
    }

    public function testMultiFilters() {
        $s = new Search('');
        $s->addFilter((new Filter('Color', '=', 'Red', null, Filter::TYPE_CNF))->addAnd('Color', '!=', 'Blue'));
        $s->addFilter((new Filter('Price', '<', 100, null, Filter::TYPE_DNF)));
        $this->assertEquals(
                $s->getUrlString(), 'q=&filter=' . urlencode('exp=Color:Red,Color:!Blue/type=cnf')
                . '&filter=' . urlencode('exp=Price:(:100)/type=dnf')
        );
    }

    public function testFilterEscapeChars() {
        $s = new Search('');
        $s->addFilter((new Filter('Color', '=', 'Red!\,|', null, Filter::TYPE_CNF))->addAnd('Color', '!=', 'Blue'));
        $this->assertEquals('q=&filter=' . urlencode('exp=Color:Red\!\\\\\,\|,Color:!Blue/type=cnf'), $s->getUrlString());
    }

    public function testEnumFacet() {
        $s = new Search('shirt');
        $s->addFacet(new EnumFacet('brand', 10, 'ponies'));
        $this->assertEquals('q=shirt&facet=' . urlencode('field=brand/type=enum/key=ponies/num=10'), $s->getUrlString());
    }

    public function testRangeFacet() {
        $s = new Search('shirt');
        $s->addFacet(new RangeFacet('price', 'prices'));
        $this->assertEquals('q=shirt&facet=' . urlencode('field=price/type=range/key=prices'), $s->getUrlString());
    }

    public function testHistFacet() {
        $s = new Search('shirt');
        $s->addFacet(new HistFacet('price', 10, 100, 5, 'prices'));
        $this->assertEquals('q=shirt&facet=' . urlencode('field=price/type=hist/key=prices/range=[10:100:5]'), $s->getUrlString());
    }

    public function testEnumFacetExcluding() {
        $s = new Search('shirt');
        $s->addFacet((new EnumFacet('brand', 10, 'ponies'))->addEx('foo')->addEx('bar'));
        $this->assertEquals('q=shirt&facet=' . urlencode('field=brand/type=enum/key=ponies/ex=foo,bar/num=10'), $s->getUrlString());
    }

    public function testMultipleFacets() {
        $s = new Search('shirt');
        $s->addFacet(new EnumFacet('brand', 10, 'top_brands'));
        $s->addFacet(new HistFacet('price', 0, 100, 10));
        $this->assertEquals(
                'q=shirt'
                . '&facet=' . urlencode('field=brand/type=enum/key=top_brands/num=10')
                . '&facet=' . urlencode('field=price/type=hist/range=[0:100:10]'), $s->getUrlString());
    }

    public function testGroup() {
        $s = new Search('hoodie');
        $s->setGroup(new Group('category', 10, new Sort('price', Sort::ORDER_ASC)));
        $this->assertEquals('q=hoodie&group=' . urlencode('field=category/sort=price:asc/num=10'), $s->getUrlString());
    }

    public function testGeo() {
        $s = new Search('hoodie');
        $s->setGeo(new Geo('geo', '37.774929', '-122.419416', 35));
        $this->assertEquals('q=hoodie&geo=' . urlencode('field=geo/pt=(37.774929,-122.419416)/d=35'), $s->getUrlString());
    }

    public function testMultiSearch() {
        $s = new MultiSearch();
        $s->addQc(new Qc('blouse',new Filter('sizes', '=', 34)));
        $s->addQc(new Qc('red shoes',new Filter('sizes', '=', 12)));
        $this->assertEquals(
                'qc=' . urlencode('q=blouse//filter=exp=sizes:34')
                .'&qc=' . urlencode('q=red shoes//filter=exp=sizes:12'),
                $s->getUrlString());
    }

}
