<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');

function OnLoadPage($conn,$DATA){
  $count = 0;
  $boolean = false;
  $Sql = "SELECT hospital.HptCode,hospital.HptName FROM hospital WHERE hospital.IsStatus = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
    $boolean = true;
  }
  $boolean = true;
  if($boolean){
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getDepartment($conn,$DATA){
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName,department.IsDefault
  FROM department
  WHERE department.HptCode = $Hotp
  AND department.IsStatus = 0
  ORDER BY department.DepCode ASC";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['IsDefault'] = $Result['IsDefault'];
    $count++;
    $boolean = true;
  }

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function ShowDocument($conn,$DATA){
  $boolean = false;
  $count = 0;
  $dept = $DATA["dept"];
  $hos = $DATA["hos"];
  $search = $DATA["search"];
  $selecta = $DATA["selecta"];

  $Sql = "SELECT item_stock_detail.RowID,
      item_stock_detail.ItemCode,
      item_stock_detail.DepCode,
      item_stock_detail.Qty,
      item.ItemName,
      item_category.CategoryName,
      department.DepName,
    SUM(item_stock_detail.Qty) as total
    FROM item_stock_detail
    INNER JOIN item ON item.ItemCode = item_stock_detail.ItemCode
    INNER JOIN item_category on item_category.CategoryCode = item.CategoryCode
    INNER JOIN department ON department.DepCode = item_stock_detail.DepCode
    INNER JOIN hospital ON hospital.HptCode = department.HptCode
    WHERE hospital.HptCode = $hos AND department.DepCode = $dept AND  item.ItemName LIKE '%$search%'
    GROUP BY ItemCode ORDER BY ItemCode";
  // $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] 	    = $Result['ItemCode'];
    $return[$count]['ItemName'] 	    = $Result['ItemName'];
    $return[$count]['CategoryName'] 	= $Result['CategoryName'];
    $return[$count]['DepCode'] 	      = $Result['DepCode'];
    $return[$count]['DepName'] 	      = $Result['DepName'];
    $return[$count]['Qty'] 	          = $Result['Qty'];
    $return[$count]['RowID']          = $Result['Id'];
    $return[$count]['DepSubCode']     = $Result['DepSubCode'];
    $return[$count]['total']          = $Result['total'];
    $ItemCode                         = $Result['ItemCode'];

    $count2 = 0;
    $sSql = "SELECT department_sub.DepSubCode,
                    department_sub.DepSubName
              FROM department_sub
              WHERE department_sub.HptCode = $hos
              AND department_sub.DepCode = $dept
              AND department_sub.IsStatus = 0";
              $return['sql'] = $sSql;

    $xxmeQuery = mysqli_query($conn, $sSql);
    while ($zResult = mysqli_fetch_assoc($xxmeQuery)) {
      $m1 = "DepSubCode_" . $ItemCode . "_" . $count;
      $m2 = "DepSubName_" . $ItemCode . "_" . $count;
      $m3 = "Cnt_" . $ItemCode;

      $return[$m1][$count2] = $zResult['DepSubCode'];
      $return[$m2][$count2] = $zResult['DepSubName'];
      $DepSubCode= $zResult['DepSubCode'];

      #---------------------------------Qty--------------------------------------
      $QtySql = "SELECT stock_in_detail.DepSubCode,
            stock_in_detail.ItemCode,
          SUM(Qty) AS total
          FROM stock_in_detail 
          INNER JOIN stock_in ON stock_in.DocNo = stock_in_detail.DocNo
          WHERE stock_in_detail.DepSubCode = $DepSubCode 
          AND stock_in_detail.ItemCode = '$ItemCode' AND stock_in.IsStatus = 1";
      $QtyQuery = mysqli_query($conn, $QtySql);
      while ($QtyResult = mysqli_fetch_assoc($QtyQuery)) {
        $Qty1 = "Qty_" . $ItemCode . "_" . $count;
        $return[$Qty1][$count2] = $QtyResult['total']==null?0:$QtyResult['total'];
        $return['sql2'] = $QtySql;
      }
      #---------------------------------Qty--------------------------------------
      $count2++;
    }
    $return[$m3][$count] = $count2;
    $count++;
    $boolean = true;
  }
  
  $return['Row'] = $count;

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return[$count]['DocNo'] = "";
    $return[$count]['DocDate'] = "";
    $return[$count]['Qty'] = "";
    $return[$count]['Elc'] = "";
    $return['status'] = "failed";
    $return['form'] = "ShowDocument";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function selectTotalQty($conn,$DATA)
{
  $ItemCode = $DATA['ItemCode'];
  $DepSubCode = $DATA['DepSubCode'];
  $RowId = $DATA['RowId'];
  $count=0;
  $Sql = "SELECT stock_in_detail.DepSubCode,
            stock_in_detail.ItemCode,
                  SUM(Qty) AS total
                FROM stock_in_detail 
          INNER JOIN stock_in ON stock_in.DocNo = stock_in_detail.DocNo
          WHERE stock_in_detail.DepSubCode = $DepSubCode AND stock_in_detail.ItemCode = '$ItemCode' AND stock_in.IsStatus = 1";
    $return['sql'] = $Sql;
          
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['total'] = $Result['total']==null?0: $Result['total'];
    $count++;
    $boolean = true;
  }
  $return['RowId'] = $RowId;

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "selectTotalQty";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['form'] = "selectTotalQty";
    $return['msg'] = "nodetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
//==========================================================
//
//==========================================================
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

  if($DATA['STATUS']=='OnLoadPage'){
    OnLoadPage($conn,$DATA);
  }elseif ($DATA['STATUS']=='getDepartment') {
    getDepartment($conn, $DATA);
  }elseif($DATA['STATUS']=='ShowDocument'){
    ShowDocument($conn,$DATA);
  }elseif($DATA['STATUS']=='selectTotalQty'){
    selectTotalQty($conn,$DATA);
  }

}else{
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
?>
