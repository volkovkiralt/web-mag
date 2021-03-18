<?php
if (!isset($_SESSION["korzina"])) {
    $_SESSION["korzina"] = [];
}

function korzina_get($db_connect) {
    if (count($_SESSION["korzina"]) === 0) {
        return ['count' => 0, 'list' => [], 'sum' => 0];
    }

    $itemKeys = join(",", array_keys($_SESSION['korzina']));
    $query = "SELECT * FROM `TOVARY` where id in ({$itemKeys})";
    $result = mysqli_query($db_connect, $query);

    $sum = 0;
    $list = [];
    while($row = $result->fetch_assoc()) {
        $count = $_SESSION["korzina"][$row["id"]];
        array_push($list, $row);
        $list[count($list) - 1]["count"] = $count;
        $list[count($list) - 1]["sum"] = $row["price"] * $count;
        $sum += $row["price"] * $count;
    }

    return ['count' => count($list), 'list' => $list, 'sum' => $sum];
}

function korzina_add($id, $count) {
    $current_value = isset($_SESSION["korzina"][$id]) ? $_SESSION["korzina"][$id] : 0;
    $_SESSION["korzina"][$id] = $current_value + $count;
}

function korzina_get_count($id) {
    $_SESSION["korzina"][$id];
}

function korzina_clear() {
    $_SESSION["korzina"] = [];
}
