<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');

function OnLoadPage($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Sql = "SELECT hospital.HptCode,hospital.HptName FROM hospital WHERE hospital.IsStatus = 0";
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

/**
 * @param $conn
 * @param $DATA
 */
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
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function CreateDocument($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $hotpCode = $DATA["hotpCode"];
  $deptCode = $DATA["deptCode"];
  $userid   = $DATA["userid"];

  //	 $Sql = "INSERT INTO log ( log ) VALUES ('userid : $userid')";
  //     mysqli_query($conn,$Sql);

  $Sql = "SELECT CONCAT('SI',lpad($hotpCode, 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
  CURRENT_TIME() AS RecNow
  FROM stock_out
  INNER JOIN department on stock_out.DepCode = department.DepCode
  WHERE DocNo Like CONCAT('SI',lpad($hotpCode, 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
  AND department.HptCode = $hotpCode
  ORDER BY DocNo DESC LIMIT 1";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DocNo = $Result['DocNo'];
    $return[0]['DocNo']   = $Result['DocNo'];
    $return[0]['DocDate'] = $Result['DocDate'];
    $return[0]['RecNow']  = $Result['RecNow'];
    $count = 1;
    $Sql = "INSERT INTO log ( log ) VALUES ('" . $Result['DocDate'] . " : " . $Result['DocNo'] . " :: $hotpCode :: $deptCode')";
    mysqli_query($conn, $Sql);
  }

  if ($count == 1) {
    $Sql = "INSERT INTO stock_out
      ( DocNo,DocDate,DepCode,RefDocNo,
		    TaxNo,TaxDate,DiscountPercent,DiscountBath,
		    Total,IsCancel,Detail,
        stock_out.Modify_Code,stock_out.Modify_Date ) VALUES ( '$DocNo', DATE(NOW()), $deptCode,'', 0, NOW(), 0, 0, 0, 0,'', $userid,NOW() )";
        $return['sql'] = $Sql;
    mysqli_query($conn,$Sql);

  //   $Sql = "INSERT INTO daily_request
  // (DocNo,DocDate,DepCode,RefDocNo,Detail,Modify_Code,Modify_Date)
  // VALUES
  // ('$DocNo',DATE(NOW()),$deptCode,'','stock_out',$userid,DATE(NOW()))";
  //   mysqli_query($conn, $Sql);

    $Sql = "SELECT employee.FirstName,employee.LastName
	  FROM employee
	  INNER JOIN users ON users.EmpCode = employee.EmpCode
	  WHERE users.ID = $userid";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $DocNo = $Result['DocNo'];
      $return[0]['Record']   = $Result['FirstName'] . ' ' . $Result['DocDate'];
    }

    $boolean = true;
  } else {
    $boolean = false;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "CreateDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "CreateDocument";
    $return['msg'] = 'cantcreate';
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowDocument($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $deptCode = $DATA["deptCode"];
  $DocNo = str_replace(' ', '%', $DATA["xdocno"]);
  $Datepicker = $DATA["Datepicker"];
  $selecta = $DATA["selecta"];
  // $Sql = "INSERT INTO log ( log ) VALUES ('$max : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "SELECT hospital.HptName,department.DepName,stock_out.DocNo,stock_out.DocDate,stock_out.Total,employee.FirstName,employee.LastName,TIME(stock_out.Modify_Date) AS xTime,stock_out.IsStatus
  FROM stock_out
  INNER JOIN department ON stock_out.DepCode = department.DepCode
  INNER JOIN hospital ON department.HptCode = hospital.HptCode
  INNER JOIN users ON stock_out.Modify_Code = users.ID
  INNER JOIN employee ON users.EmpCode = employee.EmpCode ";
  if ($selecta == 0) {
  $Sql .= "WHERE stock_out.DepCode = $deptCode AND stock_out.DocNo LIKE '%$DocNo%'";
  }
  $Sql .= "ORDER BY stock_out.DocNo DESC LIMIT 500";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName']   = $Result['HptName'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $Result['DocDate'];
    $return[$count]['Record']   = $Result['FirstName'] . " " . $Result['LastName'];
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $boolean = true;
    $count++;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowDocument";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowDocument_dis($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $DepCode_dis = $DATA["DepCode_dis"];
  $DocNo = str_replace(' ', '%', $DATA["xdocno"]);
  $Datepicker = $DATA["Datepicker"];
  $selecta = $DATA["selecta"];
  // $Sql = "INSERT INTO log ( log ) VALUES ('$max : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "SELECT hospital.HptName,
  department.DepName,
  disburse.DocNo,
  department_sub.DepSubName,
  disburse.DocDate,
  disburse.Total,
  employee.FirstName,
  employee.LastName,TIME(disburse.Modify_Date) AS xTime,disburse.IsStatus
  FROM disburse
  INNER JOIN department ON disburse.DepCode = department.DepCode
  INNER JOIN department_sub ON disburse.DepSubCode = department_sub.DepSubCode
  INNER JOIN hospital ON department.HptCode = hospital.HptCode
  INNER JOIN users ON disburse.Modify_Code = users.ID
  INNER JOIN employee ON users.EmpCode = employee.EmpCode ";
  if($selecta == 0) {
    $Sql .= "WHERE disburse.DepCode = $DepCode_dis AND disburse.IsStatus != 3 ORDER BY disburse.IsStatus, disburse.DocNo DESC LIMIT 500";
  }else{
    $Sql .= "WHERE disburse.IsStatus != 3 ORDER BY disburse.IsStatus, disburse.DocNo DESC LIMIT 500";
  }
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName']   = $Result['HptName'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DepSubName']   = $Result['DepSubName'];
    $date = explode('-',$Result['DocDate']);
    $newDate = $date[2].'-'.$date[1].'-'.$date[0];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $newDate;
    $return[$count]['Record']   = $Result['FirstName'] . " " . $Result['LastName'];
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $boolean = true;
    $count++;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowDocument_dis";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowDocument_dis";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function SelectDocument_dis($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $DocNo = $DATA["DocNo"];
  // $deptCode = $DATA["deptCode"];
  // $Datepicker = $DATA["Datepicker"];
  $Sql = "SELECT 
            	d_detail.DocNo,
              d_detail.Qty,
              d_detail.DepCodeFrom,
              d_detail.DepSubCodeFrom,
              hospital.HptName,
              department.DepName,
              department.DepCode,
              ds.DepSubCode,
              ds.DepSubName,
              item.ItemName,
              item.ItemCode,
              d.DocDate,
              employee.FirstName,
              employee.LastName,
              d2.DepName as DepFrom,
              ds2.DepSubName as DepSubFrom,
              d.IsStatus,
              TIME(d.Modify_Date) AS xTime
  FROM disburse_detail d_detail
  INNER JOIN disburse d ON d.DocNo = d_detail.DocNo
  INNER JOIN department ON department.DepCode = d_detail.DepCode
  INNER JOIN department d2 ON d2.DepCode = d_detail.DepCodeFrom
  INNER JOIN department_sub ds ON ds.DepSubCode = d_detail.DepCode
  INNER JOIN department_sub ds2 ON ds2.DepSubCode = d_detail.DepSubCodeFrom
  INNER JOIN hospital ON hospital.HptCode = department.HptCode
  INNER JOIN item ON item.ItemCode = d_detail.ItemCode
  INNER JOIN users ON d.Modify_Code = users.ID
  INNER JOIN employee ON users.EmpCode = employee.EmpCode
  WHERE d_detail.DocNo = '$DocNo'";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName']   = $Result['HptName'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DepSubName']   = $Result['DepSubName'];    
    $return[$count]['DepCodeFrom']   = $Result['DepCodeFrom'];
    $return[$count]['DepSubCodeFrom']   = $Result['DepSubCodeFrom'];
    $return[$count]['DocDate']   = $Result['DocDate'];
    $return[$count]['DepCode']   = $Result['DepCode'];
    $return[$count]['DepSubCode']   = $Result['DepSubCode'];
    $return[$count]['ItemCode']   = $Result['ItemCode'];
    $return[$count]['ItemName']   = $Result['ItemName'];
    $return[$count]['Qty']   = $Result['Qty'];
    $date = explode('-',$Result['DocDate']);
    $newDate = $date[2].'-'.$date[1].'-'.$date[0];
    $return['DocNo']   = $Result['DocNo'];
    $return['DocDate']   = $newDate;
    $return['Emp']   = $Result['FirstName'].'  '.$Result['LastName'];
    $return['xTime']   = $Result['xTime'];
    $return['DepFrom']   = $Result['DepFrom'];
    $return['DepSubFrom']   = $Result['DepSubFrom'];
    $return['IsStatus']   = $Result['IsStatus'];
    $boolean = true;
    $count++;
  }


  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "SelectDocument_dis";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "SelectDocument_dis";
    $return['msg'] = "notchosen";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowItem($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $searchitem = str_replace(' ', '%', $DATA["xitem"]);

  // $Sqlx = "INSERT INTO log ( log ) VALUES ('item : $item')";
  // mysqli_query($conn,$Sqlx);

  $Sql = "SELECT
  	item_stock.RowID,
		hospital.HptName,
		department.DepName,
		item_category.CategoryName,
		item.ItemCode,
		item.ItemName,
		item.UnitCode,
		item_unit.UnitName,
		item_stock.Qty
		FROM hospital
		INNER JOIN department ON hospital.HptCode = department.HptCode
		INNER JOIN item_stock ON department.DepCode = item_stock.DepCode
		INNER JOIN item ON item_stock.ItemCode = item.ItemCode
		INNER JOIN item_category ON item.CategoryCode= item_category.CategoryCode
		INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
		WHERE item.ItemName LIKE '%$searchitem%'
        GROUP BY item.ItemCode
		ORDER BY item.ItemCode ASC LImit 100";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $ItemCode = $Result['ItemCode'];
    $UnitCode = $Result['UnitCode'];
    $count2 = 0;
    $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
	  FROM item_multiple_unit
	  INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
	  WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
    $xQuery = mysqli_query($conn, $xSql);
    while ($xResult = mysqli_fetch_assoc($xQuery)) {
      $m1 = "MpCode_" . $ItemCode . "_" . $count;
      $m2 = "UnitCode_" . $ItemCode . "_" . $count;
      $m3 = "UnitName_" . $ItemCode . "_" . $count;
      $m4 = "Multiply_" . $ItemCode . "_" . $count;
      $m5 = "Cnt_" . $ItemCode;

      $return[$m1][$count2] = $xResult['MpCode'];
      $return[$m2][$count2] = $xResult['UnitCode'];
      $return[$m3][$count2] = $xResult['UnitName'];
      $return[$m4][$count2] = $xResult['Multiply'];
      $count2++;
    }
    $return[$m5][$count] = $count2;
    $count++;
    $boolean = true;
  }

  $return['Row'] = $count;

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowItem";
    $return[$count]['RowID'] = "";
    $return[$count]['UsageCode'] = "";
    $return[$count]['itemname'] = "";
    $return[$count]['UnitName'] = "";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowUsageCode($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $searchitem = $DATA["xitem"]; //str_replace(' ', '%', $DATA["xitem"]);

  // $Sqlx = "INSERT INTO log ( log ) VALUES ('item : $item')";
  // mysqli_query($conn,$Sqlx);

  $Sql = "SELECT
  		item_stock.RowID,
		hospital.HptName,
		department.DepName,
		item_category.CategoryName,
		item_stock.UsageCode,
		item.ItemCode,
		item.ItemName,
		item.UnitCode,
		item_unit.UnitName,
		item_stock.ParQty,
		item_stock.CcQty,
		item_stock.TotalQty
		FROM hospital
		INNER JOIN department ON hospital.HptCode = department.HptCode
		INNER JOIN item_stock ON department.DepCode = item_stock.DepCode
		INNER JOIN item ON item_stock.ItemCode = item.ItemCode
		INNER JOIN item_category ON item.CategoryCode= item_category.CategoryCode
		INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
		WHERE item.ItemCode = '$searchitem'
        AND item_stock.IsStatus = 7
        LImit 100";
  // (item_stock.IsStatus = 1 OR
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['UsageCode'] = $Result['UsageCode'];
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $ItemCode = $Result['ItemCode'];
    $UnitCode = $Result['UnitCode'];
    $count2 = 0;
    $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
	  FROM item_multiple_unit
	  INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
	  WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
    $xQuery = mysqli_query($conn, $xSql);
    while ($xResult = mysqli_fetch_assoc($xQuery)) {
      $m1 = "MpCode_" . $ItemCode . "_" . $count;
      $m2 = "UnitCode_" . $ItemCode . "_" . $count;
      $m3 = "UnitName_" . $ItemCode . "_" . $count;
      $m4 = "Multiply_" . $ItemCode . "_" . $count;
      $m5 = "Cnt_" . $ItemCode;

      $return[$m1][$count2] = $xResult['MpCode'];
      $return[$m2][$count2] = $xResult['UnitCode'];
      $return[$m3][$count2] = $xResult['UnitName'];
      $return[$m4][$count2] = $xResult['Multiply'];
      $count2++;
    }
    $return[$m5][$count] = $count2;
    $count++;
    $boolean = true;
  }

  $return['Row'] = $count;

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowUsageCode";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowUsageCode";
    $return[$count]['RowID'] = "";
    $return[$count]['UsageCode'] = "";
    $return[$count]['itemname'] = "";
    $return[$count]['UnitName'] = "";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getImport($conn, $DATA)
{
  $count = 0;
  $count2 = 0;
  $boolean = false;
  $Sel = $DATA["Sel"];
  $Hotp = $DATA["Hotp"];
  $DocNo = $DATA["DocNo"];
  $xItemStockId = $DATA["xrow"];
  $DepCode = $DATA["DepCode"];
  $ItemStockId = explode(",", $xItemStockId);
  $xqty = $DATA["xqty"];
  $nqty = explode(",", $xqty);
  // $xweight = $DATA["xweight"];
  // $nweight = explode(",", $xweight);
  $xunit = $DATA["xunit"];
  $nunit = explode(",", $xunit);

  $max = sizeof($ItemStockId, 0);

  for ($i = 0; $i < $max; $i++) {
    $iItemStockId = $ItemStockId[$i];
    $iqty = $nqty[$i];
    // $iweight = $nweight[$i];
    $iunit1 = 0;
    $iunit2 = $nunit[$i];

    $Sql = "SELECT item_stock.ItemCode,item.UnitCode
		  FROM item_stock
		  INNER JOIN item ON item_stock.ItemCode = item.ItemCode
      WHERE RowID = $iItemStockId";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ItemCode  = $Result['ItemCode'];
      $iunit1    = $Result['UnitCode'];
      // $return['sql'] = $Sql;
      echo json_encode($return);

    }

    $Sql = "SELECT COUNT(*) as Cnt
		  FROM stock_out_detail
		  INNER JOIN item  ON stock_out_detail.ItemCode = item.ItemCode
		  INNER JOIN stock_out ON stock_out.DocNo = stock_out_detail.DocNo
		  WHERE stock_out.DocNo = '$DocNo'
		  AND item.ItemCode = '$ItemCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $chkUpdate = $Result['Cnt'];
    }
    $iqty2 = $iqty;
    if ($iunit1 != $iunit2) {
      $Sql = "SELECT item_multiple_unit.Multiply
			  FROM item_multiple_unit
			  WHERE item_multiple_unit.UnitCode = $iunit1
			  AND item_multiple_unit.MpCode = $iunit2";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Multiply = $Result['Multiply'];
        $iqty2 = $iqty / $Multiply;
      }
    }

    if ($chkUpdate == 0) {
      if ($Sel == 1) {
        $Sql = "INSERT INTO stock_out_detail (DocNo,ItemCode,UnitCode,Qty,IsCancel,DepCode) VALUES ('$DocNo','$ItemCode',$iunit2,$iqty2,0,$DepCode)";
        mysqli_query($conn, $Sql);
      } else {
        $Sql = "INSERT INTO stock_out_detail_sub (DocNo,ItemCode) VALUES ('$DocNo','$ItemCode')";
        mysqli_query($conn, $Sql);
       
      }
    } 
  }
  if ($Sel == 2) {
    $n = 0;
    $Sql = "SELECT COUNT(*) AS Qty FROM stock_out_detail_sub WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $Qty[$n] = $Result['Qty'];
      $n++;
    }
    for ($i = 0; $i < $n; $i++) {
      $xQty = $Qty[$i];
      // mysqli_query($conn,"INSERT INTO log ( log ) VALUES ('$n :: $xQty :: $chkUpdate :: $iweight')");
      if ($chkUpdate == 0) {
        $Sql = "INSERT INTO stock_out_detail (DocNo,ItemCode,UnitCode,Qty,IsCancel) VALUES ('$DocNo','$ItemCode',$iunit2,$xQty,0)";
      } else {
        $Sql = "UPDATE stock_out_detail SET Qty = $xQty WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
      }
      mysqli_query($conn, $Sql);
    }
  }
    
  	ShowDetail($conn, $DATA);
}

