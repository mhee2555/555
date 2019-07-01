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
function ShowDetail_InStock($conn,$DATA){
  $boolean = false;
  $count = 0;
  $dept = $DATA["dept"];
  $HptCode = $DATA["hos"];
  $chk = $DATA["chk"];
  // $search = $DATA["search"];
  // $selecta = $DATA["selecta"];

  if($chk == 1){
    $Sql = "SELECT item_stock_detail.RowID,
      item_stock_detail.ItemCode,
      item_stock_detail.DepCode,
      item_stock_detail.Qty,
      item.ItemName,
      item_category.CategoryName,
      hospital.HptCode,
      department.DepName,
    SUM(item_stock_detail.Qty) as total
    FROM item_stock_detail
    INNER JOIN item ON item.ItemCode = item_stock_detail.ItemCode
    INNER JOIN item_category on item_category.CategoryCode = item.CategoryCode
    INNER JOIN department ON department.DepCode = item_stock_detail.DepCode
    INNER JOIN hospital ON hospital.HptCode = department.HptCode
    WHERE hospital.HptCode = $HptCode AND department.DepCode = $dept
    GROUP BY ItemCode ORDER BY ItemCode";
  }else{
    $Sql = "SELECT item_stock_detail.RowID,
      item_stock_detail.ItemCode,
      item_stock_detail.DepCode,
      item_stock_detail.Qty,
      item.ItemName,
      item_category.CategoryName,
      hospital.HptCode,
      department.DepName,
    SUM(item_stock_detail.Qty) as total
    FROM item_stock_detail
    INNER JOIN item ON item.ItemCode = item_stock_detail.ItemCode
    INNER JOIN item_category on item_category.CategoryCode = item.CategoryCode
    INNER JOIN department ON department.DepCode = item_stock_detail.DepCode
    INNER JOIN hospital ON hospital.HptCode = department.HptCode
    WHERE hospital.HptCode = 1 AND department.DepCode = 1
    GROUP BY ItemCode ORDER BY ItemCode";
  }
  // $return['Sql'] = $Sql;
  // $return['sch'] = $chk;

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
    $hos                         = $Result['HptCode'];
    $DepCode	      = $Result['DepCode'];

    $count2 = 0;
    $sSql = "SELECT department_sub.DepSubCode,
                    department_sub.DepSubName
              FROM department_sub
              WHERE department_sub.HptCode = $hos
              AND department_sub.DepCode = $DepCode
              AND department_sub.IsStatus = 0";
              // $return['sql'] = $sSql;

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
      }
      #---------------------------------Qty--------------------------------------
      // $SqlChk = "SELECT disburse_detail.DocNo,
      // disburse_detail.DepCode,
      // disburse_detail.DepSubCode,
      // disburse_detail.ItemCode,
      // disburse.IsStatus
      // FROM disburse_detail 
      // INNER JOIN disburse ON disburse.DocNo = disburse_detail.DocNo
      // WHERE disburse_detail.DepCode = $DepCode AND disburse_detail.DepSubCode = $DepSubCode";
      // $ChkQuery = mysqli_query($conn, $SqlChk);
      // while ($ChkResult = mysqli_fetch_assoc($ChkQuery)) {
      //   $Chk1 = "DepCode_" . $ItemCode . "_" . $count;
      //   $Chk2 = "DepSubCode_" . $ItemCode . "_" . $count;
      //   $Chk3 = "ItemCode_" . $ItemCode . "_" . $count;
      //   $Chk4 = "IsStatus_" . $ItemCode . "_" . $count;
      //   $return[$Chk1][$count2] = $ChkResult['DepCode'];
      //   $return[$Chk2][$count2] = $ChkResult['DepSubCode'];
      //   $return[$Chk3][$count2] = $ChkResult['ItemCode'];
      //   $return[$Chk4][$count2] = $ChkResult['IsStatus'];
      //   $return['Chk'] = $SqlChk;
      // }

      $count2++;
    }
    $return[$m3][$count] = $count2;
    $count++;
    $boolean = true;
  }
  
  $return['Row'] = $count;

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "ShowDetail_InStock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return[$count]['DocNo'] = "";
    $return[$count]['DocDate'] = "";
    $return[$count]['Qty'] = "";
    $return[$count]['Elc'] = "";
    $return['status'] = "failed";
    $return['form'] = "ShowDetail_InStock";
    $return['msg'] = "notfound";
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
          AND department.IsStatus = 0
          ORDER BY department.DepName ASC";
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

