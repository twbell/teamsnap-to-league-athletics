<?php
/**
 * Writes Teamsnap roster to League Athletics
 * @author tyler@redhawkslacrosse.org
 * Syntax = php teamsnap-to-league-athletics.php <teamsnap export>.csv [<coaches export>.csv>]
 */

	//Set vars
	error_reporting (E_ERROR);
	$delimiter = ",";
	
	//assign args
	$fileIn = $argv[1];
	//if (isset($argv[2])){$delimiter = $argv[2];}
	//if (isset($argv[3])){$header = $argv[3];}
	
	//$header =  ($header) ? (bool)$header : true;
	if (!$fp = fopen($fileIn, "r")){
		echo "Could not open '".$fileIn."' for reading\n";
		exit;
	}
	
	//output
	$out = "west_valley_red_hawks_".date("Y-m-d").".csv";
	$pointer = fopen($out, 'w');
	
	//header
	$header = array("RecordID","SerialNo","Expiration Date","First Name","MI","Last Name","Address 1","Address 2","City","State","Zip","Home Phone","Work Phone","Cell Phone","TextMsg","Email","Type","Gender","Birthday","BirthCrt","Grade","Weight","Rating","Guardian1 ID","Guardian1 First","Guardian1 Last","Guardian1 Email","Guardian1 Home","Guardian1 Work","Guardian1 Cell","Guardian2 ID","Guardian2 First","Guardian2 Last","Guardian2 Email","Guardian2 Home","Guardian2 Work","Guardian2 Cell","Notes","Team Name","Position","Jersey #");
	fputcsv($pointer, $header, ",");
	
	//read teamsnap file and iterate
	if (($handle = fopen($fileIn, "r")) === FALSE) {echo "Cannot find file";exit;}
	
	$i = 0;
	while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
		$res = null;
		//get players only and only if they have US Lacrosse number
		if ($row[2] != "Player" || !$row[28]){
			$i++;
			continue;
		}
		//Record ID
		$res[0] = ""; 
		
		//US lacrosse number
		$res[1] = (int)$row[28];
		
		//Expiration Date
		$res[2] = null;
		
		//First Name
		$res[3] = $row[0];
		
		//MI
		$res[4] = null;
		
		//Last Name
		$res[5] = $row[1];
		
		//Address 1
		$res[6] = $row[3];
		
		//Address 2
		$res[7] = null;
		
		//City
		$city = strtolower($row[4]);
		$city = ucwords($city);
		$res[8] = $city;
		
		//State
		$res[9] = "CA";
		
		//Zip
		$zip = $row[6];
		if (strlen($zip[6])){$zip = substr($zip,0,5);}
		$res[10] = $zip;
		
		//Home Phone	
		$phone = explode(";",$row[11]);
		$res[11] = formatPhone($phone[0]);
		
		//Work Phone
		$res[12] = formatPhone($phone[2]);
			
		//Cell Phone	
		$res[13] = formatPhone($phone[1]);
		
		//TextMsg	
		$res[14] = null;
		
		//Email
		//$email = explode(";",$row[10]);
		$res[15] = $row[10];
			
		//Type	
		$res[16] = "Player";
		
		//Gender
		if ($row[12] == "Female"){
			$res[17] = "F";
		} else {
			$res[17] = "M";
		}
			
		//Birthday	
		$res[18] = $row[7];
		
		//BirthCrt	
		$res[19] = null;
		
		//Grade	
		$res[20] = substr($row[33],0,1);
		
		//Weight	
		$res[21] = null;
		
		//Rating	
		$res[22] = null;
		
		//Guardian1 ID	
		$res[23] = null;
		
		//Guardian1 First
		$name = explode(" ",trim($row[13]));	
		$res[24] = $name[0];
		
		//Guardian1 Last
		$res[25] = ($name[2]) ? $name[2] : "$name[1]";	
		
		//Guardian1 Email
		$res[26] = $row[15];
		
		//Guardian1 Home	
		$phone = explode(";",trim($row[14]));
		$res[27] = formatPhone($phone[0]);
		
		//Guardian1 Work	
		$res[28] = formatPhone($phone[2]);
		
		//Guardian1 Cell	
		$res[29] = formatPhone($phone[1]);
		
		//Guardian2 ID	
		$res[30] = null;
		
		//Guardian2 First	
		$name = explode(" ",$row[17]);	
		$res[31] = $res[24] = $name[0];
		
		//Guardian2 Last	
		$res[32] = ($name[2]) ? $name[2] : "$name[1]";	
		
		//Guardian2 Email	
		$res[33] = $row[19];
		
		//Guardian2 Home	
		$phone = explode(";",trim($row[18]));
		$res[34] = formatPhone($phone[0]);
		
		//Guardian2 Work	
		$res[35] = formatPhone($phone[2]);
		
		//Guardian2 Cell	
		$res[36] = formatPhone($phone[1]);
		
		//Notes	
		$res[37] = null;
		
		//Team Name
		switch ($row[22]) {
			case "Boys 12BV":
				$team = "W. Valley Red Hawks B12Bv";
				break;
			case "GU8 Black":
				$team = "W. Valley Red Hawks Black G8";
				break;
			case "GU8 Red":
				$team = "W. Valley Red Hawks Red G8";
				break;			
			case "GU10 BR Red":
				$team = "W. Valley Red Hawks Red G10R";
				break;		
			case "GU10 BV Black":
				$team = "W. Valley Red Hawks Black G10V";
				break;			
			case "Red Hawks GU12BR Red":
				$team = "W. Valley Red Hawks Red G12Br";
				break;	
			case "Red Hawks GU12BV Black":
				$team = "W. Valley Red Hawks Black G12Bv";
				break;	
			case "WV Red Hawks GU14B Red":
				$team = "W. Valley Red Hawks Red G14Br";
				break;	
			case "GU14A Black":
				$team = "W. Valley Red Hawks Black G14A";
				break;			
			case "Red Hawks Boys 8U":
				$team = "W. Valley Red Hawks B8";	
				break;		
			case "Red Hawks Boys 10BR":
				$team = "W. Valley Red Hawks B10Br";
				break;		
			case "Boys 10BV":
				$team = "W. Valley Red Hawks B10Bv";
				break;		
			case "Boys 12BV":
				$team = "W. Valley Red Hawks B12Bv";
				break;		
			case "Boys 12A":
				$team = "W. Valley Red Hawks B12A";
				break;
			case "Boys 14BR":
				$team = "W. Valley Red Hawks B14Br";
				break;
			case "Boys 14BV":
				$team = "W. Valley Red Hawks B14Bv";
				break;
			case "Red Hawks Boys 14A":
				$team = "W. Valley Red Hawks B14A";
				break;	
			default: 
				echo "Failed team lookup: $row[22] \n";
				$team = "";						
		}
		
		$res[38] = $team;
		
		//Position	
		$res[39] = null;
		
		//Jersey #
		$res[40] = $row[35];
		
		//write to file
		fputcsv($pointer, $res, ",");

		$i++;
	}
	
	//write coaching file
	if ($argv[2]){
		//read coaches file and iterate
		if (($handle = fopen($argv[2], "r")) === FALSE) {echo "Cannot find file ".$argv[2];exit;}
	
		$i = 0;
		while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
			$res = null;
		
			if($i != 0) {
			
				//Record ID
				$res[0] = ""; 
		
				//US lacrosse number
				$res[1] = (int)$row[4];
		
				//Expiration Date
				$res[2] = null;
		
				//First Name
				$res[3] = $row[3];
		
				//MI
				$res[4] = null;
		
				//Last Name
				$res[5] = $row[2];
		
				//Address 1
				$res[6] = $row[6];
		
				//Address 2
				$res[7] = $row[7];
		
				//City
				$city = strtolower($row[8]);
				$city = ucwords($city);
				$res[8] = $city;
		
				//State
				$res[9] = "CA";
		
				//Zip
				$zip = $row[10];
				if (strlen($zip[6])){$zip = substr($zip,0,5);}
				$res[10] = $zip;
		
				//Home Phone	
				$res[11] = formatPhone($row[11]);
		
				//Work Phone
				$res[12] = formatPhone($row[12]);
			
				//Cell Phone	
				$res[13] = formatPhone($row[13]);
		
				//TextMsg	
				$res[14] = null;
		
				//Email
				$res[15] = $row[1];
			
				//Type	
				$res[16] = $row[16];
		
				//Gender
				if ($row[14] == "Female"){
					$res[17] = "F";
				} else {
					$res[17] = "M";
				}
			
				//Birthday	
				$res[18] = $row[15];
		
				//BirthCrt	
				$res[19] = null;
		
				//Grade	
				$res[20] = null;
		
				//Weight	
				$res[21] = null;
		
				//Rating	
				$res[22] = null;
		
				//Guardian1 ID	
				$res[23] = null;
		
				//Guardian1 First
				$res[24] = null;
		
				//Guardian1 Last
				$res[25] = null;	
		
				//Guardian1 Email
				$res[26] = null;
		
				//Guardian1 Home	
				$res[27] = null;
		
				//Guardian1 Work	
				$res[28] = null;
		
				//Guardian1 Cell	
				$res[29] = null;
		
				//Guardian2 ID	
				$res[30] = null;
		
				//Guardian2 First	
				$res[31] = null;
		
				//Guardian2 Last	
				$res[32] = null;	
		
				//Guardian2 Email	
				$res[33] = null;
		
				//Guardian2 Home	
				$res[34] = null;
		
				//Guardian2 Work	
				$res[35] = null;
		
				//Guardian2 Cell	
				$res[36] = null;
		
				//Notes	
				$res[37] = null;
		
				//Team Name
				if ($row[18]){
					$res[38] = $row[18];
				} else {
					echo $res[3]." ".$res[5]." skipped; no team assigned \n";
					continue;
				}
		
				//Position	
				$res[39] = null;
		
				//Jersey #
				$res[40] = null;
		
				//write to file
				fputcsv($pointer, $res, ",");
			}
			
			$i++;
		}
		echo "\nWrote converted roster & coaching file to ".$out."\n\n";
	} else {
		echo "\nWrote converted roster to ".$out."\n\n";
	}
	
	
	function formatPhone($phone){
		//return preg_replace("/(\d{3})(\d{3})(\d{4})/", "$1-$2-$3", $phone);
		$phone = trim(preg_replace("/[^0-9]/", "", $phone));
		if (count($phone) > 10){ $phone = substr($phone, 1);}
		return preg_replace("/(\d{3})(\d{3})(\d{4})/", "$1-$2-$3", $phone);
	}
	
?>
