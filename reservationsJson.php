<?php
$jsonFileName = 'reservation.json';
$xmlFileName = 'reservation.xml';

$jsonData = file_get_contents($jsonFileName);
$data = json_decode($jsonData, true);
$filteredData = $data;

if (isset($_POST['delete'])) {
    $idToDelete = intval($_POST['delete_id']);

    foreach ($data['record'] as $key => $record) {
        if ($record['@attributes']['id'] == $idToDelete) {
            unset($data['record'][$key]);
            break;
        }
    }

    $data['record'] = array_values($data['record']);
    $json = json_encode($data, JSON_PRETTY_PRINT);

    file_put_contents($jsonFileName, $json);

    header('Location: reservationsJson.php');
    exit();
}

// Операция добавления записи
if (isset($_POST['add'])) {
    $newRecord = [
        '@attributes' => [
            'id' => count($data['record']) + 1
        ],
        'phoneNumber' => $_POST['phone'],
        'name' => $_POST['name'],
        'time' => $_POST['time'],
        'service' => $_POST['service'],
        'carNumber' => $_POST['car'],
    ];

    $data['record'][] = $newRecord;

    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($jsonFileName, $json);

    header('Location: reservationsJson.php');
    exit();
}

if (isset($_POST['updateData'])) {
    $xmlString = file_get_contents($xmlFileName);
    $xml = simplexml_load_string($xmlString);
    $json = json_encode($xml, JSON_PRETTY_PRINT);
    file_put_contents($jsonFileName, $json);

    header('Location: reservationsJson.php');
    exit();
}

if (isset($_POST['filterData'])) {
    $filterService = $_POST['service'];
    $filteredData = $data;

    foreach ($filteredData['record'] as $key => $record) {
        if ($record['service'] != $filterService) {
            unset($filteredData['record'][$key]);
        }
    }

    if(count($filteredData['record']) == 0) {
        $filteredData = $data;
    }
}

?>

<!DOCTYPE html>
<html lang="et">
<head>
    <title>Auto Hoolduse Broneeringud</title>
    <link rel="stylesheet" type="text/css" href="reservationsJsonStyle.css">
</head>
<body>

<nav>
    <ul>
        <li><a href="reservations.php">XML versioon</a></li>
        <li><a href="reservationsJson.php">Json versioon</a></li>
    </ul>
</nav>

<h1>Auto Hoolduse Broneeringud (Json veersion)</h1>

<form method="post">
    <div id="serviceDiv">
        <select id="service-select" name="service" style="flex: 1;">
            <option value="">Kõik Autoteenindused</option>
            <option value="Service A">Service A</option>
            <option value="Service B">Service B</option>
        </select>
        <input type="submit" id="filterButton" name="filterData" value="Filter">
        <input type="submit" id="updateButton" name="updateData" value="Update Data">
    </div>
</form>

<table border="1">
    <tr>
        <th>Id</th>
        <th>Telefoninumber</th>
        <th>Nimi</th>
        <th>Aeg</th>
        <th>Autoteenindus</th>
        <th>Autonumber</th>
        <th>Tegevus</th>
    </tr>
    <form method="post">
        <tr>
            <td/>
            <td><input type="tel" name="phone" placeholder="+37200111222" pattern="^(\+[0-9]+|[0-9]+)" title="Vale telefoninumber!" required></td>
            <td><input type="text" name="name" placeholder="Nimi" pattern="[A-Za-z\s]+" title="Ainult ladina tähed!" required></td>
            <td><input type="datetime-local" name="time" required></td>
            <td>
                <select name="service" required>
                    <option value="">Vali</option>
                    <option value="Service A">Service A</option>
                    <option value="Service B">Service B</option>
                </select>
            </td>
            <td><input type="text" name="car" placeholder="Autonumber" pattern="[A-Za-z0-9]+" title="Vale autonumber!" required></td>
            <td><input type="submit" name="add" value="Lisa"></td>
        </tr>
    </form>
    <?php foreach ($filteredData['record'] as $record) { ?>
        <tr>
            <td><?= $record['@attributes']['id'] ?></td>
            <td><?= $record['phoneNumber'] ?></td>
            <td><?= $record['name'] ?></td>
            <td><?= $record['time'] ?></td>
            <td><?= $record['service'] ?></td>
            <td><?= $record['carNumber'] ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="delete_id" value="<?= $record['@attributes']['id'] ?>"/>
                    <input type="submit" name="delete" value="Kustuta"/>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
</body>
</html>
