<?php

require_once('simpletest/autorun.php');
require_once(dirname(__FILE__).'/../reevoo_mark.php');

class ReevooMarkDocumentTest extends UnitTestCase {
  function document(){
    return new ReevooMarkDocument(file_get_contents(dirname(__FILE__)."/example_document.cache"));
  }

  function test_should_be_a_valid_document(){
    $this->assertTrue($this->document()->isValidResponse());
  }
  
  function test_should_have_expired(){
    $this->assertTrue($this->document()->hasExpired());
  }
  
  function test_should_have_max_age(){
    $this->assertEqual(2545, $this->document()->maxAge());
  }
  
  function test_should_have_date(){
    $this->assertEqual(1249134093, $this->document()->date());
  }
}

class CurrentReevooMarkDocument extends ReevooMarkDocument {
  function date(){
    $age_of_example_document = 148;
    return time() - $this->maxAge() + $age_of_example_document;
  }
}

class NoneExpiredReevooMarkDocumentTest extends UnitTestCase {
  function document(){
    return new CurrentReevooMarkDocument(file_get_contents(dirname(__FILE__)."/example_document.cache"));
  }

  function test_should_be_a_valid_document(){
    $this->assertTrue($this->document()->isValidResponse());
  }
  
  function test_should_not_have_expired(){
    $this->assertFalse($this->document()->hasExpired());
  }
}

class ReevooMarkDocumentWithBlankLinesTest extends UnitTestCase {
  function document(){
    return new ReevooMarkDocument("HTTP/1.1 200 OK\nContent-Type: text/plain\n\na\n\nb\n");
  }

  function test_should_extract_the_body(){
    $this->assertEqual("a\n\nb\n", $this->document()->body());
  }
}


?>