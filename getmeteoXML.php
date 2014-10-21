<?php
function forecastAsXHTML($date,$desc,$icon,$tmax,$tmin) {
	$theXHTML = new SimpleXMLElement("<div/>");
	$table = $theXHTML->addChild("table");
	$table->addChild("tr")->addChild("td",$date)->addAttribute("colspan",2);
	$table->addChild("tr")->addChild("td",$desc)->addAttribute("colspan",2);
	$rowaux = $table->addChild("tr");
	$tdaux =  $rowaux->addChild("td");
	$tdaux->addAttribute("rowspan",2);
	$tdaux->addChild("img")->addAttribute("src",$icon);
	$rowaux->addChild("td",$tmin);
	$table->addChild("tr")->addChild("td",$tmax);
	
	return $theXHTML->asXML();
}
function forecastAsHTML($date,$desc,$icon,$tmax,$tmin) {
	$theHTML = "";
	$theHTML .= "<div>";
	$theHTML .= "<table>";
	$theHTML .= "<tr>";
	$theHTML .= "<td colspan=\"2\">";
	$theHTML .= $date;
	$theHTML .= "</td>";
	$theHTML .= "</tr>";
	$theHTML .= "<tr>";
	$theHTML .= "<td colspan=\"2\">";
	$theHTML .= $desc;
	$theHTML .= "</td>";
	$theHTML .= "</tr>";
	$theHTML .= "<tr>";
	$theHTML .= "<td rowspan=\"2\">";
	$theHTML .= "<img src=\"".$icon."\"/>";
	$theHTML .= "</td>";
	$theHTML .= "<td>";
	$theHTML .= $tmax;
	$theHTML .= "</td>";
	$theHTML .= "</tr>";
	$theHTML .= "<tr>";
	$theHTML .= "<td>";
	$theHTML .= $tmin;
	$theHTML .= "</td>";
	$theHTML .= "</tr>";
	$theHTML .= "</table>";
	$theHTML .= "</div>";
	return $theHTML;
}
?>
<html>
	<head>
		<meta charset="utf-8"/>
		<style>
			div {
				width:150px;
				height:150px;
				background-color: yellow;
				border-color: black;
				border-style: solid;
				border-width:2px;
				border-radius:8px;
				display:inline-block;
				margin:2px;
				padding:2px;
				
			}
		</style>
	</head>
	<body>
		<h1>previsão meteorológica de <?php echo $_GET['location'] ?></h1>
		<?php
		//api.worldweatheronline.com/free/v1/weather.ashx?q=perre%2C%20pt&format=json&num_of_days=5&key=jx6a4hxmgej238dw8x4p8vvc
		$coreURL = "http://api.worldweatheronline.com/free/v1/weather.ashx?";
		$sep = "&";
		$p_q = "q=".urlencode($_GET['location']);
		$p_nd = "num_of_days=5";
		$p_key = "key=jx6a4hxmgej238dw8x4p8vvc";
		$p_format = "format=xml";
		
		$callURL = $coreURL.$p_q.$sep.$p_nd.$sep.$p_key.$sep.$p_format;
		echo "<hr/>";
		echo $callURL;
		echo "<hr/>";
		
		$forecastXML = file_get_contents($callURL);
		
		$forecastPHP = simplexml_load_string($forecastXML,null,LIBXML_NOCDATA);
		
		//echo $forecastXML;
		
		echo "<hr/>";
		echo "xxx" . $forecastPHP->current_condition[0]->temp_C;
		echo "<hr/>";
		
		echo "<hr/>";
		$forecastArray = $forecastPHP->weather;
		foreach($forecastArray as $forecastDay) {
			$date = $forecastDay->date;
			$tmin = $forecastDay->tempMinC;
			$tmax = $forecastDay->tempMaxC;
			$desc = $forecastDay->weatherDesc[0];
			$icon = $forecastDay->weatherIconUrl[0];
			//echo forecastAsHTML($date, $desc, $icon, $tmax, $tmin);
			echo forecastAsXHTML($date, $desc, $icon, $tmax, $tmin);
			
		}
				
		echo "<hr/>";
		?>
	</body>
</html>