<?php
require("config.php");

// MySQLi setup
$GLOBALS["mysqli_link"] = mysqli_connect($mysql["host"], $mysql["user"], $mysql["password"], $mysql["db"]);

//Prepared Statements
$GLOBALS["prepared_statements"] = [
    "new_announcement" => [
        "stmt" => $GLOBALS["mysqli_link"]->prepare(
            'INSERT INTO `announcements` (`id`, `title`, `text`) VALUES (null, ?, ?)'
        ),
        "args" => [
            "title" => '',
            "text" => ''
        ]
    ] //new_announcement
];

//Bind Prepared Statements
$GLOBALS["prepared_statements"]["new_announcement"]["stmt"]->bind_param(
    'ss',
    $GLOBALS["prepared_statements"]["new_announcement"]["args"]["title"],
    $GLOBALS["prepared_statements"]["new_announcement"]["args"]["text"]
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
