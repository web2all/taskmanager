<?php
/**
 * Web2All Task IScheduler interface
 * 
 * This is the interface for schedulers
 * 
 * @author Merijn van den Kroonenberg
 * @copyright (c) Copyright 2017 Web2All BV
 * @since 2017-08-08
 */
interface Web2All_Task_IScheduler {

  /**
   * Update the scheduler with new tasks
   * 
   * @param Web2All_Task_Task[] $tasks
   */
  public function updateScheduler($tasks);
  
  /**
   * Run the scheduler
   * 
   * @param int $timestamp  unix timestamp or null
   */
  public function runScheduler($timestamp = null);
}
?>