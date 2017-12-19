<?php
use PHPUnit\Framework\TestCase;

class Web2All_Task_CronStorageTest extends TestCase
{
  /**
   * Test con storage loading
   * 
   */
  public function testCronStorage()
  {
    $cronstorage = new Web2All_Task_Storage_CronFile(__DIR__ . '/sample_cron.txt');
    $tasks = $cronstorage->getTasks();
    $this->assertCount(6, $tasks, 'CronFile sample_cron.txt did not return the expected number of tasks');
    $this->assertEquals('date', $tasks[0]->command, 'Task 1 has the wrong command');
    $this->assertEquals('10 6 * * *', $tasks[0]->schedule, 'Task 1 has the wrong schedule');
    $this->assertEquals('date', $tasks[1]->command, 'Task 2 has the wrong command');
    $this->assertEquals('date', $tasks[2]->command, 'Task 3 has the wrong command');
    $this->assertEquals('date', $tasks[3]->command, 'Task 4 has the wrong command');
    $this->assertEquals('date >> /var/log/date-output 2>&1', $tasks[4]->command, 'Task 5 has the wrong command');
    $this->assertEquals('date', $tasks[5]->command, 'Task 6 has the wrong command');
  }

  /**
   * Test con storage loading
   * 
   */
  public function testCronStorageComments()
  {
    $cronstorage = new Web2All_Task_Storage_CronFile(__DIR__ . '/sample_cron_comments.txt');
    $tasks = $cronstorage->getTasks();
    $this->assertCount(7, $tasks, 'CronFile sample_cron_comments.txt did not return the expected number of tasks');
    
    $this->assertEquals(6, $tasks[0]->id, 'First task id');
    $this->assertEquals('Task 6', $tasks[0]->name, 'First task name');
    $this->assertEquals(Web2All_Task_Task::STATE_ACTIVE, $tasks[0]->state, 'First task state');
    
    $this->assertEquals(1, $tasks[1]->id, 'Second task id');
    $this->assertEquals('two-hourly date', $tasks[1]->name, 'Second task name');
    
    $this->assertEquals(7, $tasks[2]->id, 'Third task id');
    $this->assertEquals('Task 7', $tasks[2]->name, 'Third task name');
    $this->assertEquals(' run every two hours between 11 pm and 7 am, and again at 8 am', $tasks[2]->description, 'Third task description');
    
    $this->assertEquals(3, $tasks[3]->id, 'Fourth task id');
    $this->assertEquals('Task 3', $tasks[3]->name, 'Fourth task name');
    $this->assertEquals(Web2All_Task_Task::STATE_ACTIVE, $tasks[3]->state, 'Fourth task state');
    
    $this->assertEquals(8, $tasks[4]->id, 'Fifth task id');
    $this->assertEquals('11am date', $tasks[4]->name, 'Fifth task name');
    $this->assertEquals('date >> /var/log/date-output 2>&1', $tasks[4]->command, 'Fifth task command');
    $this->assertEquals(' run every day at 11 am, appending all output to a file', $tasks[4]->description, 'Fifth task description');
    
    $this->assertEquals(5, $tasks[5]->id, 'Sixth task id');
    $this->assertEquals('Task 5', $tasks[5]->name, 'Sixth task name');
    $this->assertEquals('date,housekeeping', $tasks[5]->tags, 'Sixth task tags');
    $this->assertEquals("This is unimportant\nit really is", $tasks[5]->impact_description, 'Sixth task impact_description');
    
    $this->assertEquals(9, $tasks[6]->id, 'Seventh task id');
    $this->assertEquals('process list', $tasks[6]->name, 'Seventh task name');
    $this->assertEquals(Web2All_Task_Task::STATE_DISABLED, $tasks[6]->state, 'Seventh task state');
    $this->assertEquals('ps', $tasks[6]->command, 'Seventh task command');
    $this->assertEquals(' run ps at 3 pm on each monday', $tasks[6]->description, 'Seventh task description');
  }

