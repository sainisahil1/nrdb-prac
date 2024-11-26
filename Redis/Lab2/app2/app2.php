<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
<?php
include 'MyRedisController.php';
$myRedisController = new MyRedisController();
$stringKeys = ["firstname", "lastname"];
$listKeys = ["list1", "list2"];
$setKeys = ["set1", "set2"];
$zetKeys = ["zet1", "zet2"];
$hasKey = "hash";
function displayAllList(MyRedisController $myRedisController, $listKeys): void
{
    foreach ($listKeys as $listKey) {
        echo "key: " . $listKey . ", value: " . $myRedisController->getMyList($listKey) . "\n";
    }
}

function displaySets(MyRedisController $myRedisController, $setKeys): void
{
    foreach ($setKeys as $setKey) {
        $result = $myRedisController->scanMySet($setKey);
        echo "key: " . $setKey . ", value: ".json_encode($result);
    }
}

function displayZets(MyRedisController $myRedisController, $zetKeys): void
{
    foreach ($zetKeys as $zetKey) {
        echo "key: " . $zetKey . ", value: " . json_encode($myRedisController->getAllMembers($zetKey)) . "\n";
    }
}

function displayHash(MyRedisController $myRedisController, $hashKey): void
{
    echo "key: " . $hashKey . ", value: " . json_encode($myRedisController->getHashMembers($hashKey)) . "\n";
}

?>
<h3>String</h3>
<?php
foreach ($stringKeys as $stringKey) {
    echo "key: " . $stringKey . ", value: " . $myRedisController->getMyString($stringKey) . "\n";
}
?>
<br><br>After appending "SS" at the end of first name<br>
<?php
$myRedisController->appendString("firstname", "SS");
echo $myRedisController->getMyString("firstname");
?>
<br><br>

<h3>List</h3>
<?php
displayAllList($myRedisController, $listKeys);
?>
<br><br>Insert "hello" in list1<br>
<?php
$myRedisController->insertItem($listKeys[0], Redis::AFTER, "two", "hello");
displayAllList($myRedisController, $listKeys);
?>
<br><br>LPOP from list2<br>
<?php
$myRedisController->lPopFromList($listKeys[1]);
displayAllList($myRedisController, $listKeys);
?>
<br><br>RPOPLPUSH list1 list2<br>
<?php
$myRedisController->rPopLPush($listKeys[0], $listKeys[1]);
displayAllList($myRedisController, $listKeys);
?>
<br><br>LMOVE: Creating LPOPRPUSH behaviour<br>
<?php
$myRedisController->lMove($listKeys[0], $listKeys[1], Redis::LEFT, Redis::RIGHT);
displayAllList($myRedisController, $listKeys);
?>
<h3>Sets</h3>
<?php
displaySets($myRedisController, $setKeys);
?>
<br><br>Applying Intersection<br>
<?php
echo json_encode($myRedisController->findSetIntersection($setKeys[0], $setKeys[1]));
?>
<br><br>Applying Union<br>
<?php
echo json_encode($myRedisController->findSetUnion($setKeys[0], $setKeys[1]));
?>
<br><br>Applying Difference<br>
<?php
echo json_encode($myRedisController->findSetDifference($setKeys[0], $setKeys[1]));
?>
<br>
<h3>Sorted Sets</h3>
<?php
displayZets($myRedisController, $zetKeys);
?>
<br><br>Applying Intersection<br>
<?php
$myRedisController->findZetIntersection("zinterout", $zetKeys);
displayZets($myRedisController, ["zinterout"]);
?>
<br><br>Applying union with weights 2 3<br>
<?php
$myRedisController->findZetUnion("zunoinout", $zetKeys, [2, 3]);
displayZets($myRedisController, ["zunoinout"]);
?>
<br><br>Applying Difference with scores<br>
<?php
echo json_encode($myRedisController->findZetDifference($zetKeys, ['WITHSCORES' => true]));
?>
<br>
<h3>Hash</h3>
<?php
displayHash($myRedisController, $hasKey);
?>
<br><br>Add & update new entry<br>
<?php
$myRedisController->addNewHashEntry($hasKey, "name", "Sahil");
$myRedisController->addNewHashEntry($hasKey, "University", "Hochschule Hof");
displayHash($myRedisController, $hasKey);
?>

</body>
</html>