function getDepartment_sub($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $DepCode = $DATA["DepCode"];
  $Sql = "SELECT department_sub.DepSubCode, department_sub.DepSubName
		  FROM department_sub
      WHERE department_sub.HptCode = $Hotp 
      AND department_sub.DepCode = $DepCode
      ORDER BY department.DepName ASC";
      $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepSubCode'] = $Result['DepSubCode'];
    $return[$count]['DepSubName'] = $Result['DepSubName'];
    $count++;
    $boolean = true;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "getDepartment_sub";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment_sub";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function CreateDocument($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $hotpCode = $DATA["hotpCode"];
  $DepSubCode = $DATA["DepSubCode"];
  // $deptCode = $DATA["deptCode"];
  $userid   = $DATA["userid"];

  $count=1;
  $ItemCodex = $DATA["ItemCodex"];
  $DocNox = $DATA["DocNox"];
  $deptCodex = $DATA["deptCodex"];
  $DepSubCodex   = $DATA["DepSubCodex"];
  $DepSubCodeFrom   = $DATA["DepSubCodeFrom"];
  $DepCodeFrom   = $DATA["DepCodeFrom"];
  $iqty   = $DATA["iqty"];

  if ($count == 1) {
    $Sql = "INSERT INTO disburse
      ( DocNo,DocDate,DepCode,DepSubCode,RefDocNo,
		    TaxNo,TaxDate,DiscountPercent,DiscountBath,
		    Total,IsCancel,Detail,
        disburse.Modify_Code,disburse.Modify_Date ) VALUES ( '$DocNox', DATE(NOW()), $deptCodex,$DepSubCodex,'', 0, NOW(), 0, 0, 0, 0,'', $userid,NOW() )";
        $return['sql'] = $Sql;
    mysqli_query($conn,$Sql);

    $meQuery = mysqli_query($conn, $Sql);

    $Sqlx = "INSERT INTO disburse_detail (DocNo,ItemCode,UnitCode,Qty,IsCancel,DepCode,DepSubCode,DepCodeFrom,DepSubCodeFrom) 
    VALUES ('$DocNox','$ItemCodex',1,$iqty,0,$deptCodex,$DepSubCodex,$DepCodeFrom,$DepSubCodeFrom)";
    $return['sql'] = $Sqlx;
    mysqli_query($conn,$Sqlx);
    $return['sql'] =$Sqlx;
  
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
  $Sql = "SELECT hospital.HptName,department.DepName,disburse.DocNo,disburse.DocDate,disburse.Total,employee.FirstName,employee.LastName,TIME(disburse.Modify_Date) AS xTime,disburse.IsStatus
  FROM disburse
  INNER JOIN department ON disburse.DepCode = department.DepCode
  INNER JOIN hospital ON department.HptCode = hospital.HptCode
  INNER JOIN users ON disburse.Modify_Code = users.ID
  INNER JOIN employee ON users.EmpCode = employee.EmpCode ";
  if ($selecta == 0) {
  $Sql .= "WHERE disburse.DepCode = $deptCode AND disburse.DocNo LIKE '%$DocNo%'";
  }
  $Sql .= "ORDER BY disburse.DocNo DESC LIMIT 500";
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

function SelectDocument($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $DocNo = $DATA["xdocno"];
  $deptCode = $DATA["deptCode"];
  $Datepicker = $DATA["Datepicker"];
  $Sql = "SELECT   hospital.HptName,
    department.DepName,
    disburse.DocNo,
    disburse.DocDate,
    disburse.Total,
    employee.FirstName,
    employee.LastName,
  TIME(disburse.Modify_Date) AS xTime,disburse.IsStatus
  FROM disburse
  INNER JOIN department ON disburse.DepCode = department.DepCode
  INNER JOIN hospital ON department.HptCode = hospital.HptCode
  INNER JOIN users ON disburse.Modify_Code = users.ID
  INNER JOIN employee ON users.EmpCode = employee.EmpCode
  WHERE disburse.DocNo = '$DocNo'";
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
		  FROM disburse_detail d
		  INNER JOIN item  ON d.ItemCode = item.ItemCode
		  INNER JOIN disburse ON disburse.DocNo = d.DocNo
		  WHERE disburse.DocNo = '$DocNo'
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
        $Sql = "INSERT INTO disburse_detail (DocNo,ItemCode,UnitCode,Qty,IsCancel,DepCode) VALUES ('$DocNo','$ItemCode',$iunit2,$iqty2,0,$DepCode)";
        mysqli_query($conn, $Sql);
      } else {
        $Sql = "INSERT INTO disburse_detail_sub (DocNo,ItemCode) VALUES ('$DocNo','$ItemCode')";
        mysqli_query($conn, $Sql);
       
      }
    } 
  }
  if ($Sel == 2) {
    $n = 0;
    $Sql = "SELECT COUNT(*) AS Qty FROM disburse_detail_sub WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $Qty[$n] = $Result['Qty'];
      $n++;
    }
    for ($i = 0; $i < $n; $i++) {
      $xQty = $Qty[$i];
      if ($chkUpdate == 0) {
        $Sql = "INSERT INTO disburse_detail (DocNo,ItemCode,UnitCode,Qty,IsCancel) VALUES ('$DocNo','$ItemCode',$iunit2,$xQty,0)";
      } else {
        $Sql = "UPDATE disburse_detail SET Qty = $xQty WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
      }
      mysqli_query($conn, $Sql);
    }
  }
    

  	ShowDetail($conn, $DATA);
}

