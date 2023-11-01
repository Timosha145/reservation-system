<?php
$xml = new DOMDocument;
$xsl = new DOMDocument;
$proc = new XSLTProcessor;

$xml->load('reservation.xml');
$xsl->load('reservationExporter.xsl');

$serviceFilter = $_GET['service'] ?? '';

$proc->setParameter('', 'serviceFilter', $serviceFilter);
$proc->importStyleSheet($xsl);
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <title>Records</title>
</head>
<body>
<h1>Records</h1>
<form>
    <label for="service-select">Select a Service:</label>
    <select id="service-select" name="service">
        <option value="">All Services</option>
        <option value="Service A">Service A</option>
        <option value="Service B">Service B</option>
    </select>
    <input type="submit" value="Filter">
</form>

<?php
echo $proc->transformToXML($xml);
?>
</body>
</html>
