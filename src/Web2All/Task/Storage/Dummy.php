<?php
/**
 * Web2All Task Storage Dummy class
 * 
 * This is a dummy implementation (does nothing, stores nothing)
 * 
 * @author Merijn van den Kroonenberg
 * @copyright (c) Copyright 2017 Web2All BV
 * @since 2017-08-08
 */
class Web2All_Task_Storage_Dummy implements Web2All_Task_IStorage {

  /**
   * Task list
   *
   * @var Web2All_Task_Task[]
   */
  public $tasks=array();
  
  /**
   * Storage state
   *
   * @var string
   */
  public $state='dummy';
  
  /**
   * Get a list of tasks
   * 
   * @return Web2All_Task_Task[]
   */
  public function getTasks()
  {
    return $this->tasks;
  }
  
  /**
   * Return a string which represents the current state of the task 
   * storage
   * 
   * @return string
   */
  public function getUpdateState()
  {
    return $this->state;
  }
}
?>