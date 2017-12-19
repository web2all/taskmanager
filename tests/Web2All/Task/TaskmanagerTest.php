<?php
use PHPUnit\Framework\TestCase;

class Web2All_Task_TaskmanagerTest extends TestCase
{
  /**
   * Test load / compile
   * 
   */
  public function testTaskmanagerLoad()
  {
    $this->assertTrue(class_exists('Web2All_Task_Taskmanager', true), 'class Web2All_Task_Taskmanager exists');
  }

}
?>