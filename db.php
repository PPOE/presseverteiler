<?php
class db {
private $dbConn_ = null;
private $dbType_ = null;
public function __construct($dbType, $name, $host = null, $user = null, $pass = null)
{
  $this->dbType_ = $dbType;
  switch ($this->dbType_)
  {
    case 'mysql':
      $this->dbConn_ = mysql_connect($host, $user, $pass);
      if (!$this->dbConn_)
      {
        die('Could not connect: ' . mysql_error());
      }
      mysql_select_db($name, $this->dbConn_);
      break;
    case 'pgsql':
      $this->dbConn_ = pg_connect("dbname=$name")
        or die('Could not connect: ' . pg_last_error());
      break;
    default:
      die("invalid dbtype!");
  }
}
public function query($query)
{
  $result_array = array();
  switch ($this->dbType_)
  {
    case 'mysql':
      $result = mysql_query($query);
      if (!$result)
        return $false;

      while ($line = mysql_fetch_assoc($result)) {
        $result_array[] = $line;
      }
      break;
    case 'pgsql':
      $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error() . '<br><br>' . $query);
      if (!$result)
        return $false;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $result_array[] = $line;
      }
      pg_free_result($result);
      break;
  }
  return $result_array;
}
public function close()
{
  switch ($this->dbType_)
  {
    case 'mysql':
      mysql_close($this->dbConn_);
      break;
    case 'pgsql':
      pg_close($this->dbConn_);
      break;
    default:
      die("invalid dbtype!");
  }
}
public function escape($text)
{
  switch ($this->dbType_)
  {
    case 'mysql':
      return mysql_escape_string($text);
    case 'pgsql':
      return pg_escape_string($text);
    default:
      die("invalid dbtype!");
  }
}
}

function login($db, $user, $pass)
{
  $success = true;
  $usr_id = 0;
  if ($success)
  {
    $rand = mt_rand();
    $db->query("UPDATE admins SET cookie = $rand, login = now() WHERE usr_id = $usr_id");
    setcookie("pp_newsletter_login", $rand, time()+86400);
  }
}

function checklogin($db)
{
  if (isset($_COOKIE["pp_newsletter_login"]) && preg_match('/^-?\d+$/', $_COOKIE["pp_newsletter_login"]) == 1)
  {
    $admins = $db->query("SELECT * FROM admins WHERE cookie = {$_COOKIE["pp_newsletter_login"]} AND login > now() - '24 hours'::interval");
    if (count($admins) == 1)
    {
      return intval($admins[0]['rights']);
    }
  }
  return 0;
}

function checklogin_id($db)
{
  if (isset($_COOKIE["pp_newsletter_login"]) && preg_match('/^-?\d+$/', $_COOKIE["pp_newsletter_login"]) == 1)
  {
    $admins = $db->query("SELECT * FROM admins WHERE cookie = {$_COOKIE["pp_newsletter_login"]} AND login > now() - '24 hours'::interval");
    if (count($admins) == 1)
    {
      return intval($admins[0]['usr_id']);
    }
  }
  return 0;
}

function decodePrefs($prefs)
{
  $prefs = intval($prefs);
  if ($prefs & 1)
    $pa[] = "Bundesweite Informationen";
  if ($prefs & 2)
    $pa[] = "Burgenland";
  if ($prefs & 4)
    $pa[] = "Kärnten";
  if ($prefs & 8)
    $pa[] = "Niederösterreich";
  if ($prefs & 16)
    $pa[] = "Oberösterreich";
  if ($prefs & 32)
    $pa[] = "Salzburg";
  if ($prefs & 64)
    $pa[] = "Steiermark";
  if ($prefs & 128)
    $pa[] = "Vorarlberg";
  if ($prefs & 256)
    $pa[] = "Wien";
  return $pa;
}


?>