function getCnt($conn,$itemcode,$deptCode){
    $Cnt = 0;
    $Sql = "SELECT COUNT(*) AS Cnt
    FROM item_stock_detail
    WHERE item_stock_detail.ItemCode = '$itemcode'
    AND item_stock_detail.DepCode = $deptcode";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = $Result['Cnt'];
    }
    return $Cnt;
}


function UpdateDetailQty($conn, $DATA)
{
  $Id  = $DATA["Id"];
  $Qty  =  $DATA["Qty"];
  $ItemCode  =  $DATA["ItemCode"];
  $add  =  $DATA["add"];
  $count = 0;
 
  
  $Sql = "UPDATE stock_out_detail SET Qty = $add WHERE stock_out_detail.Id = $Id";
  mysqli_query($conn, $Sql);
  echo json_encode($return);
  // ShowDetail($conn, $DATA);
}
function SaveQtyTime($conn, $DATA)
{
  $RowID  = $DATA["RowID"];
  $ItemCode  =  $DATA["ItemCode"];
  $DocNo  =  $DATA["DocNo"];
  $add  =  $DATA["add"];
  $Sel  =  $DATA["Sel"];
  $count = 0;

  
  $Sql = "UPDATE stock_out_detail SET Qty = $add WHERE stock_out_detail.Id = $RowID";
  $return['sql'] = $Sql;
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['Sel'] = $Sel;
    $return['form'] = "SaveQtyTime";
    $return['msg'] = "Save Success...";
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

function UpdateDetailWeight($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $Weight  =  $DATA["Weight"];
  $Price  =  $DATA["Price"];
  $isStatus = $DATA["isStatus"];
  $DocNo = $DATA["DocNo"];

  //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$RowID / $Weight')";
  //	mysqli_query($conn,$Sqlx);

  $Sql = "UPDATE stock_out_detail
	SET Weight = $Weight
	WHERE stock_out_detail.Id = $RowID";
  mysqli_query($conn, $Sql);

  ShowDetail($conn, $DATA);
}

function updataDetail($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $docno  = $DATA["docno"];
  $DepSubCode  = $DATA["DepSubCode"];
  $Sql = "UPDATE stock_out_detail 
    SET stock_out_detail.DepSubCode = $DepSubCode 
    WHERE stock_out_detail.Id = $RowID";
  $return['sql'] = $Sql;
  echo json_encode($return);
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function DeleteItem($conn, $DATA)
{
  $RowID    = $DATA["rowid"];
  $DocNo    = $DATA["DocNo"];
  $DepCode  = $DATA["DepCode"];
  $ItemCode = "";
    $Sql = "SELECT ItemCode,Qty FROM stock_out_detail WHERE stock_out_detail.Id = $RowID";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $ItemCode = $Result['ItemCode'];
        $Cnt = getCnt($conn,$DepCode,$ItemCode);
        //mysqli_query($conn,"INSERT INTO log ( log ) VALUES ('$Cnt / $DepCode / $ItemCode')");
        if( $Cnt == 0 ){
            $xSql = "INSERT INTO item_stock_detail
            (ItemCode,DepCode,Qty)
            VALUES
            ('$ItemCode',$DepCode,".$Result['Qty'].")";
        }else{
            $xSql = "UPDATE item_stock_detail SET Qty = (Qty - ".$Result['Qty'].") WHERE ItemCode = '$ItemCode' AND DepCode = $DepCode";
        }
        mysqli_query($conn, $xSql);
    }

  $n = 0;
  $Sql = "SELECT stock_out_detail_sub.UsageCode,stock_out_detail.ItemCode
  FROM stock_out_detail
  INNER JOIN stock_out_detail_sub ON stock_out_detail.DocNo = stock_out_detail_sub.DocNo
  WHERE  stock_out_detail.Id = $RowID";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $ItemCode = $Result['ItemCode'];
    $UsageCode[$n] = $Result['UsageCode'];
    $n++;
  }

  for ($i = 0; $i < $n; $i++) {
    $xUsageCode = $UsageCode[$i];
    $Sql = "UPDATE item_stock SET IsStatus = 1 WHERE UsageCode = '$xUsageCode'";
    mysqli_query($conn, $Sql);
  }

    $Sql = "DELETE FROM stock_out_detail_sub
	WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
  mysqli_query($conn, $Sql);

  $Sql = "DELETE FROM stock_out_detail WHERE stock_out_detail.Id = $RowID";
  mysqli_query($conn, $Sql);

  ShowDetail($conn, $DATA);
}

