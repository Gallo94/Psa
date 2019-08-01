<?php
require 'query.php';

define("CODALF", 0);
define("DESCR", 1);
define("TYPE", 2);
define("CHILDREN", 3);

$result = $client->run($query);

$hash_map = array();
foreach ($result->records() as $record) {
    $key = $record->get('cod');
    $value = array();
    $value[CODALF] = $record->get('codAlf');
    $value[DESCR] = utf8_decode(addslashes($record->get('descr')));
    $value[TYPE] = strtolower($record->get('type'));
    $value[CHILDREN] = $record->get('children');
    array_multisort($value[CHILDREN], SORT_ASC);

    if (!array_key_exists($key, $hash_map))
        $hash_map[$key] = $value;
}

function print_tree($key, $hash_map)
{
    assert(array_key_exists($key, $hash_map));
    $data = $hash_map[$key];
    $children = $data[CHILDREN];

    foreach ($children as $child) {
        if ($child == $key)
            continue;

        if (count($hash_map[$child][CHILDREN]) == 0) {
            echo ("<li>\n");
            echo ("<div class='treeview-animated-element'>" . $hash_map[$child][CODALF] . " - " . utf8_encode($hash_map[$child][DESCR]) . "</div>\n");
            echo ("</li>\n");
        } else {
            echo ("<li class='treeview-animated-items'>\n");
            echo ("<a class='closed " . $hash_map[$child][TYPE] . "'>\n");
            echo ("<i class='fas fa-angle-right'></i>\n");
            echo ("<span>" . $hash_map[$child][CODALF] . " - " . utf8_encode($hash_map[$child][DESCR]) . "</span>");
            echo ("</a>\n");
            echo ("<ul class='nested'>\n");
            print_tree($child, $hash_map);
            echo ("</ul>\n");
            echo ("</li>\n");
        }
    }
}
?>
<html>

<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.7/css/mdb.min.css" rel="stylesheet">

    <link href="css/color_type.css" type="text/css" rel="stylesheet">
</head>

<body>

    <!--Navbar-->
    <nav class="navbar navbar-dark primary-color">
        <a class="navbar-brand" href="https://www.unicam.it/">
            <img src="/LogoUnicam.png" height="30" class="d-inline-block align-top">    Piano Strategico
        </a>
    </nav>
    <div class="treeview-animated border mx-4 my-4">
        <ul class="treeview-animated-list mb-3">
            <?php print_tree(0, $hash_map) ?>
        </ul>
    </div>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.7/js/mdb.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.treeview-animated').mdbTreeview();
        });
    </script>

</body>

</html>