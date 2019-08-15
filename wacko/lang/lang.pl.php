<?php

if (!defined('IN_WACKO'))
{
	exit;
}

$wacko_language = [
	'name'					=> "Polski",
	'code'					=> "pl",
	'charset'				=> "iso-8859-2",
	'locale'				=> "pl_PL",
	'utfdecode'				=> [],
	'UPPER_P'				=> "A-Z\xa1\xa3\xa5\xa6\xa9-\xac\xae\xaf\xc0-\xd6\xd8-\xde",
	'LOWER_P'				=> "a-z\xb1\xb3\xb5\xb6\xb9-\xbc\xbe\xbf\xdf-\xf6\xf8-\xfe\/'",
	'ALPHA_P'				=> "A-Za-z\xa1\xa3\xa5\xa6\xa9-\xac\xae\xaf\xb1\xb3\xb5\xb6\xb9-\xbc\xbe-\xd6\xd8-\xf6\xf8-\xfe\_\-\/'",
	'TranslitLettersFrom'	=> "ÁÂÇÉËÍÎÓÔÚÝáâçéëíîóôúýĂăĄąĆćČčĎďĐđĘęĚěĹĺĽľŁłŃńŇňŐőŔŕŘřŚśŞşŠšŢţŤťŮůŰűŹźŻżŽž",
	'TranslitLettersTo'		=> "AACEEIIOOUYaaceeiioouyAaAaCcCcDdDdEeEeLlLlLlNnNnOoRrRrSsSsSsTtTtUuUuZzZzZz",
	'TranslitCaps'			=> "ŔÁÂĂÄĹ¨ĆÇČÉĘËĚÍÎĎĐŃŇÓÔŐÖ×ŘŮÜÚŰÝŢß",
	'TranslitSmall'			=> "ŕáâăäĺ¸ćçčéęëěíîďđńňóôőö÷řůüúűýţ˙",
	'TranslitBiLetters'		=> [
								"ä"=>"ae", "ö"=>"oe", "ü"=>"ue", "Ä"=>"Ae",
								"Ö"=>"Oe", "Ü"=>"Ue", "ß"=>"ss",
								],
	'unicode_entities'		=> [
								"Á"=>"&#193;", "Â"=>"&#194;", "Ä"=>"&#196;", "Ç"=>"&#199;", "É"=>"&#201;", "Ë"=>"&#203;",
								"Í"=>"&#205;", "Î"=>"&#206;", "Ó"=>"&#211;", "Ô"=>"&#212;", "Ö"=>"&#214;", "Ú"=>"&#218;",
								"Ü"=>"&#220;", "Ý"=>"&#221;", "ß"=>"&#223;", "á"=>"&#225;", "â"=>"&#226;", "ä"=>"&#228;",
								"ç"=>"&#231;", "é"=>"&#233;", "ë"=>"&#235;", "í"=>"&#237;", "î"=>"&#238;", "ó"=>"&#243;",
								"ô"=>"&#244;", "ö"=>"&#246;", "ú"=>"&#250;", "ü"=>"&#252;", "ý"=>"&#253;", "Ă"=>"&#258;",
								"ă"=>"&#259;", "Ą"=>"&#260;", "ą"=>"&#261;", "Ć"=>"&#262;", "ć"=>"&#263;", "Č"=>"&#268;",
								"č"=>"&#269;", "Ď"=>"&#270;", "ď"=>"&#271;", "Đ"=>"&#272;", "đ"=>"&#273;", "Ę"=>"&#280;",
								"ę"=>"&#281;", "Ě"=>"&#282;", "ě"=>"&#283;", "Ĺ"=>"&#313;", "ĺ"=>"&#314;", "Ľ"=>"&#317;",
								"ľ"=>"&#318;", "Ł"=>"&#321;", "ł"=>"&#322;", "Ń"=>"&#323;", "ń"=>"&#324;", "Ň"=>"&#327;",
								"ň"=>"&#328;", "Ő"=>"&#336;", "ő"=>"&#337;", "Ŕ"=>"&#340;", "ŕ"=>"&#341;", "Ř"=>"&#344;",
								"ř"=>"&#345;", "Ś"=>"&#346;", "ś"=>"&#347;", "Ş"=>"&#350;", "ş"=>"&#351;", "Š"=>"&#352;",
								"š"=>"&#353;", "Ţ"=>"&#354;", "ţ"=>"&#355;", "Ť"=>"&#356;", "ť"=>"&#357;", "Ů"=>"&#366;",
								"ů"=>"&#367;", "Ű"=>"&#368;", "ű"=>"&#369;", "Ź"=>"&#377;", "ź"=>"&#378;", "Ż"=>"&#379;",
								"ż"=>"&#380;", "Ž"=>"&#381;", "ž"=>"&#382;",
								],
];

?>