function save_dis($conn, $DATA)
{
  $Docnox = $DATA["Docnox"];
  $deptCodex = $DATA["deptCodex"];
  $DepSubCodex = $DATA["DepSubCodex"];
  $ItemCodex = $DATA["ItemCodex"];
  $iqty = $DATA["iqty"];
  $DepSubCodeFromx = $DATA["DepSubCodeFromx"];
  $deptCodeFromx = $DATA["deptCodeFromx"];


  $Sql = "UPDATE disburse SET IsStatus = 1 WHERE disburse.DocNo = '$Docnox'";
  mysqli_query($conn, $Sql);
        
        $xSql = "UPDATE item_stock_detail 
        SET item_stock_detail.Qty = (item_stock_detail.Qty - $iqty)  
        WHERE ItemCode = '$ItemCodex' AND DepCode = $deptCodex";
        mysqli_query($conn, $xSql);
        
        $SSql = "UPDATE stock_in_detail 
        SET stock_in_detail.Qty = (stock_in_detail.Qty  - $iqty) 
        WHERE stock_in_detail.ItemCode = '$ItemCodex'  AND stock_in_detail.DepCode = $deptCodex 
        AND stock_in_detail.DepSubCode = $DepSubCodex";
        mysqli_query($conn, $SSql);
    

        // $xxSql = "UPDATE item_stock_detail 
        // SET item_stock_detail.Qty = (item_stock_detail.Qty + $iqty)  
        // WHERE ItemCode = '$ItemCodex' AND DepCode = $deptCodeFromx";
        // mysqli_query($conn, $xxSql);

        // $SSSql = "UPDATE stock_in_detail 
        // SET stock_in_detail.Qty = (stock_in_detail.Qty  + $iqty) 
        // WHERE stock_in_detail.ItemCode = '$ItemCodex'  AND stock_in_detail.DepCode = $deptCodeFromx 
        // AND stock_in_detail.DepSubCode = $DepSubCodeFromx";
        // mysqli_query($conn, $SSSql);

        $S = "UPDATE disburse_detail 
        SET disburse_detail.Qty = $iqty 
        WHERE disburse_detail.ItemCode = '$ItemCodex'  AND disburse_detail.DepCode = $deptCodex 
        AND disburse_detail.DepSubCode = $DepSubCodex AND disburse_detail.DocNo =  '$Docnox'";
        mysqli_query($conn, $S);
        $return['sqll'] = $S;
  // ShowDocument($conn, $DATA);

  $return['status'] = "success";
  $return['form'] = "save_dis";
  echo json_encode($return);
  mysqli_close($conn);

}

