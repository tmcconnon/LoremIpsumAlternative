<?php
require_once '../include/config.php';

function selectParagraph($db, $id) {
    $sql = sprintf('SELECT content FROM paragraphs WHERE id = %d', $id);
    $result = $db->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $result->closeCursor();
    return $row['content'];
}

$db = new PDO(DBDSN, DBUSER, DBPASS);
$result = $db->query('SELECT MAX(id) FROM paragraphs');
$maxID = $result->fetchColumn();
$result->closeCursor();
?>
<html>
 <body>
  <form method="post">
   <label for="slider">How many paragraphs do you want?</label>
   <input type="range" min="1" max="4" step="1" name="slider">
   <input type="submit" name="submit" value="Get Excerpt">
  </form>
<?php
if (isset($_POST['slider'])) {
    $i = $_POST['slider'];
    while ($i--) {
        $id = rand(1, $maxID);
        $paragraph = selectParagraph($db, $id);
        echo '<p>' . $paragraph . '</p>';
    }
}
?>
 </body>
</html>
