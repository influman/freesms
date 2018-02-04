<?php
   $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";      
   //***********************************************************************************************************************
   // V1.1 : Notification SMS via API Free Mobile
   
	// recuperation des infos depuis la requete
    $value = getArg("value");
	$key = getArg("key");
	$login = getArg("login");
	$periph_id = getArg('eedomus_controller_module_id'); 
	$chatbot_output = getArg("chatbot");
	
	$notif = "";
	if ($value != "" && $value >= 0 && $key != "plugin.parameters.free_login" && $event != "plugin.parameters.free_key") {
		// récupérer description de l'actionneur déclenché
		$tab_value = getPeriphValueList($periph_id);
		foreach($tab_value As $tab_notif_value) {
			if ($tab_notif_value["value"] == $value) {
				$notif = $tab_notif_value["state"]; 
				break;
			}
		}
		
		While (strpos($notif, "[") !== false) {
			$startstring = "";
			if (strpos($notif, "[") > 0) {
				$startstring = substr($notif, 0, strpos($notif, "["));
			}
			$nextperiph = substr($notif, strpos($notif, "[") + 1, strpos($notif, "]") - strpos($notif, "[") - 1);
			$endstring = "";
			if (substr($notif, strpos($notif, "]") + 1) !== false) {
				$endstring = substr($notif, strpos($notif, "]") + 1);
			}
			$periph_text = $nextperiph;
			if ($nextperiph != "" && $nextperiph != "PERIPH_ID") {
				if ($nextperiph == "BR" || $nextperiph == "br") {
					$periph_text = "\r";
				} else if ($nextperiph == "DATE" || $nextperiph == "date") {
						$periph_text = date('d/m/Y');
				} else if ($nextperiph == "TIME" || $nextperiph == "time") {
						$periph_text = date('H:i');
				} else if ($nextperiph == "CHATBOT") {
						    // le chatbot a demandé une notification
							if (is_numeric($chatbot_output) && $chatbot_output > 1) {
							    // lit le périphérique Output s'il est indiqué dans VAR3
								$tabperiph_text = getValue($chatbot_output);
								$periph_text = $tabperiph_text['value'];
								// sinon recherche dans les variables de passage du chatbot	
							} else {
								$chatbotok = false;
								$msg_chatbot = loadVariable("CHATBOT_9999_1", $scope = "chatbot");
								if ($msg_chatbot != '' && substr($msg_chatbot,0,8) != "## ERROR") {
									$periph_text = $msg_chatbot;
									$chatbotok = true;
								}
								if (!$chatbotok) {
									$msg_chatbot = loadVariable("CHATBOT_9999_2", $scope = "chatbot");
									if ($msg_chatbot != '' && substr($msg_chatbot,0,8) != "## ERROR") {
										$periph_text = $msg_chatbot;
										$chatbotok = true;
									}
								}
								if (!$chatbotok) {
									$msg_chatbot = loadVariable("CHATBOT_9999_3", $scope = "chatbot");
									if ($msg_chatbot != '' && substr($msg_chatbot,0,8) != "## ERROR") {
										$periph_text = $msg_chatbot;
										$chatbotok = true;
									}
								}
								if (!$chatbotok) {
									$msg_chatbot = loadVariable("CHATBOT_9999_4", $scope = "chatbot");
									if ($msg_chatbot != '' && substr($msg_chatbot,0,8) != "## ERROR") {
										$periph_text = $msg_chatbot;
										$chatbotok = true;
									}
								}
								if (!$chatbotok) {
									$msg_chatbot = loadVariable("CHATBOT_9999_5", $scope = "chatbot");
									if ($msg_chatbot != '' && substr($msg_chatbot,0,8) != "## ERROR") {
										$periph_text = $msg_chatbot;
										$chatbotok = true;
									}
								}
								
								if (!$chatbotok) {
									$periph_text = "??? chatBOT ???";
								}
							} // fin output 
				} else if ($nextperiph == "ASK") {
						    $askok = false;
							$msg_ask = loadVariable("ASK_99999_1", $scope = "ask");
							if ($msg_ask != '' && substr($msg_ask,0,8) != "## ERROR") {
								$periph_text = $msg_ask;
								$askok = true;
							}
							if (!$askok) {
								$msg_ask = loadVariable("ASK_99999_2", $scope = "ask");
								if ($msg_ask != '' && substr($msg_ask,0,8) != "## ERROR")  {
									$periph_text = $msg_ask;
									$askok = true;
								}
							}
								
							if (!$askok) {
								$periph_text = "??? ASK ???";
							}
						
				} else if (is_numeric($nextperiph) && $nextperiph > 1) {
						$tab_periph = getValue($nextperiph, true);
						$periph_text = $tab_periph['value_text'];
						$periph_value = $tab_periph['value'];
						if ($periph_text == "") {
							$periph_text = $periph_value;
						}
				}
			} 
			
			$notif = $startstring.$periph_text.$endstring;
		}
		
		$value1 = $notif;
		
		$value1 = sdk_fullescape(sdk_noaccent($value1));
		
		if ($value1 == "") {
			$value1 = "value1 error";
		}
		
		$url = "https://smsapi.free-mobile.fr/sendmsg?user=".$login."&pass=".$key."&msg=".$value1;
		httpQuery($url,'GET'); 	
		
	}

	function sdk_noaccent($text) {
		$utf8_keys = array(	'/[áàâãä]/','/[ÁÀÂÃÄ]/', '/[ÍÌÎÏ]/', '/[íìîï]/', '/[éèêë]/', '/[ÉÈÊË]/', '/[óòôõö]/', '/[ÓÒÔÕÖ]/', '/[úùûü]/', '/[ÚÙÛÜ]/' ,	'/ç/', '/Ç/', '/ñ/', '/Ñ/');
		$utf8_values = array('a', 'A', 'I',	'i', 'e', 'E', 'o',	'O', 'u', 'U', 'c',	'C', 'n', 'N');
	return preg_replace($utf8_keys, $utf8_values, $text);
	}	
	
		function sdk_fullescape($in)
	{
  $out = urlencode($in);
  
  $out = str_replace('+','%20',$out);
  $out = str_replace('_','%5F',$out);
  $out = str_replace('.','%2E',$out);
  $out = str_replace('-','%2D',$out);
  $out = str_replace('%B0','%27',$out);
  return $out;
	} 
?>
