<?php
/**
 * Web2All Task Storage CronFile class
 * 
 * This storage implementation stores the schedule in a cron file
 * 
 * @author Merijn van den Kroonenberg
 * @copyright (c) Copyright 2017 Web2All BV
 * @since 2017-08-09
 */
class Web2All_Task_Storage_CronFile implements Web2All_Task_IStorage {

  /**
   * Task list
   *
   * @var Web2All_Task_Task[]
   */
  protected $tasks;
  
  /**
   * Storage state
   *
   * @var string
   */
  protected $state;
  
  /**
   * Cron file
   *
   * @var string
   */
  protected $cronfile;
  
  /**
   * Only parse schedule and command
   *
   * Comments and tags will be ignored.
   *
   * @var boolean
   */
  protected $parse_no_meta;
  
  /**
   * Only parse schedule, command and id
   *
   * Comments and all other tags will be ignored.
   *
   * @var boolean
   */
  protected $parse_only_id;
  
  /**
   * constructor
   * 
   * @param string $cronfile
   */
  public function __construct($cronfile) 
  {
    if(!is_readable($cronfile)){
      throw new InvalidArgumentException('Argument $cronfile should be a readable file: '.$cronfile);
    }
    $this->cronfile = $cronfile;
    $this->parse_no_meta = false;
    $this->parse_only_id = false;
  }
  
  /**
   * When reading cronfile, parse comments as well
   * 
   * This is the default.
   */
  public function parseFully()
  {
    $this->parse_no_meta = false;
    $this->parse_only_id = false;
  }
  
  /**
   * When reading cronfile, ignore all comments
   * 
   */
  public function parseNoMeta()
  {
    $this->parse_no_meta = true;
    $this->parse_only_id = false;
  }
  
  /**
   * When reading cronfile, parse the [id] tag but nothing else
   * 
   */
  public function parseOnlyID()
  {
    $this->parse_no_meta = false;
    $this->parse_only_id = true;
  }
  
  /**
   * Get a list of tasks
   * 
   * @return Web2All_Task_Task[]
   */
  public function getTasks()
  {
    while(!$this->state || $this->state!==$this->calculateUpdateState()){
      $this->loadTasks();
      $this->state=$this->calculateUpdateState();
    }
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
    return $this->calculateUpdateState();
  }
  
  /**
   * Return a string which represents the current state of the task 
   * storage
   * 
   * @return string
   */
  protected function calculateUpdateState()
  {
    $cronsize=filesize($this->cronfile);
    $cronmod=filemtime($this->cronfile);
    return hash('sha256',$cronsize.':'.$cronmod);
  }
  
  /**
   * Get a list of tasks
   * 
   */
  protected function loadTasks()
  {
    $this->tasks = array();
    $cron_fh = fopen($this->cronfile, "r");
    if (!$cron_fh) {
      throw new Exception('could not read cron file '.$this->cronfile);
    }
    $collected_comment = '';
    $comment_encoded_id = null;
    $comment_encoded_name = null;
    $comment_encoded_tags = null;
    $comment_encoded_impact = null;
    $task_counter = 1;
    while (($line = fgets($cron_fh, 8192)) !== false) {
      // parse line
      if(preg_match('/^\s*$/',$line)){
        // empty line
        // first check if the last comment line was a possibly a disabled cron statement
        $comment_lines=explode("\n",$collected_comment);
        if(!$this->parse_no_meta && !$this->parse_only_id && preg_match('/^\s*((?:\S+\s+){4}\S+)\s+(.*?)\s*$/',array_pop($comment_lines),$matches)){
          // possible cron command
          // todo: also do this check on end-of-file
          // see if it at least starts with a number or *
          if(preg_match('/^[\d\*]/',$matches[1])){
            $task = new Web2All_Task_Task();
            if($comment_encoded_id){
              $task->id = $comment_encoded_id;
              if($task->id >= $task_counter){
                // keep track of highest task number
                $task_counter = $task->id + 1;
              }
            }
            if($comment_encoded_name){
              $task->name = $comment_encoded_name;
            }
            $task->schedule = $matches[1];
            $task->command = $matches[2];
            $task->description = implode("\n",$comment_lines);
            $task->state = Web2All_Task_Task::STATE_DISABLED;
            if($comment_encoded_tags){
              $task->tags = $comment_encoded_tags;
            }
            if($comment_encoded_impact){
              $task->impact_description = $comment_encoded_impact;
            }
            $this->tasks[] = $task;
          }
        }
        
        // reset comments and collected data
        $collected_comment = '';
        $comment_encoded_id = null;
        $comment_encoded_name = null;
        $comment_encoded_tags = null;
        $comment_encoded_impact = null;
      }elseif(preg_match('/^\s*#+(.*?)\s+$/',$line,$matches)){
        // comment line
        if($this->parse_no_meta){
          continue;
        }
        
        // see if its structured meta
        // like: # [id] 1
        if(preg_match('/^\s*\[([^\]]+)\]\s+(.+)$/',$matches[1],$property_matches)){
          switch($property_matches[1]){
            case 'id':
              $comment_encoded_id = $property_matches[2];
              continue(2);
              
            case 'name':
              if($this->parse_only_id){
                continue;
              }
              $comment_encoded_name = $property_matches[2];
              continue(2);
              
            case 'tags':
              if($this->parse_only_id){
                continue;
              }
              $comment_encoded_tags = $property_matches[2];
              continue(2);
              
            case 'impact':
              if($this->parse_only_id){
                continue;
              }
              if($comment_encoded_impact){
                $comment_encoded_impact .= "\n";
              }
              $comment_encoded_impact .= $property_matches[2];
              continue(2);
          }
        }
        if($this->parse_only_id){
          continue;
        }
        
        // normal comment
        if($collected_comment){
          $collected_comment .= "\n";
        }
        $collected_comment.=$matches[1];
      }elseif(preg_match('/^((?:\S+\s+){4}\S+)\s+(.*?)\s+$/',$line,$matches)){
        // cron command
        $task = new Web2All_Task_Task();
        if($comment_encoded_id){
          $task->id = $comment_encoded_id;
          if($task->id >= $task_counter){
            // keep track of highest task number
            $task_counter = $task->id + 1;
          }
        }
        if($comment_encoded_name){
          $task->name = $comment_encoded_name;
        }
        $task->schedule = $matches[1];
        $task->command = $matches[2];
        if(!$this->parse_no_meta && !$this->parse_only_id){
          $task->description = $collected_comment;
        }
        $task->state = Web2All_Task_Task::STATE_ACTIVE;
        if($comment_encoded_tags){
          $task->tags = $comment_encoded_tags;
        }
        if($comment_encoded_impact){
          $task->impact_description = $comment_encoded_impact;
        }
        $this->tasks[] = $task;
        
        $collected_comment = '';
        $comment_encoded_id = null;
        $comment_encoded_name = null;
        $comment_encoded_tags = null;
        $comment_encoded_impact = null;
      }
    }
    fclose($cron_fh);
    // assign task numbers and possibly names
    foreach($this->tasks as $task){
      if(!$task->id){
        $task->id = $task_counter;
        $task_counter++;
      }
      if(!$task->name){
        $task->name = 'Task '.$task->id;
      }
    }
  }
  
}
?>