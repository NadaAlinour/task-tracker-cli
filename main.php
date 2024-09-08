<?php

class Task
{
  public $id;
  public $description;
  public $status;
  public $created_at;
  public $last_updated;

  public function __construct($id, $description, $status = "todo", $last_updated = null)
  {
    $this->id = $id;
    $this->description = $description;
    $this->status = $status;
    $this->last_updated = $last_updated;

    // get current date and time to set the created_at attribute
    $this->created_at = date("Y/m/d") . " " . date("H:i");
  }
}


while (true) {

  if (!file_exists("tasks.json")) {
    $file = fopen("tasks.json", "w");
    fwrite($file, "[]"); // initialize json array
    fclose($file);
    $task_array = [];
  } else {
    $file = fopen("tasks.json", "r");

    $tasks = fread($file, filesize("tasks.json"));

    fclose($file);

    // decode json to php object
    $task_array = json_decode($tasks, false);
  }

  if ($argc == 3 && $argv[1] == "add") {

    // new id is the last id added + 1 (or the number of tasks added)
    $new_id = count($task_array);

    // create a new task object with id and description, the rest of the attributes are default for the add command
    $new_task = new Task($new_id, $argv[2]);

    // push new task object to array of tasks
    array_push($task_array, $new_task);

    $file = fopen("tasks.json", "w");
    fwrite($file, json_encode($task_array));

    fclose($file);

    echo "Your task has been added successfully!";

    break;
  } elseif ($argc == 4 && $argv[1] == 'update') {
    if (is_numeric($argv[2]) && intval($argv[2]) < count($task_array) && $argv[3] != '') {

      // find the task with the supplied id and update the description
      foreach ($task_array as &$task) {
        if ($task->id == $argv[2]) {
          $task->description = $argv[3];

          // also update the last_updated property with the current date and time
          $task->last_updated = date("Y/m/d") . " " . date("H:i");

          $is_updated = true;
          break;
        }
      }

      // write to update file
      $file = fopen("tasks.json", "w");
      fwrite($file, json_encode($task_array));

      fclose($file);

      echo "Your task has been updated successfully!";
    } else {
      echo "something went wrong";
      break;
    }

    break;
  } elseif ($argc == 3 && $argv[1] == 'delete') {
    if (is_numeric($argv[2]) && $argv[2] < count($task_array)) {
      array_splice($task_array, $argv[2], 1);
      $file = fopen("tasks.json", "w");
      fwrite($file, json_encode($task_array));

      fclose($file);

      echo "Your task has been deleted successfully!";
    } else {
      echo 'something went wrong';
      break;
    }
    break;
  } elseif($argc == 2 && $argv[1] == 'list') {
    foreach($task_array as $task) {
      echo "ID: " . $task->id . "    Description: " . $task->description . "\n";
    }

    break;
  }
}
