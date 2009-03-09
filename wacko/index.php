<?php
// app start
error_reporting (E_ALL ^ E_NOTICE);

if (ini_get("zlib.output_compression"))
ob_start();
else
ob_start("ob_gzhandler");

// do not change this two lines, PLEASE-PLEASE. In fact, don't change anything! Ever!
define("WAKKA_VERSION", "0.1.2");
define("WACKO_VERSION", "R4.3");
define('XML_HTMLSAX3', dirname(__FILE__)."/lib/HTMLSax3/");

// stupid version check
if (!isset($_REQUEST)) die('$_REQUEST[] not found. WackoWiki requires PHP 4.3.3 or higher!');

// workaround for the amazingly annoying magic quotes.
function magicQuotesSuck(&$a)
{
	if (is_array($a))
	{
		foreach ($a as $k => $v)
		{
			if (is_array($v))
			magicQuotesSuck($a[$k]);
			else
			$a[$k] = stripslashes($v);
		}
	}
}
set_magic_quotes_runtime(0);

if (get_magic_quotes_gpc())
{
	magicQuotesSuck($_POST);
	magicQuotesSuck($_GET);
	magicQuotesSuck($_COOKIE);
	magicQuotesSuck($_SERVER);
	magicQuotesSuck($_REQUEST);
}

if (strstr($_SERVER["SERVER_SOFTWARE"], "IIS")) $_SERVER["REQUEST_URI"] = $_SERVER["PATH_INFO"];

$found_rewrite_extension = function_exists('apache_get_modules') ? in_array('mod_rewrite', apache_get_modules()) : false;

// default configuration values
$wackoDefaultConfig = array(
  "database_driver" => "",
  "database_host" => "localhost",
  "database_port" => "",
  "database_database" => "wacko",
  "database_user" => "",
  "database_password" => "",

  "table_prefix" => "wacko_",
  "cookie_prefix" => "wacko_",
  "session_prefix" => "wacko43_",

  "xml_sitemap" => 0,

  "root_page" => "HomePage",
  "wacko_name" => "MyWackoSite",
  "base_url" => ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http').'://'.$_SERVER["SERVER_NAME"].
($_SERVER["SERVER_PORT"] != 80 ? ":".$_SERVER["SERVER_PORT"] : "").
preg_replace("/(\?|&)installAction=site-config/","",$_SERVER["REQUEST_URI"]).
($found_rewrite_extension ? "" : "?page="),
  "rewrite_mode" => ($found_rewrite_extension ? "1" : "0"),

  "action_path" => "actions",
  "handler_path" => "handlers",

  "language" => "en",
  "theme" => "default",

  "header_action" => "header",
  "footer_action" => "footer",

  "show_datetime" => "Y",
  "show_spaces" => "Y",

//  "default_bookmarks" => "((PageIndex Index))\n((RecentChanges Changes))\n((RecentlyCommented Comments))\n((Users))\n((Registration))",
//  "site_bookmarks" => "((PageIndex Index)) / ((RecentChanges Changes)) / ((RecentlyCommented Comments))",

  "default_typografica" => 1,
  "default_showdatetime" => 1,
  "paragrafica" => 1,

  "referrers_purge_time" => 1,
  "pages_purge_time" => 0,

  "hide_files" => 0,
  "hide_comments" => 0,

  "debug" => 0,
  "youarehere_text" => "",
  "hide_locked" => 1,
  "allow_rawhtml" => 1,
  "disable_safehtml" => 0,
  "urls_underscores" => 0,

  "allrecentchanges_page" => "",
  "allpageindex_page" => "",

  "default_write_acl" => "$",
  "default_read_acl" => "*",
  "default_comment_acl" => "$",
  "default_rename_redirect" => 1,
  "owners_can_remove_comments" => 1,
  "allow_registration" => 1,

  "standard_handlers" => "acls|addcomment|claim|diff|edit|latex|msword|print|referrers|referrers_sites|remove|rename|revisions|revisions\.xml|show|watch|settings",

  "revisions_hide_cancel" => 0,
  "footer_comments" => 1,
  "footer_files" => 1,

  "disable_tikilinks" => 0,
  "remove_onlyadmins" => 0,

  "upload" => "admins",
  "upload_images_only" => 0,
  "upload_max_size" => 190,
  "upload_max_per_user" => 100,
  "upload_path" => "files",
  "upload_path_per_page" => "files/perpage",
  "upload_banned_exts" => "php|cgi|js|php|php3|php4|php5|pl|ssi|jsp|phtm|phtml|shtm|shtml|xhtm|xht|asp|aspx|htw|ida|idq|cer|cdx|asa|htr|idc|stm|printer|asax|ascx|ashx|asmx|axd|vdisco|rem|soap|config|cs|csproj|vb|vbproj|webinfo|licx|resx|resources",

  "outlook_workaround" => 1,
  "disable_autosubscribe" => 0,
  "allow_gethostbyaddr" => 1,

  "multilanguage" => 1,

  "cache" => 0,
  "cache_dir" => "_cache/",
  "cache_ttl" => 600,

  "db_collation" => 0,
  "rename_globalacl" => "Admins",

  "spam_filter" => 1,

  "captcha_new_comment" => 1,
  "captcha_new_page" => 1,
  "captcha_edit_page" => 1,
  "captcha_registration" => 1,
);

