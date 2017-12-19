<?php
use PHPUnit\Framework\TestCase;

class Web2All_Task_GoSchedulerTest extends TestCase
{
  /**
   * set up test environmemt
   */
  public static function setUpBeforeClass()
  {
    
  }

  /**
   * Test load / compile
   * 
   */
  public function testSchedulerLoad()
  {
    $this->assertTrue(class_exists('Web2All_Task_Scheduler_PhpCronScheduler', true), 'class Web2All_Task_Scheduler_PhpCronScheduler exists');
  }

  /**
   * Test instantiation
   * 
   */
  public function testSchedulerRun()
  {
    if (!class_exists('GO\Scheduler')) {
      $this->markTestSkipped(
        'The GO\Scheduler class is not available.'
      );
    }

    $cronscheduler=new GO\Scheduler();
    $scheduler = new Web2All_Task_Scheduler_PhpCronScheduler($cronscheduler);
    $storage = new Web2All_Task_Storage_Dummy();
    $taskmanager= new Web2All_Task_Taskmanager($storage,$scheduler);
    $taskmanager->run();
  }

  /**
   * Test instantiation
   * 
   */
  public function testGoScheduler()
  {
    if (!class_exists('GO\Scheduler')) {
      $this->markTestSkipped(
        'The GO\Scheduler class is not available.'
      );
    }

    $scheduler=new GO\Scheduler();
    $scheduler->raw('echo test')->at('* * * * *');
    $jobsran=$scheduler->run();
    $this->assertCount(1, $jobsran, 'GO\Scheduler->run() did not execute the number of expected jobs');
  }

}
?>