function UpdateDetailQty($conn, $DATA)
{
  $Id  = $DATA["Id"];
  $Qty  =  $DATA["Qty"];
  $ItemCode  =  $DATA["ItemCode"];
  $add  =  $DATA["add"];
  $count = 0;

  $Sql = "UPDATE disburse_detail SET Qty = $add WHERE disburse_detail.Id = $Id";
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

  $Sql = "UPDATE disburse_detail SET Qty = $add WHERE disburse_detail.Id = $RowID";
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

  $Sql = "UPDATE disburse_detail
	SET Weight = $Weight
	WHERE disburse_detail.Id = $RowID";
  mysqli_query($conn, $Sql);

  ShowDetail($conn, $DATA);
}

function updataDetail($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $docno  = $DATA["docno"];
  $DepSubCode  = $DATA["DepSubCode"];
  $Sql = "UPDATE disburse_detail 
    SET disburse_detail.DepSubCode = $DepSubCode 
    WHERE disburse_detail.Id = $RowID";
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
    $Sql = "SELECT ItemCode,Qty FROM disburse_detail WHERE disburse_detail.Id = $RowID";
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
  $Sql = "SELECT disburse_detail_sub.UsageCode,disburse_detail.ItemCode
  FROM disburse_detail
  INNER JOIN disburse_detail_sub ON disburse_detail.DocNo = disburse_detail_sub.DocNo
  WHERE  disburse_detail.Id = $RowID";
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

    $Sql = "DELETE FROM disburse_detail_sub
	WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
  mysqli_query($conn, $Sql);

  $Sql = "DELETE FROM disburse_detail WHERE disburse_detail.Id = $RowID";
  mysqli_query($conn, $Sql);

  ShowDetail($conn, $DATA);
}

function SaveBill($conn, $DATA)
{
  $DocNo = $DATA["xdocno"];
  $isStatus = $DATA["isStatus"];
  $Hotp = $DATA["HptCode"];
  $deptCode = $DATA["deptCode"];
  $Sql = "UPDATE stock_in SET IsStatus = $isStatus WHERE stock_in.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);

    // $DepCode = 1;
    $Sql = "SELECT DepCode FROM department WHERE department.HptCode = $Hotp AND department.IsDefault = 1 ORDER BY DepCode ASC LIMIT 1";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $DepCode = $Result['DepCode'];
    }
  



    
    $Sql = "SELECT ItemCode, Qty FROM disburse_detail WHERE disburse_detail.DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $Cnt = 0;
      $Sqlxx = "SELECT COUNT(*) AS Cnt FROM item_stock_detail WHERE item_stock_detail.ItemCode = '".$Result['ItemCode']."' AND item_stock_detail.DepCode = $deptCode";
      $meQueryx = mysqli_query($conn, $Sqlxx);
        while ($Resultq = mysqli_fetch_assoc($meQueryx)) {
            $Cnt = $Resultq['Cnt'];
        }

        if( $Cnt == 0 ){
            $xSql = "INSERT INTO item_stock_detail (ItemCode,DepCode,Qty) VALUES ('".$Result['ItemCode']."',$deptCode,".$Result['Qty'].")";
        }else{
            $xSql = "UPDATE item_stock_detail SET Qty = (Qty + ".$Result['Qty'].") WHERE ItemCode = '".$Result['ItemCode']."' AND DepCode = $deptCode";
        }
        mysqli_query($conn, $xSql);
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
    d.Id,
    d.ItemCode,
    d.DepSubCode,
    item.ItemName,
    item.UnitCode AS UnitCode1,
    item_unit.UnitName,
    d.UnitCode AS UnitCode2,
    d.Qty,
    item.UnitCode
  FROM item
  INNER JOIN item_category i ON item.CategoryCode = i.CategoryCode
  INNER JOIN disburse_detail d ON d.ItemCode = item.ItemCode
  INNER JOIN item_unit ON d.UnitCode = item_unit.UnitCode
  WHERE d.DocNo = '$DocNo'
  ORDER BY d.Id DESC";
  $return['sql'] = $Sql;
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

  $Sql = "UPDATE stock_in SET Total = $Total WHERE DocNo = '$DocNo'";
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
  $Sql = "UPDATE stock_in SET IsStatus = 2  WHERE DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
}

