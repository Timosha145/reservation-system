<?php
$xml = new DOMDocument;
$xsl = new DOMDocument;
$proc = new XSLTProcessor;

$xml->load('reservation.xml');
$xsl->load('reservationExporter.xsl');

$serviceFilter = $_GET['service'] ?? '';

$proc->setParameter('', 'serviceFilter', $serviceFilter);
$proc->importStyleSheet($xsl);

if (isset($_POST['delete'])) {
    $idToDelete = intval($_POST['delete_id']);
    $records = $xml->getElementsByTagName('record');

    foreach ($records as $record) {
        $recordId = intval($record->getAttribute('id'));

        if ($recordId === $idToDelete) {
            $record->parentNode->removeChild($record);
            $xml->save('reservation.xml');
            break;
        }
    }

    header('Location: reservations.php');
    exit();
}

if (isset($_POST['add'])) {
    $records = $xml->getElementsByTagName('record');

    $lastRecord = $records->item($records->length - 1);
    $lastId = $lastRecord ? intval($lastRecord->getAttribute('id')) : 0;

    $newRecord = $xml->createElement('record');
    $newRecord->setAttribute('id', $lastId + 1);

    $newRecord->appendChild($xml->createElement('phoneNumber', $_POST['phone']));
    $newRecord->appendChild($xml->createElement('name', $_POST['name']));

    $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['time']);
    $formattedTime = $dateTime->format('Y-m-d H:i');

    $newRecord->appendChild($xml->createElement('time', $formattedTime));
    $newRecord->appendChild($xml->createElement('service', $_POST['service']));
    $newRecord->appendChild($xml->createElement('carNumber', $_POST['car']));

    $xml->getElementsByTagName('records')->item(0)->appendChild($newRecord);
    $xml->save('reservation.xml');

    header('Location: reservations.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <title>Auto Hoolduse Broneeringud</title>
    <link rel="stylesheet" type="text/css" href="reservationsStyle.css">
</head>
<body>

<nav>
    <ul>
        <li><a href="reservations.php">XML versioon</a></li>
        <li><a href="reservationsJson.php">Json versioon</a></li>
    </ul>
</nav>

<h1>Auto Hoolduse Broneeringud (XML veersion)</h1>

<form>
    <div id="serviceDiv">
        <select id="service-select" name="service" style="flex: 1;">
            <option value="">Kõik Autoteenindused </option>
            <option value="Service A">Service A</option>
            <option value="Service B">Service B</option>
        </select>
        <input type="submit" id="filterButton" value="Filter">
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
            <?php
            echo $proc->transformToXML($xml);
            ?>
        </tr>
    </form>
</table>
</body>
</html>
