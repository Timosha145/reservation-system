<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $phone = $_POST['phone'];
    $name = $_POST['name'];
    $time = $_POST['time'];
    $service = $_POST['service'];
    $car = $_POST['car'];

    $xml = simplexml_load_file('reservation.xml');

    $record = $xml->addChild('record');
    $record->addChild('phoneNumber', $phone);
    $record->addChild('name', $name);
    $record->addChild('time', $time);
    $record->addChild('service', $service);
    $record->addChild('carNumber', $car);

    $xml->asXML('reservation.xml');

    header('Location: index.php');
    exit();
}
?>
