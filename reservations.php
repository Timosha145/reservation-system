<?php
$xml = new DOMDocument;
$xsl = new DOMDocument;
$proc = new XSLTProcessor;

$xml->load('reservation.xml');
$xsl->load('reservationExporter.xsl');

$serviceFilter = $_GET['service'] ?? '';

$proc->setParameter('', 'serviceFilter', $serviceFilter);
$proc->importStyleSheet($xsl);

// Обработка добавления новой записи
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newRecord = $xml->createElement('record');
    $newRecord->appendChild($xml->createElement('phoneNumber', $_POST['phone']));
    $newRecord->appendChild($xml->createElement('name', $_POST['name']));
    $newRecord->appendChild($xml->createElement('time', $_POST['time']->format('Y-m-d H:i:s')));
    $newRecord->appendChild($xml->createElement('service', $_POST['service']));
    $newRecord->appendChild($xml->createElement('carNumber', $_POST['car']));
    $xml->getElementsByTagName('records')->item(0)->appendChild($newRecord);
    $xml->save('reservation.xml');

    // После успешного добавления перенаправляем пользователя на эту же страницу для предотвращения повторной отправки формы
    header('Location: reservation.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <title>Auto Hoolduse Broneeringud</title>
</head>
<body>
<h1>Auto Hoolduse Broneeringud</h1>

<form>
    <label for="service-select">Vali Autoteenindus: </label>
    <select id="service-select" name="service">
        <option value="">Kõik Autoteenindused</option>
        <option value="Service A">Service A</option>
        <option value="Service B">Service B</option>
    </select>
    <input type="submit" value="Filter">
</form>

<table border="1">
    <tr>
        <th>Telefoninumber</th>
        <th>Nimi</th>
        <th>Aeg</th>
        <th>Autoteenindus</th>
        <th>Autonumber</th>
        <th>Tegevus</th>
    </tr>
    <form method="post">
        <tr>
            <td><input type="tel" name="phone" placeholder="+37200111222" pattern="^(\+[0-9]+|[0-9]+)" title="Vale telefoninumber!" required></td>
            <td><input type="text" name="name" placeholder="Nimi" pattern="[A-Za-z\s]+" title="Ainult tähed!" required></td>
            <td><input type="datetime-local" name="time" placeholder="Time and Date" required></td>
            <td>
                <select name="service" required>
                    <option value="">Vali</option>
                    <option value="Service A">Service A</option>
                    <option value="Service B">Service B</option>
                </select>
            </td>
            <td><input type="text" name="car" placeholder="Autonumber" pattern="[A-Za-z0-9]+" title="Vale autonumber!" required></td>
            <td><input type="submit" value="Add Record"></td>
            <?php
            echo $proc->transformToXML($xml);
            ?>
        </tr>
    </form>
</table>
</body>
</html>