  /**
   * Test con storage loading
   * 
   */
  public function testCronStorageCommentsIDOnly()
  {
    $cronstorage = new Web2All_Task_Storage_CronFile(__DIR__ . '/sample_cron_comments.txt');
    $cronstorage->parseOnlyID();
    
    $tasks = $cronstorage->getTasks();
    $this->assertCount(6, $tasks, 'CronFile sample_cron_comments.txt did not return the expected number of tasks');
    
    $this->assertEquals(6, $tasks[0]->id, 'First task id');
    $this->assertEquals('Task 6', $tasks[0]->name, 'First task name');
    $this->assertEquals(Web2All_Task_Task::STATE_ACTIVE, $tasks[0]->state, 'First task state');
    
    $this->assertEquals(1, $tasks[1]->id, 'Second task id');
    $this->assertEquals('Task 1', $tasks[1]->name, 'Second task name');
    
    $this->assertEquals(7, $tasks[2]->id, 'Third task id');
    $this->assertEquals('Task 7', $tasks[2]->name, 'Third task name');
    $this->assertEquals(null, $tasks[2]->description, 'Third task description');
    
    $this->assertEquals(3, $tasks[3]->id, 'Fourth task id');
    $this->assertEquals('Task 3', $tasks[3]->name, 'Fourth task name');
    $this->assertEquals(Web2All_Task_Task::STATE_ACTIVE, $tasks[3]->state, 'Fourth task state');
    
    $this->assertEquals(8, $tasks[4]->id, 'Fifth task id');
    $this->assertEquals('Task 8', $tasks[4]->name, 'Fifth task name');
    $this->assertEquals('date >> /var/log/date-output 2>&1', $tasks[4]->command, 'Fifth task command');
    $this->assertEquals(null, $tasks[4]->description, 'Fifth task description');
    
    $this->assertEquals(5, $tasks[5]->id, 'Sixth task id');
    $this->assertEquals('Task 5', $tasks[5]->name, 'Sixth task name');
    $this->assertEquals(null, $tasks[5]->tags, 'Sixth task tags');
    $this->assertEquals(null, $tasks[5]->impact_description, 'Sixth task impact_description');
  }

  /**
   * Test con storage loading
   * 
   */
  public function testCronStorageCommentsNoMeta()
  {
    $cronstorage = new Web2All_Task_Storage_CronFile(__DIR__ . '/sample_cron_comments.txt');
    $cronstorage->parseNoMeta();
    
    $tasks = $cronstorage->getTasks();
    $this->assertCount(6, $tasks, 'CronFile sample_cron_comments.txt did not return the expected number of tasks');
    
    $this->assertEquals(1, $tasks[0]->id, 'First task id');
    $this->assertEquals('Task 1', $tasks[0]->name, 'First task name');
    $this->assertEquals(Web2All_Task_Task::STATE_ACTIVE, $tasks[0]->state, 'First task state');
    
    $this->assertEquals(2, $tasks[1]->id, 'Second task id');
    $this->assertEquals('Task 2', $tasks[1]->name, 'Second task name');
    
    $this->assertEquals(3, $tasks[2]->id, 'Third task id');
    $this->assertEquals('Task 3', $tasks[2]->name, 'Third task name');
    $this->assertEquals(null, $tasks[2]->description, 'Third task description');
    
    $this->assertEquals(4, $tasks[3]->id, 'Fourth task id');
    $this->assertEquals('Task 4', $tasks[3]->name, 'Fourth task name');
    $this->assertEquals(Web2All_Task_Task::STATE_ACTIVE, $tasks[3]->state, 'Fourth task state');
    
    $this->assertEquals(5, $tasks[4]->id, 'Fifth task id');
    $this->assertEquals('Task 5', $tasks[4]->name, 'Fifth task name');
    $this->assertEquals('date >> /var/log/date-output 2>&1', $tasks[4]->command, 'Fifth task command');
    $this->assertEquals(null, $tasks[4]->description, 'Fifth task description');
    
    $this->assertEquals(6, $tasks[5]->id, 'Sixth task id');
    $this->assertEquals('Task 6', $tasks[5]->name, 'Sixth task name');
    $this->assertEquals(null, $tasks[5]->tags, 'Sixth task tags');
    $this->assertEquals(null, $tasks[5]->impact_description, 'Sixth task impact_description');
  }

}
?>