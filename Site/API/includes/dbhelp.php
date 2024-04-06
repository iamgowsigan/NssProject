<?php
	
	
	function Insertdata($table, array $dataArray ){
		include('includes/config.php');
		
		$getColumnsKeys = array_keys($dataArray);
		$implodeColumnKeys = implode(",",$getColumnsKeys);
		
		$getValues = array_values($dataArray);
		$implodeValues = "'".implode("','",$getValues)."'";
		$last_id="0";
		
		$qry = "insert into $table (".$implodeColumnKeys.") values (".$implodeValues.")";
		$query = mysqli_query($con, $qry );
		if ($query) {
			$last_id = mysqli_insert_id($con);
		}
		$uname = $_SESSION['login'];
		$queryy = mysqli_query($con, "insert into adminlog(name,action) values('$uname','$table Added - $last_id')");
		return $last_id;
	}
	
	function Updatedata($table, array $dataArray, $where ){
		include('includes/config.php');
		$last_id="0";
		
		$cols = array();
		foreach($dataArray as $key=>$val) {
			$cols[] = "$key = '$val'";
		}
		$sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";
		
		
		$query = mysqli_query($con, $sql );
		if ($query) {
			$last_id='1';
			} else {
            $last_id="0";
		}
		
		$uname = $_SESSION['login'];
		$queryy = mysqli_query($con, "insert into adminlog(name,action) values('$uname','$table Updated')");
		return $last_id;
	}
	
	
	function Uploadimage($name,$value){
		include('includes/config.php');
		$file_name = $_FILES[$value]['name'];
		if($file_name!=''){
		$file_tmp = $_FILES[$value]['tmp_name'];
		$temp = explode(".", $_FILES[$value]["name"]);
		$file_name = $name . round(microtime(true)) . '.' . end($temp);
		
		if($production=="1"){
			$file_type=$_FILES[$value]['type'];
			$result = $s3->putObject([
			'Bucket' => $bucketName,
			'Key'    => $file_name,
			'ContentType'    => $file_type,
			'SourceFile' => $file_tmp			
			]);
			
		}
		else{		
			move_uploaded_file($file_tmp, $imgloc . $file_name);
		}
		
		$checkimg=explode(".",$file_name);
		
		if($_FILES[$value]['name']!=''){
			return $file_name;
			}else{
			return '';
		}
	}else{
		return '';
	}
	}
	
	function Selectdata($sql){
		
		include('includes/config.php');
		
		$arrayList=array();
		
		$result1 = $con->query($sql);	
		while($row1 = $result1->fetch_assoc()) 
		{	
			array_push($arrayList,$row1);
		}
		return $arrayList;
	}	
	
	
	function Deletedata($sql){
		
		include('includes/config.php');
		$last_id="0";
		$query = mysqli_query($con, $sql );
		
		if ($query) {
			$last_id= '1';
			} else {
            $last_id= "0";
		}
		
		$uname = $_SESSION['login'];
		$queryy = mysqli_query($con, "insert into adminlog(name,action) values('$uname','$table Updated')");
		return $sql;
		
	}
	
	
	function Countdata($sql){
		
		include('includes/config.php');
		
		$query = mysqli_query($con, $sql );
		$cnt = 0;
		$cnt = mysqli_num_rows($query);
		
		return $cnt;
		
		
	}
	
	function AdminLog($name='', $action='' ){
		include('includes/config.php');
		
		$last_id="0";
		
		$qry = "insert into adminlog (name,action) values ('$name','$action')";
		$query = mysqli_query($con, $qry );
		if ($query) {
			$last_id = mysqli_insert_id($con);
		}

		return $last_id;
	}
	
	
	function ArrayToName($id,$list, $key,$value){
		
		$result='';
		for($x=0;$x<sizeof($list);$x++){
			if($list[$x][$key]==$id){
				$result=$list[$x][$value];
				
				}
			
			}

		return $result;
		
		
	}
	
	/*
		
		Image Upload Function
		----------------------
		
		Uploadimage('aaa','image');
		
		
		Insert Function
		---------------
		
		$field['name'] = addslashes($_POST['title']);
		$field['age'] = addslashes($_POST['titlearab']);
		$field['image'] = addslashes($_POST['screen']);
		$res=Insertdata("testt",$field);
		if($res!=0){
		$msg = "Success ";
		}else{
		$error = "Deleted ";
		} 
		
		
		Update Function
		---------------
		
		$field['name'] = addslashes($_POST['title']);
		$field['age'] = addslashes($_POST['titlearab']);
		$field['image'] = addslashes($_POST['screen']);
		
		$res=Updatedata("testt",$field,"id=7");
		if($res!=0){
		$msg = "Success ";
		}else{
		$error = "Deleted ";
		} 
		
		
		Select Data
		-------------
		
		$users=Selectdata("SELECT * from testt");
		
		
		Delete Data
		-----------
		Deletedata("delete from Safety  where s_id='$id'");
		
		
		Count Row
		---------
		
		Countdata("delete from Safety  where s_id='$id'");
		
		
		
		
	*/
	
	
	
?>
