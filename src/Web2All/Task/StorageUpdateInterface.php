<?php
/**
 * Web2All Task StorageUpdateInterface interface
 * 
 * Storage backends implementing this interface can update 
 * the task they have stored.
 * 
 * @author Merijn van den Kroonenberg
 * @copyright (c) Copyright 2017 Web2All BV
 * @since 2017-08-18
 */
interface Web2All_Task_StorageUpdateInterface {

  /**
   * Replace all stored tasks by the given tasks in the storage
   * 
   * @param Web2All_Task_Task[] $tasks
   * @return boolean
   */
  public function replaceTasks($tasks);
  
  /**
   * Update/add the given task in the storage
   * 
   * @param Web2All_Task_Task $task
   * @return boolean
   */
  public function storeTask($task);
  
  /**
   * Remove a task by its id from the storage
   * 
   * @param int $id
   * @return boolean
   */
  public function removeTask($id);
}
?>