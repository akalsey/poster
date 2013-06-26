<?php
include 'compat.php';

$files = glob("log/*.txt");
rsort($files);
foreach ($files as $filename) {
  $timestamp = str_replace('.txt', '', $filename);
  $timestamp = substr($timestamp, 18);
  if ($timestamp < time() - 60*60*24) {
    unlink($filename);
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (array_key_exists('PATH_INFO', $_SERVER)) {
    print "<h1>Poster</h1>";
    print "<p>Post to <code>http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}</code></p>";
    foreach ($files as $filename) {
      $id = substr($_SERVER['PATH_INFO'], 1);
      if (stripos($filename, $id) == 4) {
        $timestamp = str_replace('.txt', '', $filename);
        $timestamp = str_replace('log/'.$id . '-', '', $timestamp);
        $file = fopen($filename, "r");
        $contents = fread($file, filesize($filename));
        fclose($file);
        echo "\n<hr/><pre>\n" . htmlentities($contents) . "\n</pre>\n";        
      }
    }
  } else {
    $path = uniqid();
    header('Location: ' . $_SERVER['PHP_SELF'] . '/' . $path); 
  }

} else {
  $req = date('r') . "\n";
  $req .= "{$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']}\n";
  if (array_key_exists('QUERY_STRING', $_SERVER)) {
    $req .= "?{$_SERVER['QUERY_STRING']}\n";
  }
  
  $headers = apache_request_headers();
  foreach ($headers as $header => $value) {
      $req .= "$header: $value\n";
  }
  $req .= "\n". file_get_contents("php://input");
  $filename = dirname(__FILE__) . '/log' . $_SERVER['PATH_INFO'] . '-' . time(). ".txt";
  $file = fopen($filename, 'w');
  fwrite($file, $req);
  fclose($file);
}
?>