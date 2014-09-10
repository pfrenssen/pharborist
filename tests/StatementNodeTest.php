<?php

namespace Pharborist;

class StatementNodeTest extends \PHPUnit_Framework_TestCase {
  public function testGetLineCount() {
    $text = <<<'END'
class Foobar {

  protected $name;

  protected $age;

}
END;
    $this->assertEquals(7, Parser::parseSnippet($text)->getLineCount());
    // What, you haven't seen Spaceballs?
    $this->assertEquals(1, Parser::parseSnippet('$combination = 12345;')->getLineCount());


    $text = <<<'END'
db_delete('variable')
  ->condition('name', 'cron_last')
  ->execute();
END;
    $this->assertEquals(3, Parser::parseSnippet($text)->getLineCount());
  }
}