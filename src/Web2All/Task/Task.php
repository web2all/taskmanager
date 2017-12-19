<?php
/**
 * Web2All Task Task class
 * 
 * This class represents a Task.
 * 
 * @author Merijn van den Kroonenberg
 * @copyright (c) Copyright 2017 Web2All BV
 * @since 2017-08-08
 */
class Web2All_Task_Task {
  
  const STATE_DISABLED           = 0;
  const STATE_ACTIVE             = 1;
  const STATE_DEACTIVATED_MANUAL = 2;
  const STATE_DEACTIVATED_AUTO   = 3;
  
  /**
   * Unique ID
   *
   * @var int
   */
  public $id;
  
  /**
   * Task name
   *
   * @var string
   */
  public $name;
  
  /**
   * Schedule in cron format
   *
   * @var string
   */
  public $schedule;
  
  /**
   * Raw command
   *
   * @var string
   */
  public $command;
  
  /**
   * State of task
   *
   * @var int
   */
  public $state;
  
  /**
   * Description of task
   *
   * @var string
   */
  public $description;
  
  /**
   * Description of impact when task is not run
   *
   * @var string
   */
  public $impact_description;
  
  /**
   * Comma separated list of tags
   *
   * @var string
   */
  public $tags;
  
}
?>