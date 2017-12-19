<?php
use PHPUnit\Framework\TestCase;

class Web2All_Task_DummyTest extends TestCase
{
  /**
   * Test instantiation and run
   * 
   */
  public function testDummyRun()
  {
    $scheduler = new Web2All_Task_Scheduler_Dummy();
    $storage = new Web2All_Task_Storage_Dummy();
    $taskmanager= new Web2All_Task_Taskmanager($storage,$scheduler);
    $taskmanager->run();
  }
}
?>