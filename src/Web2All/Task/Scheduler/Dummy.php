<?php
/**
 * Web2All Task Scheduler Dummy class
 * 
 * This is a dummy implementation (does nothing)
 * 
 * @author Merijn van den Kroonenberg
 * @copyright (c) Copyright 2017 Web2All BV
 * @since 2017-08-08
 */
class Web2All_Task_Scheduler_Dummy implements Web2All_Task_IScheduler {

  /**
   * Update the scheduler with new tasks
   * 
   * @param Web2All_Task_Task[] $tasks
   */
  public function updateScheduler($tasks)
  {
    // do nothing
  }
  
  /**
   * Run the scheduler
   * 
   * @param int $timestamp  unix timestamp or null
   */
  public function runScheduler($timestamp = null)
  {
    // do nothing
  }
}
?>