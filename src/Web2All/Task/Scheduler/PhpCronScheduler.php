<?php
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Web2All Task Scheduler PhpCronScheduler class
 * 
 * This is the implementation for peppeocchi/php-cron-scheduler
 * 
 * @author Merijn van den Kroonenberg
 * @copyright (c) Copyright 2017 Web2All BV
 * @since 2017-08-08
 */
class Web2All_Task_Scheduler_PhpCronScheduler implements Web2All_Task_IScheduler, LoggerAwareInterface {
  use LoggerAwareTrait;

  /**
   * Storage implementation
   *
   * @var GO\Scheduler
   */
  protected $scheduler;
  
  /**
   * constructor
   * 
   * @param GO\Scheduler $scheduler
   */
  public function __construct($scheduler) 
  {
    $this->scheduler=$scheduler;
  }
  
  /**
   * Update the scheduler with new tasks
   * 
   * @param Web2All_Task_Task[] $tasks
   */
  public function updateScheduler($tasks)
  {
    // remove existing tasks
    $this->scheduler->clearJobs();
    // add all tasks
    foreach($tasks as $task){
      $this->scheduler->raw($task->command,array(),$task->id)->at($task->schedule);
    }
  }
  
  /**
   * Run the scheduler
   * 
   * @param int $timestamp  unix timestamp or null
   */
  public function runScheduler($timestamp = null)
  {
    $datetime = new DateTime();
    if($timestamp) {
      $datetime->setTimestamp($timestamp);
    }
    $this->scheduler->resetRun();
    $this->scheduler->run($datetime);
    if($this->logger){
      if($timestamp) {
        $this->logger->debug('Scheduler ran with force timestamp: '.$datetime->format('Y-m-d H:i:s'));
      }
      foreach($this->scheduler->getExecutedJobs() as $job){
        $this->logger->debug('Scheduler executed job: '.$job->getId());
      }
      $this->logger->debug("Scheduler log:\n".$this->scheduler->getVerboseOutput());
    }
  }
}
?>