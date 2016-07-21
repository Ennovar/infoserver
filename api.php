<?php
require("config.php");

// MySQLi setup
$GLOBALS["mysqli_link"] = mysqli_connect($mysql["host"], $mysql["user"], $mysql["password"], $mysql["db"]);

//Prepared Statements
$GLOBALS["prepared_statements"] = [
    "new_announcement" => [
        "stmt" => $GLOBALS["mysqli_link"]->prepare(
            'INSERT INTO `announcements` (`id`, `title`, `text`) VALUES (null, ?, ?)'
        ), //stmt
        "args" => [
            "title" => '',
            "text" => ''
        ] //args
    ], //new_announcement

    "last_announcements" => [
        "stmt" => $GLOBALS["mysqli_link"]->prepare(
            'SELECT * FROM `announcements` ORDER BY `id` DESC LIMIT ?'
        ), //stmt
        "args" => [
            "num" => 0
        ] //args
    ], //last_announcement

    "announcement_by_id" => [
        "stmt" => $GLOBALS["mysqli_link"]->prepare(
            'SELECT * FROM `announcements` WHERE `id` = ?'
        ), //stmt
        "args" => [
            "id" => 0
        ] //args
    ]//announcement_by_id
];

//Bind Prepared Statements
//new_announcement
$GLOBALS["prepared_statements"]["new_announcement"]["stmt"]->bind_param(
    'ss',
    $GLOBALS["prepared_statements"]["new_announcement"]["args"]["title"],
    $GLOBALS["prepared_statements"]["new_announcement"]["args"]["text"]
);

//last_announcements
$GLOBALS["prepared_statements"]["last_announcements"]["stmt"]->bind_param(
    'i',
    $GLOBALS["prepared_statements"]["last_announcements"]["args"]["num"]
);

//anouncement_by_id
$GLOBALS["prepared_statements"]["announcement_by_id"]["stmt"]->bind_param(
    'i',
    $GLOBALS["prepared_statements"]["announcement_by_id"]["args"]["id"]
);

$action = get("action");
$result = [];

switch ($action) {
    case "test":
        $result = [
            "msg" => "Test",
            "success" => true
        ];
        break;
    case "new_announcement":
        $result = new_announcement();
        break;
    case "get_announcement":
        $result = get_announcement();
        break;
}

respond($result);

function respond($result)
{
    echo "<pre>";
    echo json_encode($result, JSON_PRETTY_PRINT);
    echo "</pre>";
}

function get($var)
{
    if (isset($_GET["$var"])) {
        return $_GET["$var"];
    } else {
        return null;
    }
}

function new_announcement()
{
    $result = [];
    $title = get("title");
    $text = get("text");

    if ($title) {
        //Assign prepared statement's arguments
        assign_args(
            [
                "title" => $title,
                "text" => ($text ? $text : "")
            ],
            $GLOBALS["prepared_statements"]["new_announcement"]["args"]
        );

        //Execute statement
        $success = $GLOBALS["prepared_statements"]["new_announcement"]["stmt"]->execute();
        $result["success"] = $success;

        if ($success == false) {
            $result["msg"] = mysqli_error($GLOBALS["mysqli_link"]);
        }
    } else { //No Title
        $result = [
            "success" => false,
            "msg" => "No title provided"
        ];
    }

    return $result;
}

function assign_args($args, &$target)
{
    foreach ($args as $key => $value) {
        $target["$key"] = $value;
    }
}

function get_results($stmt)
{
    $stmt->store_result();

    $array = array();
    $variables = array();
    $data = array();
    $metadata = $stmt->result_metadata();

    while ($field = $metadata->fetch_field()) {
        $variables[] = &$data[$field->name];
    }

    call_user_func_array(array($stmt, 'bind_result'), $variables);

    for ($i = 0; $stmt->fetch(); $i++) {
        $array[$i] = array();
        foreach ($data as $key => $value) {
            $array[$i][$key] = $value;
        }
    }

    return $array;
}

function get_announcement()
{
    $result = [];
    $id = get("id");
    $stmt = "";

    if ($id) {
        $stmt = "announcement_by_id";
        assign_args(
            [
                "id" => $id
            ],
            $GLOBALS["prepared_statements"][$stmt]["args"]
        );
        $result["success"] = $GLOBALS["prepared_statements"][$stmt]["stmt"]->execute();
    } else { //No ID Provided
        $stmt = "last_announcements";
        assign_args(
            [
                "num" => 1
            ],
            $GLOBALS["prepared_statements"][$stmt]["args"]
        );
        $result["success"] = $GLOBALS["prepared_statements"][$stmt]["stmt"]->execute();
    }

    if ($result["success"]) {
        $result["announcement"] = get_results($GLOBALS["prepared_statements"][$stmt]["stmt"]);
    } else { //Statement execution failed
        $result["msg"] = mysqli_error($GLOBALS["mysqli_link"]);
    }

    return $result;
}
