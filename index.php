<?php
include 'compat.php';

$files = glob("*.txt");
rsort($files);
foreach ($files as $filename) {
  $timestamp = str_replace('.txt', '', $filename);
  if ($timestamp < time() - 60*60*24) {
    unlink($filename);
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  print "<h1>Poster</h1>";
  foreach ($files as $filename) {
    $timestamp = str_replace('.txt', '', $filename);
    echo "<h4>" . date('F j, Y g:i:s e',$timestamp) . "</h4\n";
    $file = fopen($filename, "r");
    $contents = fread($file, filesize($filename));
    fclose($file);
    echo "<pre>\n" . htmlentities($contents) . "\n</pre>\n<hr/>\n";
  }
} else {
  $req = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']}\n";
  $req .= "?{$_SERVER['QUERY_STRING']}\n";
  
  $headers = apache_request_headers();
  foreach ($headers as $header => $value) {
      $req .= "$header: $value\n";
  }
  $req .= "\n". file_get_contents("php://input");
  $file = fopen(time(). ".txt", 'w');
  fwrite($file, $req);
  fclose($file);
}
?>