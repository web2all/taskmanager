<?php
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Web2All Task Taskmanager class
 * 
 * This class is the glue between the tasks stored in the backend
 * and the actual scheduler.
 * 
 * @author Merijn van den Kroonenberg
 * @copyright (c) Copyright 2017 Web2All BV
 * @since 2017-08-08
 */
class Web2All_Task_Taskmanager implements LoggerAwareInterface {
  
  /**
   * Scheduler implementation
   *
   * @var Web2All_Task_IScheduler
   */
  protected $scheduler;
  
  /**
   * Storage implementation
   *
   * @var Web2All_Task_Istorage
   */
  protected $storage;
  
  /**
   * A string with the last value from $storage->getUpdateState()
   *
   * @var string
   */
  protected $storage_state;
  
  /**
   * Task list
   *
   * @var Web2All_Task_Task[]
   */
  protected $tasks;
  
  /**
   * Logger object (PSR Log)
   *
   * @var Psr\Log\LoggerInterface
   */
  protected $logger;
  
  /**
   * String to prefix to each log message
   *
   * @var string
   */
  protected $log_prefix;
  
  /**
   * constructor
   * 
   * @param Web2All_Task_Istorage $storage
   * @param Web2All_Task_IScheduler $scheduler
   */
  public function __construct($storage, $scheduler) 
  {
    $this->scheduler = $scheduler;
    $this->storage = $storage;
    $this->storage_state = '';
    $this->tasks = array();
    $this->logger = null;
    $this->log_prefix=get_class($this)." ";
  }
  
  /**
   * Assign a logger
   * 
   * @param Psr\Log\LoggerInterface $logger
   */
  public function setLogger(LoggerInterface $logger)
  {
    $this->logger = $logger;
  }
  
  /**
   * Run the taskmanager
   * 
   * @param int $timestamp  unix timestamp or null
   */
  public function run($timestamp = null)
  {
    // see if we have to load the tasks from the backend
    $this->updateTasks();
    // run the task scheduler
    $this->runScheduler($timestamp);
  }
  
  /**
   * update the tasks from the database
   * 
   */
  protected function updateTasks()
  {
    // determine if tasks have changed
    $changed=false;
    while($this->tasksChanged()){
      // we check in a loop because the state could have changed
      // between our check and our loading. this guarantees a stable 
      // state.
      $this->loadTasks();
      $changed = true;
    }
    if($changed){
      $this->updateScheduler();
    }
  }
  
  /**
   * Check if the tasks have been changed since last load
   * 
   * @return boolean
   */
  protected function tasksChanged()
  {
    $actual_state = $this->storage->getUpdateState();
    if($this->storage_state !== $actual_state){
      if($this->logger){
        $this->logger->info($this->log_prefix.'Storage backend state changed from "{STATEOLD}" to "{STATENEW}"', array('STATEOLD' => $this->storage_state, 'STATENEW' => $actual_state));
      }
      return true;
    }
    return false;
  }
  
  /**
   * Load the tasks from the backend
   * 
   */
  protected function loadTasks()
  {
    if($this->logger){
      $this->logger->debug($this->log_prefix.'Storage backend loading tasks from the database');
    }
    $this->tasks = $this->storage->getTasks();
    $this->storage_state = $this->storage->getUpdateState();
  }
  
  /**
   * Update the scheduler
   * 
   */
  protected function updateScheduler()
  {
    // scheduler dependent
    $active_tasks=array();
    foreach($this->tasks as $task){
      if($task->state == Web2All_Task_Task::STATE_ACTIVE){
        $active_tasks[]=$task;
      }
    }
    $this->scheduler->updateScheduler($active_tasks);
  }
  
  /**
   * Run the scheduler
   * 
   * @param int $timestamp  unix timestamp or null
   */
  protected function runScheduler($timestamp = null)
  {
    if($this->logger){
      $this->logger->debug($this->log_prefix.'Running scheduler');
    }
    // scheduler dependent
    $this->scheduler->runScheduler($timestamp);
  }
  
}
?>