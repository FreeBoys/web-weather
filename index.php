<?php
include 'include/db.php';
$_SERVER["REMOTE_ADDR"];
$dbl = $dbhost;
$dbp = $dbpas;
$dbu = $dbuser;
$json_value = Getip($_SERVER["REMOTE_ADDR"]);
$caller_ip = $json_value["retData"]["ip"];//访问者ip
$caller_country = $json_value["retData"]["country"];//国家
$caller_province = $json_value["retData"]["province"];//省
$caller_city = $json_value["retData"]["city"];//市
$caller_district = $json_value["retData"]["district"];//县/区
$caller_carrier = $json_value["retData"]["carrier"];//运营商（ISP）
Weather_Data($caller_province,$caller_city,$caller_district);
show($weather_all_0,$weather_all_1,$weather_all_2,$caller_province,$caller_city,$caller_district);


function show($weather_all_0,$weather_all_1,$weather_all_2,$caller_province,$caller_city,$caller_district)
{
	$time_h = date("H",time());
	if($weather_all_0[2]<10)
		$weather_Ico_0_day = "0".$weather_all_0[2];
	if($weather_all_0[3]<10)
		$weather_Ico_0_night = "0".$weather_all_0[3];
		
	if($weather_all_1[2]<10)
		$weather_Ico_1_day = "0".$weather_all_0[2];
	if($weather_all_1[3]<10)
		$weather_Ico_1_night = "0".$weather_all_0[3];
		
	if($weather_all_2[2]<10)
		$weather_Ico_2_day = "0".$weather_all_0[2];
	if($weather_all_2[3]<10)
		$weather_Ico_2_night = "0".$weather_all_0[3];
	echo "<html><head>";
	echo "<meta content='text/html' charset='utf-8'>";
	echo "<title>天气 by:WD</title>";
	echo "<style>";
	if($time_h >= 18 || $time_h <= 8)
	{
		if($weather_all_0[1]<22)
		{
			$bg = "15";
		}
		else
		{
			$bg = "11";
		}
	}
	else
	{
		if($weather_all_0[0]<22)
		{
			$bg = "15";
		}
		else
		{
			$bg = "11";
		}
	}
	$bg1 = "tb/bg/".$bg.".jpg";
	echo "#main1{width:500px;height:720px;background-image:url(".$bg1.");margin:200px auto;}";
	echo "#top{width:500px;height:100px;/*background-color:#278818;*/ font-size:20px;text-align:center;}";
	echo "#cen{width:500px;height:120px;/*background-color:#17898B;*/ font-size:50px; text-align:center; line-height:150px;}";
	echo "#bom{width:500px;height:100px;/*background-color:#C0272A;*/}";
	echo "#one{width:166px;height:100px;/*background-color:#6F3637;*/ float:left;text-align:center;}";
	echo "#two{width:166px;height:100px;/*background-color:#391010;*/ float:left;text-align:center; font-size:30px; line-height:100px;}";
	echo "#therr{width:166px;height:100px;/*background-color:#000000;*/ float:left;text-align:center;}";
	echo "</style>";
	echo "</head>";
	echo "<body text='#FFFFFF' bgcolor=#51B0A3>";
	echo "<div id='main1'>";
	echo "<div id='top'>";
	echo "<br /><br />";
	echo " &nbsp;<img src='tb/bg/dd.png'/>".$caller_province."&nbsp;".$caller_city."&nbsp;".$caller_district."</div>";
	/*位于中间的天气图标*/
	if($time_h >= 18 || $time_h <= 8)
		{
			echo "<div id='cen'>"."<p></p><img src='tb/night/".$weather_Ico_0_night.".png' />"."</div>";
		}
	else
		{
			echo "<div id='cen'>"."<p></p><img src='tb/day/".$weather_Ico_0_day.".png' />"."</div>";
		}
	/*END*/
	
	/*温度数字*/
	if($time_h >= 18 || $time_h <= 8)
		{
			echo "<div id='cen'>"."&nbsp;".$weather_all_0[1]."&deg;"."</div><p></p>";
		}
	else
		{
			echo "<div id='cen'>"."&nbsp;".$weather_all_0[0]."&deg;"."</div><p></p>";
		}
	
	/*END*/
	echo "<div id='bom'>";
	/*底部天气第一天*/
	if($time_h >= 18 || $time_h <= 8)
		{
			echo "<div id='one'>"."<img src='tb/night/".$weather_Ico_0_night.".png' />"."</div>";
		}
	else
		{
			echo "<div id='one'>"."<img src='tb/day/".$weather_Ico_0_day.".png' />"."</div>";
		}
	if($time_h >= 18 || $time_h <= 8)
		{
			echo "<div id='two'>"."<img src='tb/night/".$weather_Ico_1_night.".png' />"."</div>";
		}
	else
		{
			echo "<div id='two'>"."<img src='tb/day/".$weather_Ico_1_day.".png' />"."</div>";
		}
	if($time_h >= 18 || $time_h <= 8)
		{
			echo "<div id='therr'>"."<img src='tb/night/".$weather_Ico_2_night.".png' />"."</div>";
		}
	else
		{
			echo "<div id='therr'>"."<img src='tb/day/".$weather_Ico_2_day.".png' />"."</div>";
		}
	echo "</div>";
	/*END*/
	/*底部温度数字*/
	echo "<div id='two'>".$weather_all_0[1]."&deg;/".$weather_all_0[0]."&deg;"."<br />今天"."</div>";
	echo "<div id='two'>".$weather_all_1[1]."&deg;/".$weather_all_1[0]."&deg;"."<br />明天"."</div>";
	echo "<div id='two'>".$weather_all_2[1]."&deg;/".$weather_all_2[0]."&deg;"."<br />后天"."</div>";
	echo "</div>";
	/*END*/
	echo "</div>";
	echo "</body>";
	echo "</html>";
}

