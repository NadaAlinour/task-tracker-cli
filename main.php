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

  if ($argc == 3 && $argv[1] == "add") {

    if (!file_exists("tasks.json")) {
      $file = fopen("tasks.json", "w");
      fwrite($file, "[]"); // initialize json array
      fclose($file);
    }

    $file = fopen("tasks.json", "r");

    $tasks = fread($file, filesize("tasks.json"));

    fclose($file);

    // decode json to php object
    $task_array = json_decode($tasks, false);

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
  }
}