function SelectDocument($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $DocNo = $DATA["xdocno"];
  $deptCode = $DATA["deptCode"];
  $Datepicker = $DATA["Datepicker"];
  $Sql = "SELECT   hospital.HptName,department.DepName,stock_out.DocNo,stock_out.DocDate,stock_out.Total,employee.FirstName,employee.LastName,TIME(stock_out.Modify_Date) AS xTime,stock_out.IsStatus
    FROM stock_out
    INNER JOIN department ON stock_out.DepCode = department.DepCode
    INNER JOIN hospital ON department.HptCode = hospital.HptCode
    INNER JOIN users ON stock_out.Modify_Code = users.ID
    INNER JOIN employee ON users.EmpCode = employee.EmpCode
    WHERE stock_out.DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName']   = $Result['HptName'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $Result['DocDate'];
    $return[$count]['Record']   = $Result['FirstName'] . " " . $Result['LastName'];
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $boolean = true;
    $count++;
  }
  $return['deptCode'] = $deptCode;
    

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "SelectDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return[$count]['HptName']   = "";
    $return[$count]['DepName']   = "";
    $return[$count]['DocNo']   = "";
    $return[$count]['DocDate']   = "";
    $return[$count]['Record']   = "";
    $return[$count]['RecNow']   = "";
    $return[$count]['Total']   = "0.00";
    $return['status'] = "failed";
    $return['form'] = "SelectDocument";
    $return['msg'] = "notchosen";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function SaveBill($conn, $DATA)
{
  $DocNo = $DATA["xdocno"];
  $isStatus = $DATA["isStatus"];
  $Hotp = $DATA["HptCode"];
  $deptCode = $DATA["deptCode"];
  $Sql = "UPDATE stock_out SET IsStatus = $isStatus WHERE stock_out.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
    $Sql = "SELECT ItemCode, Qty, DepSubCode FROM stock_out_detail WHERE stock_out_detail.DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $DepSubCode = $Result['DepSubCode'];
        $Qty = $Result['Qty'];
        $ItemCode = $Result['ItemCode'];
        
        $xSql = "UPDATE item_stock_detail SET item_stock_detail.Qty = (item_stock_detail.Qty - $Qty)  WHERE ItemCode = '$ItemCode' AND DepCode = $deptCode";
        mysqli_query($conn, $xSql);
        
        $SSql = "UPDATE stock_in_detail SET stock_in_detail.Qty = (stock_in_detail.Qty  - $Qty) 
        WHERE stock_in_detail.ItemCode = '$ItemCode'  AND stock_in_detail.DepCode = $deptCode 
        AND stock_in_detail.DepSubCode = $DepSubCode";
        mysqli_query($conn, $SSql);
    }
  // ShowDocument($conn, $DATA);
}

