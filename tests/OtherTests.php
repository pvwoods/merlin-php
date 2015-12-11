<?php

namespace Merlin;

require_once '../Merlin/Merlin.php';

class OtherTests extends \PHPUnit_Framework_TestCase {
    public function testVrec() {
        $v = new Vrec('f346904e7dcd43c521bff2e6dcfae21a', null , '20', new Filter('price', '<', '79.99'));
        $this->assertEquals(
                'id=f346904e7dcd43c521bff2e6dcfae21a&num=20&filter='.urlencode('exp=price:(:79.99)'),
                $v->__toString());

    }

    public function testAnnotations() {
        $s = new Search('reddress');
        $s->setUid('b1cb66795dfb0ff41fff56c841c0dea8');
        $s->setTqid('D9XmaeqdjAxrkDPX');
        $this->assertEquals('q=reddress&tqid=D9XmaeqdjAxrkDPX&uid=b1cb66795dfb0ff41fff56c841c0dea8', $s->getUrlString());
    }

    public function testFeedback() {
        $f = new Feedback('dfK4ZYyKt5', '53232');
        $this->assertEquals('qid=dfK4ZYyKt5&docids=53232', $f->getUrlString());
    }
}
