<?php

namespace Merlin;

require_once '../Merlin/Merlin.php';

//creates an instance of the Merlin search engine
$engine = new MerlinSearch('blackbird', 'dev', 'guest_dev');

// does a simple search for a dress
$r = $engine->search(new Search('dress'));
printResults($r);

//A query where we want 50 results starting from the 100th result
$r = $engine->search((new Search('dress'))->setStart(100)->setNum(50));
printResults($r);

//A query where we only want back the "id" and "title" fields
$r = $engine->search((new Search('red dress'))->addField('id')->addField('title'));
printResults($r);

//Get all fields including debug fields
$r = $engine->search((new Search('red dress'))->addField('[debug]'));
printResults($r);

//Filter by price < 100
$r = $engine->search((new Search('red dress'))->addFilter(new Filter('price', '<', 100)));
printResults($r);

//A query where we want red dresses in size '2P' or in size '4R' and
$s = new Search('red dress');
$s->addFilter((new Filter('sizes', '=', '2P', '2p2r'))->addOr('sizes', '=', '4R'));
$r = $engine->search($s);
printResults($r);

//A query where we want red dresses under $100 and the top 5 brands returned as facets
$s = new Search('red dress');
$s->addFilter(new Filter('price', '<', 100));
$s->addFacet(new EnumFacet('brand', 5));
$r = $engine->search($s);
printResults($r);

//A query where we want red dresses and the range of prices returned
$s = new Search('red dress');
$s->addFacet(new RangeFacet('price'));
$r = $engine->search($s);
printResults($r);

//A query where we want red dresses and a histogram of their
$s = new Search('red dress');
$s->addFacet(new HistFacet('price', 0, 500, 100));
$r = $engine->search($s);
printResults($r);

//A search with multiple keyed facets on the 'brand' field
$s = new Search('red dress');
$s->addFacet(new RangeFacet('price', 'price_range'));
$s->addFacet(new HistFacet('price', 0, 500, 100, 'price_hist'));
$r = $engine->search($s);
printResults($r);

//pass array of tags to exclude into the facet
$s = new Search('red dress');
$s->addFacet((new EnumFacet('brand', 200))->addEx('tag1')->addEx('tag2'));
$r = $engine->search($s);
printResults($r);

//search for 'red dress' with spelling correction turned off
$s = new Search('red dress');
$s->setCorrect(false);
$r = $engine->search($s);
printResults($r);

function printResults($r) {
    echo "Got {$r->results->numfound} result(s)\n";
    if ($r->results->numfound > 0) {
        var_dump($r->results);
    }
    echo "\n-------------\n";
}
