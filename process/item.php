<?php
session_start();
require '../connect/connect.php';

function ShowItem($conn, $DATA)
{
  $count = 0;
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item_category.CategoryName,
            item_unit.UnitName,
            item.Price
          FROM item
          INNER JOIN item_category ON item_category.CategoryCode = item.CategoryCode
          INNER JOIN item_unit ON item_unit.UnitCode = item.UnitCode
          WHERE item.CategoryCode = $Catagory AND (item.ItemCode LIKE '%$Keyword%' OR
            item.ItemName LIKE '%$Keyword%' OR
            item_unit.UnitName LIKE '%$Keyword%')
          ORDER BY item.ItemCode ASC";
          $return['sql'] =  $Sql;
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['Price'] = $Result['Price'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['form'] = "ShowItem";
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}
function OnloadPage($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item_category.CategoryName,
            item_unit.UnitName,
            item.Price
          FROM item
          INNER JOIN item_category ON item_category.CategoryCode = item.CategoryCode
          INNER JOIN item_unit ON item_unit.UnitCode = item.UnitCode
          ORDER BY item.ItemCode ASC";
          $return['sql'] =  $Sql;
   //var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['Price'] = $Result['Price'];
    $count++;
  }
    $Sql = "SELECT MAX(ItemCode) AS MaxCode FROM item";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $MaxCode = $Result['MaxCode'];
    }
    $sub = explode("i",$MaxCode);
    $newCode = $sub[1]+1;
    if($newCode>99){
      $newCode = 'i'.$newCode;
    }else if($newCode>9){
      $newCode = 'i0'.$newCode;
    }else if($newCode<10){
      $newCode = 'i00'.$newCode;
    }
    $return['newCode'] = $newCode;

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "OnloadPage";
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

function getUnit($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_unit.UnitCode,
          item_unit.UnitName,
          item_unit.IsStatus
          FROM
          item_unit
          WHERE item_unit.IsStatus = 0
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getUnit";
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

function getCatagory($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_category.CategoryCode,
          item_category.CategoryName,
          item_category.IsStatus
          FROM
          item_category
          WHERE item_category.IsStatus = 0
          ORDER BY item_category.CategoryName ASC";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['CategoryCode'] = $Result['CategoryCode'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getCatagory";
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

function getdetail($conn, $DATA)
{
  $count = 0;
  $ItemCode = $DATA['ItemCode'];
  $Sql = "SELECT
              item.ItemCode,
              item.ItemName,
              item_category.CategoryName,
              item_unit.UnitName,
              item_unit.UnitCode,
              item.CategoryCode,
              item.Price
            FROM item
            INNER JOIN item_category ON item_category.CategoryCode = item.CategoryCode
            INNER JOIN item_unit ON item_unit.UnitCode = item.UnitCode
            WHERE item.ItemCode = '$ItemCode' LIMIT 1";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['CategoryCode'] = $Result['CategoryCode'];
    $return[$count]['Price'] = $Result['Price'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $count++;
  }
  $return['sql'] = $Sql;
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

function getSection($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          department.DepCode,
          department.UnitCode,
          department.DepName,
          department.IsStatus
          FROM
          department";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode']       = $Result['DepCode'];
    $return[$count]['DepName']  = $Result['DepName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function AddItem($conn, $DATA)
{
  $ItemCode = $DATA['ItemCode'];
  $Catagory = $DATA['Catagory'];
  $ItemName = $DATA['ItemName'];
  $FacPrice = $DATA['FacPrice'];
  $UnitCode = $DATA['UnitCode'];
  $chk_update = $DATA['chk_update'];

  if($chk_update!=1){
    $count = 0;
    $Sql = "INSERT INTO item(ItemCode, CategoryCode, ItemName, Price, UnitCode)
            VALUES
            (
              '$ItemCode',
              $Catagory,
              '$ItemName',
              $FacPrice,
              $UnitCode
            )";
    $return['sql'] = $Sql;
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
  }else{
    $Sql = "UPDATE item SET
            CategoryCode = $Catagory,
            ItemName =  '$ItemName',
            UnitCode = $UnitCode,
            Price = $FacPrice
            WHERE ItemCode = '$ItemCode'";
            $return['sql'] = $Sql;

    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "AddItem";
      $return['msg'] = "editsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "editfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }
}

function AddUnit($conn, $DATA)
{
  $count = 0;
  $Sql = "INSERT INTO item_multiple_unit(
          MpCode,
          UnitCode,
          Multiply,
          ItemCode
        )
        VALUES
        (
          ".$DATA['MpCode'].",
          ".$DATA['UnitCode'].",
          ".$DATA['Multiply'].",
          '".$DATA['ItemCode']."'
        )
  ";
  // var_dump($Sql); die;
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "AddUnit";
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
  $count = 0;
  if($DATA["UnitCode"]!=""){
    $Sql = "UPDATE item_Unit SET
            UnitCode = '".$DATA['UnitCode']."',
            UnitName = '".$DATA['UnitName']."'
            WHERE UnitCode = ".$DATA['UnitCode']."
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
      $return['msg'] = "editfailed";
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
  if($DATA["ItemCode"]!=""){
    $Sql = "DELETE FROM item
            WHERE ItemCode = '".$DATA['ItemCode']."'
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

function DeleteUnit($conn, $DATA)
{
  $count = 0;
  if($DATA["RowID"]!=""){
    $Sql = "DELETE FROM item_multiple_unit
            WHERE RowID = ".$DATA['RowID']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "CancelUnit";
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
      }else if ($DATA['STATUS'] == 'getCatagory') {
        getCatagory($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getUnit') {
        getUnit($conn, $DATA);
      }else if ($DATA['STATUS'] == 'AddItem') {
        AddItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'AddUnit') {
        AddUnit($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'DeleteUnit') {
        DeleteUnit($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }else if ($DATA['STATUS'] == 'OnloadPage') {
        OnloadPage($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
