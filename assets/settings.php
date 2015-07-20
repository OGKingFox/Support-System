<?php
	$pageStart = microtime(false);
	include 'assets/classes/purify/HTMLPurifier.auto.php';

	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', 'p,ul,ol,li,strong,b,i,u,a[href],code,pre,img[src|alt],br,hr,h1,h2,h3,h4');
    $purifier = new HTMLPurifier($config);


	define("host", "localhost");
	define("user", "root");
	define("pass", "");
	define("database", "");

	define("skills", "Overall,Attack,Defence,Strength,Constitution,Ranged,Prayer,Magic,Cooking,Woodcutting,Fletching,Fishing,Firemaking,Crafting,Smithing,Mining,Herblore,Agility,Thieving,Slayer,Farming,Runecrafting,Hunter,Construction,Summoning,Dungeoneering,Assassin");

	define("enc_key", "+0491!03"); #change before making an account!
	define("live_support", 1);

	
	require 'assets/classes/database.class.php';
	$db = new Database(host, user, pass, database); 
	$db->connect();

	define("api_user", "KingFox");
	define("api_pass", "");
	define("from_email", "");

	#end mail settings
	
	function getPage() {
		return basename($_SERVER['PHP_SELF']);
	}

	function isValidSkill($skill) {
		if (strtolower($skill) == "prestige") {
			return true;
		}
		$skills = explode(',', skills);
		foreach ($skills as $sk) {
			if (strtolower($sk) == strtolower($skill))
				return true;
		}
		return false;
	}
	
	function formatName($string) {
		return ucwords(str_replace("_", " ", $string));
	}
	
	function cleanString($string) {
		return preg_replace("/[^A-Za-z0-9 ]/", ' ', $string);
	}
	
	function cleanInt($string) {
		return preg_replace("/[^0-9]/", ' ', $string);
	}

	function nl2br_special($string){

	    // Step 1: Add <br /> tags for each line-break
	    $string = nl2br($string); 

	    // Step 2: Remove the actual line-breaks
	    $string = str_replace("\n", "", $string);
	    $string = str_replace("\r", "", $string);

	    // Step 3: Restore the line-breaks that are inside <pre></pre> tags
	    if(preg_match_all('/\<pre\>(.*?)\<\/pre\>/', $string, $match)){
	        foreach($match as $a){
	            foreach($a as $b){
	            $string = str_replace('<pre>'.$b.'</pre>', "<pre>".str_replace("<br />", PHP_EOL, $b)."</pre>", $string);
	            }
	        }
	    }

	    // Before <pre> tags
	    $string = str_replace("<br /><br /><br /><pre>", '<br /><br /><pre>', $string);
	    // After </pre> tags
	    $string = str_replace("</pre><br /><br />", '</pre><br />', $string);

	    // Arround <ul></ul> tags
	    $string = str_replace("<br /><br /><ul>", '<br /><ul>', $string);
	    $string = str_replace("</ul><br /><br />", '</ul><br />', $string);
	    // Inside <ul> </ul> tags
	    $string = str_replace("<ul><br />", '<ul>', $string);
	    $string = str_replace("<br /></ul>", '</ul>', $string);

	    // Arround <ol></ol> tags
	    $string = str_replace("<br /><br /><ol>", '<br /><ol>', $string);
	    $string = str_replace("</ol><br /><br />", '</ol><br />', $string);
	    // Inside <ol> </ol> tags
	    $string = str_replace("<ol><br />", '<ol>', $string);
	    $string = str_replace("<br /></ol>", '</ol>', $string);

	    // Arround <li></li> tags
	    $string = str_replace("<br /><li>", '<li>', $string);
	    $string = str_replace("</li><br />", '</li>', $string);

	    return $string;
	}
?>