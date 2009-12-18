<?php
error_reporting(E_ALL);

if (!function_exists('mysql_connect'))
{
  echo "Error: MySQL module not loaded?\n";
  exit(1);
}

$fp = fopen('php://stdin', 'r');

echo "\nThis script will install PowerDNS GUI on this server\n\nPress ENTER to continue Ctrl-C to abort...\n";

fgets($fp, 1024);

echo "Enter MySQL host name: ";

$db_host = trim(fgets($fp, 1024));

echo "Enter MySQL database name: ";

$db_name = trim(fgets($fp, 1024));

echo "Enter MySQL user name: ";

$db_user = trim(fgets($fp, 1024));

$db_pass = prompt_silent("Enter MySQL password: ");

if (!$link = @mysql_connect($db_host,$db_user,$db_pass))
{
  echo "\nError: ".mysql_error()."\n\n";
  exit(1);
}

if (!@mysql_select_db($db_name))
{
  echo "\nError: ".mysql_error()."\n\n";
  exit(1);
}



/**
 * Interactively prompts for input without echoing to the terminal.
 * Requires a bash shell or Windows and won't work with
 * safe_mode settings (Uses `shell_exec`)
 */
function prompt_silent($prompt = "Enter Password:") {
  if (preg_match('/^win/i', PHP_OS)) {
    $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
    file_put_contents(
      $vbscript, 'wscript.echo(InputBox("'
      . addslashes($prompt)
      . '", "", "password here"))');
    $command = "cscript //nologo " . escapeshellarg($vbscript);
    $password = rtrim(shell_exec($command));
    unlink($vbscript);
    return $password;
  } else {
    $command = "/usr/bin/env bash -c 'echo OK'";
    if (rtrim(shell_exec($command)) !== 'OK') {
      trigger_error("Can't invoke bash");
      return;
    }
    $command = "/usr/bin/env bash -c 'read -s -p \""
      . addslashes($prompt)
      . "\" mypassword && echo \$mypassword'";
    $password = rtrim(shell_exec($command));
    echo "\n";
    return $password;
  }
}


?>
