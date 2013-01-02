<?
require("db.php");
require("config.php");

$db = new db($dbLang, $dbName);

$header_location = "Location: " . $baseUrl . "login.php";

if (!(checklogin($db) & 1))
{
header("$header_location");
}

if(isset($_POST['delete'])) {
  if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $error = "Ein Fehler beim Löschen ist aufgetreten!";
    goto end;
  }
  $db->query("DELETE FROM users WHERE email = '{$_POST['email']}'");
}

$users = $db->query("SELECT * FROM users;");

end:
$db->close();

?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <title>Piraten-Newsletter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hier können sich Interessenten und Mitglieder für den Newsletter der Piratenpartei Österreichs anmelden.">
    <meta name="author" content="Piratenpartei Österreichs">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
	body {
	background-color: #4c2582;
        padding-top: 60px;
        padding-bottom: 40px;
        }
	footer {
	color: white;
	}
<?echo $display;?>
    </style>

    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons
    <link rel="shortcut icon" href="ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">-->
  </head>

  <body>

    <div class="container">
      <div class="row">
        <div class="span12">
	  <div id="list_view" class="well">
	    <h1>Teilnehmer-Liste</h1>
	    <p>
              <table class="table">
                <tr><td>Adresse</td><td>Präferenzen</td><td>Bestätigt</td><td>Optionen</td></tr>
<?
foreach ($users as $user)
{
  echo '<tr><td><a href="mailto:' . $user['email'] . '">' . $user['email'] . '</a></td><td>' . implode(', ', decodePrefs($user['prefs'])) . '</td><td>' . ($user['confirmed'] == 't' ? 'Ja' : 'Nein') . '</td><td><form action="list.php" method="POST"><input type="hidden" name="email" value="' . $user['email'] . '" /><input type="submit" name="delete" value="Abmelden" class="btn btn-danger"/></form></td></tr>';
}
?>
              </table>
            </p>
	  </div>
        </div><!--/span-->
      </div><!--/row-->

      <footer>
        <p>Piratenpartei Österreichs, Lange Gasse 1/4, 1080 Wien</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>
  </body>
</html>