function Getip($get_ip) 
{
	
	$ch = curl_init();
    $url = "http://apis.baidu.com/apistore/iplookupservice/iplookup?ip=$get_ip";
    $header = array('apikey:144b2f67e508ad09eeb7c1ae1a49f11b');
    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);
    //var_dump(json_decode($res));
	return object_array($res);
}

/*json格式转数组*/
function object_array($array)
{
   if(is_object($array))
   {
    $array = (array)$array;
   }
   if(is_array($array))
   {
    foreach($array as $key=>$value)
    {
     $array[$key] = object_array($value);
    }
   }
   return json_decode($array,true);
}


/*查询数据库中天气*/
	function Weather_Data($Province,$City,$District)
	{
		global $dbl;
		global $dbu;
		global $dbp;
		global $weather_all_0;
		global $weather_all_1;
		global $weather_all_2;
		$Province_s = "'".$Province."'";
		$City_s = "'".$City."'";
		$District_s = "'".$District."'";
		$Ims = mysql_connect($dbl,$dbu,$dbp);
		if (!$Ims)
		  {
		  	die('Could not connect: ' . mysql_error());
		  }
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db("qdm165423358_db",$Ims);
		$t_or_f = mysql_fetch_row(mysql_query("SELECT ID FROM sheet1 WHERE NAMECN = $District_s"));
		if($t_or_f != NULL)
		{
			$weather_all_0 = mysql_fetch_row(mysql_query("select Day_C_0,Night_C_0,Day_Ico_0,Night_Ico_0 from sheet1 where PROVCN = $Province_s AND DISTRICTCN = $City_s and NAMECN = $District_s"));
			$weather_all_1 = mysql_fetch_row(mysql_query("select Day_C_1,Night_C_1,Day_Ico_1,Night_Ico_1 from sheet1 where PROVCN = $Province_s AND DISTRICTCN = $City_s and NAMECN = $District_s"));
			$weather_all_2 = mysql_fetch_row(mysql_query("select Day_C_2,Night_C_2,Day_Ico_2,Night_Ico_2 from sheet1 where PROVCN = $Province_s AND DISTRICTCN = $City_s and NAMECN = $District_s"));
		}
		else
		{
			$weather_all_0 = mysql_fetch_row(mysql_query("select Day_C_0,Night_C_0,Day_Ico_0,Night_Ico_0 from sheet1 where PROVCN = $Province_s and DISTRICTCN = $City_s"));
			$weather_all_1 = mysql_fetch_row(mysql_query("select Day_C_1,Night_C_1,Day_Ico_1,Night_Ico_1 from sheet1 where PROVCN = $Province_s and DISTRICTCN = $City_s"));
			$weather_all_2 = mysql_fetch_row(mysql_query("select Day_C_2,Night_C_2,Day_Ico_2,Night_Ico_2 from sheet1 where PROVCN = $Province_s and DISTRICTCN = $City_s"));
		}
		mysql_close($Ims); 
		
	}
?>