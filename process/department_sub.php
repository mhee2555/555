<?php
session_start();
require '../connect/connect.php';

function ShowItem($conn, $DATA)
{
  $count = 0;
  $Keyword = $DATA['Keyword'];
  $HptCode = $DATA['HptCode']==""?1:$DATA['HptCode'];
  $DepCode = $DATA['DepCode']==""?1:$DATA['DepCode'];

  $Sql = "SELECT 	department_sub.HptCode,
                  department_sub.DepSubCode,
                  department_sub.DepCode,
                  department_sub.DepSubName,
                  department.DepName,
                  department_sub.IsDefault
          FROM department_sub
          INNER JOIN department ON department.DepCode = department_sub.DepCode
          INNER JOIN hospital ON hospital.HptCode = department_sub.HptCode
          WHERE department_sub.IsStatus =  0
          AND hospital.HptCode = $HptCode AND department.DepCode =  $DepCode
          AND ( department_sub.DepSubCode LIKE '%$Keyword%' OR department_sub.DepSubName LIKE '%$Keyword%')";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['DepSubCode'] = $Result['DepSubCode'];
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepSubName'] = $Result['DepSubName'];
    $return[$count]['DepName'] = $Result['DepName'];
	  $return[$count]['IsDefault'] = $Result['IsDefault'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    $return['sql'] = $Sql;
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}
function getdetail($conn, $DATA)
{
  $count = 0;
  $DepSubCode = $DATA['DepSubCode'];
  //---------------HERE------------------//
  $Sql = "SELECT 	department_sub.HptCode,
                  department_sub.DepSubCode,
                  department_sub.DepCode,
                  department_sub.DepSubName,
                  department.DepName
        FROM department_sub
        INNER JOIN department ON department.DepCode = department_sub.DepCode
        INNER JOIN hospital ON hospital.HptCode = department_sub.HptCode
        WHERE department_sub.IsStatus = 0 
        AND department_sub.DepSubCode = $DepSubCode LIMIT 1";
        // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['HptCode'] = $Result['HptCode'];
    $return['DepSubCode'] = $Result['DepSubCode'];
    $return['DepCode'] = $Result['DepCode'];
    $return['DepSubName'] = $Result['DepSubName'];
    $return['DepName'] = $Result['DepName'];
    $HptCode              = $Result['HptCode'];
    $count++;
  }

  $cnt = 0;
  $Sql = "SELECT hospital.HptCode,hospital.HptName
          FROM hospital
          WHERE hospital.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['Hpt'.$cnt]['HptCode']  = $Result['HptCode'];
    $return['Hpt'.$cnt]['HptName']  = $Result['HptName'];
    $cnt++;
  }
  $return['HptCnt'] = $cnt;

  $cnt=0;
  $Sql = "SELECT department.DepCode, department.DepName
          FROM department
          WHERE department.IsStatus = 0
          AND department.HptCode = $HptCode";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['Dep'.$cnt]['DepCode']  = $Result['DepCode'];
    $return['Dep'.$cnt]['DepName']  = $Result['DepName'];
    $cnt++;
  }
  $return['DepCnt'] = $cnt;

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function OnLoadPage($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Sql = "SELECT hospital.HptCode, hospital.HptName FROM hospital WHERE hospital.IsStatus = 0";
  // echo $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
    $boolean = true;
  }
  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getDepartment($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = $Hotp
		  AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function getDepartment2($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = $Hotp
		  AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "getDepartment2";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function Department2($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "Department2";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function AddItem($conn, $DATA)
{
  $count = 0;
  $HptCode = $DATA['HptCode'];
  $DepCode = $DATA['DepCode'];
  $depSubName = $DATA['depSubName'];

  $Sql = "INSERT INTO department_sub( HptCode, DepCode, depSubName, IsStatus )
          VALUES ($HptCode, $DepCode, '$depSubName', 0)";
  // var_dump($Sql); die;
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "AddItem";
    $return['msg'] = "addsuccess";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function EditItem($conn, $DATA)
{
  // var_dump($DATA); die;
  $count = 0;
  $HptCode = $DATA['HptCode'];
  $DepCode = $DATA['DepCode'];
  $depSubName = $DATA['depSubName'];
  $depSubCode = $DATA['depSubCode'];

  if($depSubCode!=""){
    $Sql = "UPDATE department_sub SET
            HptCode =  $HptCode,
            DepCode = $DepCode,
            depSubName = '$depSubName'
            WHERE depSubCode = $depSubCode;
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "EditItem";
      $return['msg'] = "editsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      // $return['msg'] = "editfailed :  $xCenter";
      $return['sql'] = $Sql;
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function CancelItem($conn, $DATA)
{
  $count = 0;
  if($DATA["DepSubCode"]!=""){
    $Sql = "UPDATE department_sub SET
            IsStatus = 1
            WHERE DepSubCode = ".$DATA['DepSubCode']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "CancelItem";
      $return['msg'] = "cancelsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "cancelfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "cancelfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getSection') {
        getSection($conn, $DATA);
      }else if ($DATA['STATUS'] == 'AddItem') {
        AddItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }else if ($DATA['STATUS'] == 'OnLoadPage') {
        OnLoadPage($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getDepartment') {
        getDepartment($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getDepartment2') {
        getDepartment2($conn,$DATA);
      }else if ($DATA['STATUS'] == 'Department2') {
        Department2($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
