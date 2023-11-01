<?php
extension_loaded('xsl') or die('XSL extension not loaded');

$xml = new DOMDocument;
$xml->load('reservation.xml');

$xsl = new DOMDocument;
$xsl->load('reservationExporter.xsl');

$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);

echo $proc->transformToXML($xml);

$xml = simplexml_load_file('reservation.xml');
$json = json_encode($xml, JSON_PRETTY_PRINT);

file_put_contents('reservation.json', $json);