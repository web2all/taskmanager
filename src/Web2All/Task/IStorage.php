<?php
/**
 * Web2All Task IStorage interface
 * 
 * This is the interface for task storage
 * 
 * @author Merijn van den Kroonenberg
 * @copyright (c) Copyright 2017 Web2All BV
 * @since 2017-08-08
 */
interface Web2All_Task_IStorage {

  /**
   * Get a list of tasks
   * 
   * @return Web2All_Task_Task[]
   */
  public function getTasks();
  
  /**
   * Return a string which represents the current state of the task 
   * storage
   * 
   * @return string
   */
  public function getUpdateState();
}
?>