function ShowDetail($conn, $DATA)
{
  $count = 0;
  $Total = 0;
  $boolean = false;
  $DocNo = $DATA["DocNo"];
  $Hotpx = $DATA["Hotpx"]==''?1:$DATA["Hotpx"];
  $deptCodex = $DATA["deptCodex"]==''?1:$DATA["deptCodex"];

  //==========================================================
  $Sql = "SELECT
    stock_out_detail.Id,
    stock_out_detail.ItemCode,
    stock_out_detail.DepSubCode,
    item.ItemName,
    item.UnitCode AS UnitCode1,
    item_unit.UnitName,
    stock_out_detail.UnitCode AS UnitCode2,
    stock_out_detail.Qty,
    item.UnitCode
  FROM item
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
  INNER JOIN stock_out_detail ON stock_out_detail.ItemCode = item.ItemCode
  INNER JOIN item_unit ON stock_out_detail.UnitCode = item_unit.UnitCode
  WHERE stock_out_detail.DocNo = '$DocNo'
  ORDER BY stock_out_detail.Id DESC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID']      = $Result['Id'];
    $return[$count]['ItemCode']   = $Result['ItemCode'];
    $return[$count]['ItemName']   = $Result['ItemName'];
    $return[$count]['DepSubCode']   = $Result['DepSubCode'];
    $return[$count]['UnitCode']   = $Result['UnitCode2'];
    $return[$count]['Id']   = $Result['Id'];
    $return[$count]['UnitName']   = $Result['UnitName'];
    $return[$count]['Qty']         = $Result['Qty'];
    $UnitCode                     = $Result['UnitCode1'];
    $ItemCode                     = $Result['ItemCode'];

    $count2 = 0;
    $sSql = "SELECT department_sub.DepSubCode,department_sub.DepSubName
              FROM department_sub
              WHERE department_sub.HptCode = $Hotpx AND department_sub.DepCode = $deptCodex AND department_sub.IsStatus = 0";
    $xxmeQuery = mysqli_query($conn, $sSql);
    while ($zResult = mysqli_fetch_assoc($xxmeQuery)) {
      $m1 = "DepSubCode_" . $ItemCode . "_" . $count;
      $m2 = "DepSubName_" . $ItemCode . "_" . $count;
      $m3 = "Cnt_" . $ItemCode;

      $return[$m1][$count2] = $zResult['DepSubCode'];
      $return[$m2][$count2] = $zResult['DepSubName'];

      $count2++;
    }
    $return[$m3][$count] = $count2;
    $count++;
    $boolean = true;
  }
  if ($count == 0) $Total = 0;

  $Sql = "UPDATE stock_out SET Total = $Total WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  $return[0]['Total']    = round($Total, 2);
  $return['Row'] = $count;
  //==========================================================

  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowDetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowDetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function CancelBill($conn, $DATA){
  $DocNo = $DATA["DocNo"];
  // $Sql = "INSERT INTO log ( log ) VALUES ('DocNo : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "UPDATE stock_out SET IsStatus = 2  WHERE DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
}
//==========================================================
//
//==========================================================
if (isset($_POST['DATA'])) {
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace('\"', '"', $data), true);

  if ($DATA['STATUS'] == 'OnLoadPage') {
    OnLoadPage($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'getDepartment') {
    getDepartment($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowItem') {
    ShowItem($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowUsageCode') {
    ShowUsageCode($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowDocument') {
    ShowDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'SelectDocument') {
    SelectDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'CreateDocument') {
    CreateDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'CancelDocNo') {
    CancelDocNo($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'getImport') {
    getImport($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowDetail') {
    ShowDetail($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'UpdateDetailQty') {
    UpdateDetailQty($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'updataDetail') {
    updataDetail($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'UpdateDetailWeight') {
    UpdateDetailWeight($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'DeleteItem') {
    DeleteItem($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'SaveBill') {
    SaveBill($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'CancelBill') {
    CancelBill($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'UpdateRefDocNo') {
    UpdateRefDocNo($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'SaveQtyTime') {
    SaveQtyTime($conn,$DATA);
  } elseif ($DATA['STATUS'] == 'ShowDocument_dis') {
    ShowDocument_dis($conn,$DATA);
  } elseif ($DATA['STATUS'] == 'SelectDocument_dis') {
    SelectDocument_dis($conn,$DATA);
  } elseif ($DATA['STATUS'] == 'save_dis') {
    save_dis($conn,$DATA);
  }
  




} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
