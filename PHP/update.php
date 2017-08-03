<?php

	include '../include/db.php';
	$dbl = $dbhost;
	$dbu = $dbuser;
	$dbp = $dbpas;
	Get_nmber();
function Get_nmber()
{
		global $dbl;
		global $dbu;
		global $dbp;
		$mysql = mysql_connect($dbl,$dbu,$dbp);
		if (!$mysql)
		{
			 die('出错信息1： ' . mysql_error());
		}
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db("qdm165423358_db",$mysql);
		$Mysql_counts = mysql_query("select count(*) from sheet1");
		$ls = mysql_fetch_row($Mysql_counts);
		
		mysql_close($mysql);
		$id = 1;
		gd:
		$Error_quit = 0;
		for(;$id <= 999;$id++)
		{
			sleep(10);
			$mysql = mysql_connect($dbl,$dbu,$dbp);
			if (!$mysql)
			{
				 die('出错信息2： ' . mysql_error());
			}
			mysql_query("SET NAMES 'utf8'");
			mysql_select_db("qdm165423358_db",$mysql);
			$ls_AREAID_query = mysql_query("select AREAID from sheet1 where ID = $id",$mysql);
			$ls_AREAID_value = mysql_fetch_row($ls_AREAID_query);
			mysql_close($mysql);
			$Back_value = Get_Weather($ls_AREAID_value[0]);
			if($Back_value != 0)
			{
					if(++$Error_quit == 3)//如果三次都未成功，放弃。
					{
						goto gd;
					}
					else
					{
						Get_Weather($Back_value);
					}
			}
		}

	exit("《《《《《《《《《《《《《《《《《《《《《《《《《《《 更 新 完成 》》》》》》》》》》》》》》》》》》》》》》》》》》》》》》》》");
		
}



	function Get_Weather($ls_AREAID_id)
	{
		
		set_time_limit(0);
		$private_key = 'b6b624_SmartWeatherAPI_6ccf17d';
		$appid='b608c509ee8caae8';
		$appid_six=substr($appid,0,6);
		$type='forecast_v';
		$date=date("YmdHi");
		$public_key="http://open.weather.com.cn/data/?areaid=".$ls_AREAID_id."&type=".$type."&date=".$date."&appid=".$appid;
		$key = base64_encode(hash_hmac('sha1',$public_key,$private_key,TRUE));
		$URL="http://open.weather.com.cn/data/?areaid=".$ls_AREAID_id."&type=".$type."&date=".$date."&appid=".$appid_six."&key=".urlencode($key);
		$string = file_get_contents($URL);
		$Weather_Array_data = json_decode($string, true);
		if($Weather_Array_data == NULL)
		{
			return $ls_AREAID_id; //如果出错,返回出错的地区代码
		}
		else
		{
			if(Update_db($Weather_Array_data) == 1)
			{
				return 0;
			}  
		}
	}
	
	

	function Update_db($Weather_Array_data)
	{
		global $dbl;
		global $dbu;
		global $dbp;
		
		/*当天白天气温，晚上气温，白天图标，晚上图标*/
		$Night_C_0 = $Weather_Array_data["f"]["f1"][0]["fd"];
		$Day_C_0 = $Weather_Array_data["f"]["f1"][0]["fc"];
		$Night_Ico_0 = $Weather_Array_data["f"]["f1"][0]["fb"];
		$Day_Ico_0 = $Weather_Array_data["f"]["f1"][0]["fa"];
		/*END*/
		
		/*第二天白天气温，晚上气温，白天图标，晚上图标*/
		$Night_C_1 = $Weather_Array_data["f"]["f1"][1]["fd"];
		$Day_C_1 = $Weather_Array_data["f"]["f1"][1]["fc"];
		$Night_Ico_1 = $Weather_Array_data["f"]["f1"][1]["fb"];
		$Day_Ico_1 = $Weather_Array_data["f"]["f1"][1]["fa"];
		/*END*/
		
		/*第三天白天气温，晚上气温，白天图标，晚上图标*/
		$Night_C_2 = $Weather_Array_data["f"]["f1"][2]["fd"];
		$Day_C_2 = $Weather_Array_data["f"]["f1"][2]["fc"];
		$Night_Ico_2 = $Weather_Array_data["f"]["f1"][2]["fb"];
		$Day_Ico_2 = $Weather_Array_data["f"]["f1"][2]["fa"];
		/*END*/						
		$time_h = date("H",time());
		
		$mysql = mysql_connect($dbl,$dbu,$dbp);
		if (!$mysql)
		{
			 die('出错信息:3 ' . mysql_error());
		}
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db("qdm165423358_db",$mysql);
				if($time_h >= 18 || $time_h <= 8)
				{
					//////////////////////////////////////////////更新晚上数据//////////////////////////////////////
					/*当天晚上天*/
					 $is_0 = "update sheet1 set Night_C_0 = ".$Night_C_0." Where "."AREAID = ".$Weather_Array_data["c"]["c1"];
					 $is_ico_0 = "update sheet1 set Night_Ico_0 = ".$Night_Ico_0." Where "."AREAID = ".$Weather_Array_data["c"]["c1"];//图标数据为0开头时数据库会舍去0，调用时拼接加0,判断图标数字小于10就拼接0，大于等于10就不用拼接
					 /*第二天晚上*/
					 $is_1 = "update sheet1 set Night_C_1 = ".$Night_C_1." Where "."AREAID = ".$Weather_Array_data["c"]["c1"];
					 $is_ico_1 = "update sheet1 set Night_Ico_1 = ".$Night_Ico_1." Where "."AREAID = ".$Weather_Array_data["c"]["c1"];
					 
					 /*第三天晚上*/	
					 $is_2 = "update sheet1 set Night_C_2 = ".$Night_C_2." Where "."AREAID = ".$Weather_Array_data["c"]["c1"];
					 $is_ico_2 = "update sheet1 set Night_Ico_2 = ".$Night_Ico_2." Where "."AREAID = ".$Weather_Array_data["c"]["c1"]; 
				}
				else
				{
					//////////////////////////////////////////////更新白天数据//////////////////////////////////////
					/*当天白天*/
					$is_0 = "update sheet1 set Day_C_0 = ".$Day_C_0." Where AREAID = ".$Weather_Array_data["c"]["c1"];
					$is_ico_0 = "update sheet1 set Day_Ico_0 = ".$Day_Ico_0." Where "."AREAID = ".$Weather_Array_data["c"]["c1"];
					/*第二天白天*/
					$is_1 = "update sheet1 set Day_C_1 = ".$Day_C_1." Where AREAID = ".$Weather_Array_data["c"]["c1"];
					$is_ico_1 = "update sheet1 set Day_Ico_1 = ".$Day_Ico_1." Where "."AREAID = ".$Weather_Array_data["c"]["c1"];
					/*第三天白天*/
					$is_2 = "update sheet1 set Day_C_2 = ".$Day_C_2." Where AREAID = ".$Weather_Array_data["c"]["c1"];
					$is_ico_2 = "update sheet1 set Day_Ico_2 = ".$Day_Ico_2." Where "."AREAID = ".$Weather_Array_data["c"]["c1"];
				}
				mysql_query($is_ico_0,$mysql);
				mysql_query($is_0,$mysql);
				mysql_query($is_ico_1,$mysql);
				mysql_query($is_1,$mysql);
				mysql_query($is_ico_2,$mysql);
				mysql_query($is_2,$mysql);
				mysql_close($mysql);
				return 1;	
	}
?>