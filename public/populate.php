<?php
require_once '../include/config.php';
$htmlFile = 'PATH-TO-HTML-DATA-FILE';

function extractContent($tag, $html) {
    $closeTag = substr($tag, 0, 1) . '/' . substr($tag, 1, 3);
    $startPos = strlen($tag);
    $endPos = strpos($html, $closeTag);
    $text = substr($html, $startPos, $endPos - $startPos);
    return array($closeTag, trim(preg_replace('/(\s){2,}/', ' ', $text)));
}

$html = file_get_contents($htmlFile);
$limits = array('min' => 200, 'max' => 2000);
$tag = '<p>';
$paragraphs = array();

$i = 0;
while (($pos = strpos($html, $tag, $i)) !== false) {
    list($closeTag, $text) = extractContent($tag, substr($html, $pos));
    // keep the content if it's a suitable size
    $len = strlen($text);
    if ($len >= $limits['min'] && $len <= $limits['max']) {
        $paragraphs[] = $text;
    }
    $i = $pos + strlen($tag) + strlen($text) + strlen($closeTag);
}

$db = new PDO(DBDSN, DBUSER, DBPASS);

$query = $db->prepare('INSERT INTO paragraphs (content) VALUES (:content)');
$query->bindParam(':content', $content);
foreach ($paragraphs as $content) {
    $query->execute();
}