function Select_FromStock($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $hotpCode = $DATA["HptCode"];
  $deptCode = $DATA["DepCode"];
  $DepSubCode = $DATA["DepSubCode"];
  $ItemCode = $DATA["ItemCode"];

  $userid   = $DATA["userid"];

  //	 $Sql = "INSERT INTO log ( log ) VALUES ('userid : $userid')";
  //     mysqli_query($conn,$Sql);

  $Sql = "SELECT CONCAT('DI',lpad($hotpCode, 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
  CURRENT_TIME() AS RecNow
    FROM disburse
    INNER JOIN department on disburse.DepCode = department.DepCode
    WHERE DocNo Like CONCAT('DI',lpad($hotpCode, 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
    AND department.HptCode = $hotpCode
    ORDER BY DocNo DESC LIMIT 1";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DocNo = $Result['DocNo'];
    $return['DocNo']   = $Result['DocNo'];
    $return['DocDate'] = $Result['DocDate'];
    $return['RecNow']  = $Result['RecNow'];
    $boolean = true;
  }

  $Sql2 = "SELECT i.ItemCode,
        i.Qty,
        item.ItemName,
        d.DepName,
        d.DepCode,
        ds.DepSubName,
        ds.DepSubCode,
        item_unit.UnitName,
        h.HptName
      FROM item_stock_detail i
      INNER JOIN department d ON d.DepCode = i.DepCode
      INNER JOIN department_sub ds ON ds.DepSubCode = 1
      INNER JOIN hospital h ON h.HptCode = 1
      INNER JOIN item ON item.ItemCode = '$ItemCode' 
      INNER JOIN item_unit ON item_unit.UnitCode = item.UnitCode
      WHERE ds.HptCode = $hotpCode AND i.ItemCode = '$ItemCode' 
      AND d.DepCode = $deptCode";
      $return['sql'] = $Sql2;
    $meQueryX = mysqli_query($conn, $Sql2);
    while ($ResultX = mysqli_fetch_assoc($meQueryX)) {
      $return[$count]['ItemCode']   = $ResultX['ItemCode'];
      $return[$count]['ItemName']   = $ResultX['ItemName'];
      $return[$count]['DepName'] = $ResultX['DepName'];
      $return[$count]['DepCode'] = $ResultX['DepCode'];
      $return[$count]['DepSubCode'] = $ResultX['DepSubCode'];
      $return[$count]['DepSubName']  = $ResultX['DepSubName'];
      $return[$count]['UnitName']  = $ResultX['UnitName'];
      $boolean = true;
      $count++;

    }
    $return['Qty']  = $DATA['Qty'];
  
    if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "Select_FromStock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "Select_FromStock";
    $return['msg'] = 'cantcreate';
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
  employee.FirstName,
  employee.LastName,TIME(disburse.Modify_Date) AS xTime,disburse.IsStatus
  FROM disburse
  INNER JOIN department ON disburse.DepCode = department.DepCode
  INNER JOIN department_sub ON disburse.DepSubCode = department_sub.DepSubCode
  INNER JOIN hospital ON department.HptCode = hospital.HptCode
  INNER JOIN users ON disburse.Modify_Code = users.ID
  INNER JOIN employee ON users.EmpCode = employee.EmpCode ";
  if ($selecta == 0) {
    $Sql .= "WHERE disburse.DepCode = $DepCode_dis ";
  }else{
    $Sql .= "ORDER BY disburse.DocNo DESC LIMIT 500";
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
  }elseif ($DATA['STATUS'] == 'getDepartment_sub') {
    getDepartment_sub($conn,$DATA);
  } elseif ($DATA['STATUS'] == 'ShowDetail_InStock') {
    ShowDetail_InStock($conn,$DATA);
  } elseif ($DATA['STATUS'] == 'Select_FromStock') {
    Select_FromStock($conn,$DATA);
  } elseif ($DATA['STATUS'] == 'ShowDocument_dis') {
    ShowDocument_dis($conn,$DATA);
  } elseif ($DATA['STATUS'] == 'SelectDocument_dis') {
    SelectDocument_dis($conn,$DATA);
  }
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
