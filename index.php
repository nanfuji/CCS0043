<?php
session_start();

$todoList = array();

if (isset($_SESSION["todoList"])) $todoList = $_SESSION["todoList"];

function appendData($data) {
    global $todoList;
    $task = ['name' => $data, 'done' => false];
    $todoList[] = $task;
    return $todoList;
}

function deleteData($toDelete, $indexToDelete, $todoList) {
    foreach ($todoList as $index => $task) {
        if ($task['name'] === $toDelete && $index == $indexToDelete) {
            unset($todoList[$index]);
        }
    }
    return array_values($todoList);
}

function markAsDone($toMark, $indexToMark, $todoList) {
    foreach ($todoList as $index => $task) {
        if ($task['name'] === $toMark && $index == $indexToMark) {
            $todoList[$index]['done'] = true;
        }
    }
    return $todoList;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["task"])) {
        echo '<script>alert("Error: there is no data to add in array")</script>';
        exit;
    }

    $todoList = appendData($_POST["task"]);
    $_SESSION["todoList"] = $todoList;
}

if (isset($_GET['delete'])) {
    $todoList = deleteData($_GET['task'], $_GET['index'], $todoList);
    $_SESSION["todoList"] = $todoList;
}

if (isset($_GET['done'])) {
    $todoList = markAsDone($_GET['task'], $_GET['index'], $todoList);
    $_SESSION["todoList"] = $todoList;
}

$taskCount = count($todoList);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do List</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">To-Do List</h1>
        <div class="card">
            <div class="card-header">Add a new task</div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" name="task" placeholder="Enter your task here">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Tasks (<?php echo $taskCount; ?>)</div>
            <ul class="list-group list-group-flush">
            <?php
                foreach ($todoList as $index => $task) {
                    $taskName = htmlspecialchars($task['name']);
                    $taskClass = $task['done'] ? 'style="text-decoration: line-through;"' : '';
                    echo '<div class="d-flex p-2 bd-highlight w-100 justify-content-between">
                            <li class="list-group-item w-100" ' . $taskClass . '>' . $taskName . '</li>
                            <div>
                                <a href="index.php?done=true&task=' . urlencode($taskName) . '&index=' . $index . '" class="btn btn-success">Done</a>
                                <a href="index.php?delete=true&task=' . urlencode($taskName) . '&index=' . $index . '" class="btn btn-danger">Delete</a>
                            </div>
                          </div>';
                }
            ?>
            </ul>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