$wackoDefaultConfig['aliases'] = array('Admins' => "",);

// load config
if (!$configfile = GetEnv("WAKKA_CONFIG")) $configfile = "config.inc.php";
if (@file_exists($configfile)) include($configfile);
$wackoConfigLocation = $configfile;
$wackoConfig = array_merge($wackoDefaultConfig, (array)$wackoConfig);

// check for locking
if (@file_exists("locked"))
{
	// read password from lockfile
	$lines = file("locked");
	$lockpw = trim($lines[0]);

	// is authentification given?
	if (isset($_SERVER["PHP_AUTH_USER"]))
	{
		if (!(($_SERVER["PHP_AUTH_USER"] == "admin") && ($_SERVER["PHP_AUTH_PW"] == $lockpw)))
		{
			$ask = 1;
		}
	}

	else
	{
		$ask = 1;
	}

	if ($ask)
	{
		header("WWW-Authenticate: Basic realm=\"".$wackoConfig["wacko_name"]." Install/Upgrade Interface\"");
		header("HTTP/1.1 503 Service Temporarily Unavailable");
		print("This site is currently being upgraded. Please try again later.");
		exit;
	}
}

// compare versions, start installer if necessary
if ($wackoConfig["wacko_version"] != WACKO_VERSION)
{
	if (!$_REQUEST["installAction"] && !strstr($_SERVER["SERVER_SOFTWARE"], "IIS"))
	{
		$req = $_SERVER["REQUEST_URI"];
		if ($req{strlen($req) - 1} != "/" && strstr($req, ".php") != ".php")
		{
			header("Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]."/");
			exit;
		}
	}

	// start installer
	if (!$installAction = trim($_REQUEST["installAction"])) $installAction = "lang";
	include("setup/header.php");
	
	if (@file_exists("setup/".$installAction.".php")) 
	include("setup/".$installAction.".php"); 
	
	else print("<em>Invalid action</em>");
	include("setup/footer.php");
	
	exit;
}

// set root_url & theme_url
if (!isset($wackoConfig["root_url"])) $wackoConfig["root_url"]=preg_replace("#/[^/]*$#","/",$wackoConfig["base_url"]);
$wackoConfig["theme_url"]=$wackoConfig["root_url"]."themes/".$wackoConfig["theme"]."/";

//user-table
if (!isset($wackoConfig["user_table"]) && !$wackoConfig["user_table"]) $wackoConfig["user_table"] = $wackoConfig["table_prefix"]."users";

// fetch wacko location
if (isset($_SERVER["PATH_INFO"]) && function_exists("virtual")) $request = $_SERVER["PATH_INFO"];
else $request = @$_REQUEST["page"];

// fix win32 apache 1 bug
if (stristr($_SERVER["SERVER_SOFTWARE"], "Apache/1") && stristr($_SERVER["SERVER_SOFTWARE"], "Win32") && $wackoConfig["rewrite_mode"])
{
	$dir = str_replace("http://".$_SERVER["SERVER_NAME"].($_SERVER["SERVER_PORT"] != 80 ? ":".$_SERVER["SERVER_PORT"] : ""),"",$wackoConfig["base_url"]);
	$request = preg_replace("+^".preg_quote(rtrim($dir,"/"))."+i","",$_SERVER["REDIRECT_URL"]);//$request);
}

// remove leading slash
$request = preg_replace("/^\//", "", $request);
$method = '';

// split into page/method
$p = strrpos($request, "/");

if ($p === false)
{
	$page = $request;
}
else
{
	$page = substr($request, 0, $p);
	$m1 = $method = strtolower(substr($request, $p - strlen($request) + 1));
	if (!@file_exists($wackoConfig["handler_path"]."/page/".$method.".php"))
	{
		$page = $request;
		$method = "";
	}

	else if (preg_match( '/^(.*?)\/('.$wackoConfig["standard_handlers"].')($|\/(.*)$)/i', $page, $match ))
	{
		//translit case
		$page = $match[1];
		$method = $match[2];
	}
}

// Load the correct database connector
if (!isset( $wackoConfig["database_driver"] )) $wackoConfig["database_driver"] = "mysql";

switch($wackoConfig["database_driver"])
{
	case "mysql_legacy":
		$dbfile = "db/mysql.php";
		break;
	case "mysqli_legacy":
		$dbfile = "db/mysqli.php";
		break;
	default:
		$dbfile = "db/pdo.php";
		break;
}

if (@file_exists($dbfile)) include($dbfile);
else die("Error loading Database Connector.");

// cache!
require("classes/cache.php");
$cache = &new Cache($wackoConfig["cache_dir"], $wackoConfig["cache_ttl"]);

$iscache = null;
if ($wackoConfig["cache"] &&  $_SERVER["REQUEST_METHOD"] != "POST" && $method != "edit" && $method != "watch")
{
	// anonymous
	if (!$_COOKIE[$wackoConfig["cookie_prefix"]."name"])
	{
		$iscache = $cache->CheckHttpRequest($page, $method);
	}
}

// start session
session_start();

// create wacko object
require("classes/wacko.php");
$wacko = &new Wacko($wackoConfig);
$wacko->headerCount = 0;
$cache->wacko = &$wacko;
$wacko->cache = &$cache;
if ($method && $method != "show") unset($wacko->config["youarehere_text"]);

// go!
$pg = $wacko->Run($page, $method);

if ($iscache)
{
	$data = ob_get_contents();
	$cache->StoreToCache($data);
}

// how much time script take
$ddd = $wacko->GetMicroTime();
if ($wacko->GetConfigValue("debug") >= 1 && strpos($method,".xml") === false && $method != "print")
{
	echo ("<div class=\"time\">".$wacko->GetTranslation("MeasuredTime").": ".(number_format(($ddd-$wacko->timer), 3))." s<br />");
	if ($mem = @memory_get_usage()) echo ($wacko->GetTranslation("MeasuredMemory").": ".(number_format(($mem/(1024*1024)), 3))." Mb");
	if ($wacko->GetConfigValue("debug") >= 2)
	{
		$sql_time = 0;
		foreach($wacko->queryLog as $q)
		$sql_time += $q["time"];
		echo (" &nbsp; SQL time: ".$sql_time);
	}
	echo "</div>";
}

// closing tags
if (strpos($method,".xml") === false)
echo "\n</body>\n</html>";
?>