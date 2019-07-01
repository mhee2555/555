<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
if($Userid==""){
  header("location:../index.html");
}

$language = $_GET['lang'];
if($language=="en"){
  $language = "en";
}else{
  $language = "th";
}

header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $array['recivedirtycloth'][$language]; ?></title>

  <link rel="icon" type="image/png" href="../img/pose_favicon.png">
  <!-- Bootstrap core CSS-->
  <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../bootstrap/css/tbody.css" rel="stylesheet">
  <link href="../bootstrap/css/myinput.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../template/css/sb-admin.css" rel="stylesheet">
  <link href="../css/xfont.css" rel="stylesheet">

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
    jqui = jQuery.noConflict(true);
  </script>

  <link href="../dist/css/sweetalert2.min.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>


  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <script src="../datepicker/dist/js/datepicker.min.js"></script>
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

  <script type="text/javascript">
    var summary = [];
    var xItemcode;

    $(document).ready(function (e) {
      console.log(window.parent.location.href);
      OnLoadPage();
      getDepartment();
      // ShowDocument_dis();
      //==============================
      $('.TagImage').bind('click', {
        imgId: $(this).attr('id')
      }, function (evt) {
        alert(evt.imgId);
      });
      //On create
      var userid = '<?php echo $Userid; ?>';
      if (userid != "" && userid != null && userid != undefined) {
        var dept = '<?php echo $_SESSION['
        Deptid ']; ?>';
        var data = {
          'STATUS': 'getDocument',
          'DEPT': dept
        };

        // console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
    }).mousemove(function (e) {
      parent.last_move = new Date();;
    }).keyup(function (e) {
      parent.last_move = new Date();;
    });

    jqui(document).ready(function ($) {

      dialogItemCode = jqui("#dialogItemCode").dialog({
        autoOpen: false,
        height: 680,
        width: 1200,
        modal: true,
        buttons: {
          "<?php echo $array['close'][$language]; ?>": function () {
            dialogItemCode.dialog("close");
          }
        },
        close: function () {
          console.log("close");
        }
      });

      dialogUsageCode = jqui("#dialogUsageCode").dialog({
        autoOpen: false,
        height: 680,
        width: 1200,
        modal: true,
        buttons: {
          "<?php echo $array['close'][$language]; ?>": function () {
            dialogUsageCode.dialog("close");
          }
        },
        close: function () {
          console.log("close");
        }
      });
    });

    function OpenDialogItem() {
      var docno = $("#docno").val();
      if (docno != "") {
        $("#TableItem tbody").empty();
        dialogItemCode.dialog("open");
        ShowItem();
      }
    }

    function OpenDialogUsageCode(itemcode) {
      xItemcode = itemcode;
      var docno = $("#docno").val();
      if (docno != "") {
        dialogItemCode.dialog("close");
        dialogUsageCode.dialog("open");
        $("#TableItem tbody").empty();
        ShowUsageCode();
      }
    }

    function ShowUsageCode() {
      // var searchitem = $('#searchitem1').val();
      var docno = $("#docno").val();
      var data = {
        'STATUS': 'ShowUsageCode',
        'docno': docno,
        'xitem': xItemcode
      };
      senddata(JSON.stringify(data));
    }

    function DeleteItem() {
      var docno = $("#docno").val();
      var xrow = $("#checkrow:checked").val();
      var DepCode = $('#department option:selected').attr("value");
      xrow = xrow.split(",");

      swal({
        title: "<?php echo $array['confirm'][$language]; ?>",
        text: "<?php echo $array['confirm1'][$language]; ?>" + xrow[1] +
          "<?php echo $array['confirm2'][$language]; ?>",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        var data = {
          'STATUS': 'DeleteItem',
          'rowid': xrow[0],
          'DocNo': docno,
          'DepCode': DepCode
        };
        senddata(JSON.stringify(data));
      })
    }

    function CancelDocument() {
      var docno = $("#docno").val();

      swal({
        title: "<?php echo $array['confirm'][$language]; ?>",
        text: "<?php echo $array['canceldata4'][$language];?> " + docno + " ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        CancelBill();
      })
    }

    //======= On create =======
    //console.log(JSON.stringify(data));
    function OnLoadPage() {
      var data = {
        'STATUS': 'OnLoadPage'
      };
      senddata(JSON.stringify(data));
      $('#isStatus').val(0)
    }



    function getDepartment() {
      var Hotp = $('#hotpital option:selected').attr("value");
      if (typeof Hotp == 'undefined') Hotp = "1";
      var data = {
        'STATUS': 'getDepartment',
        'Hotp': Hotp
      };
      senddata(JSON.stringify(data));
    }

    function ShowDocument(selecta) {
      var searchdocument = $('#searchdocument').val();
      if (typeof searchdocument == 'undefined') searchdocument = "";
      var deptCode = $('#Dep2 option:selected').attr("value");
      if (typeof deptCode == 'undefined') deptCode = "1";
      var data = {
        'STATUS': 'ShowDocument',
        'xdocno': searchdocument,
        'selecta': selecta,
        'deptCode': deptCode
      };
      senddata(JSON.stringify(data));
    }


    function ShowItem() {
      var searchitem = $('#searchitem').val();
      var data = {
        'STATUS': 'ShowItem',
        'xitem': searchitem
      };
      senddata(JSON.stringify(data));
    }

    function SelectDocument() {
      var selectdocument = "";
      $("#checkdocno:checked").each(function () {
        selectdocument = $(this).val();
      });
      var deptCode = $('#Dep2 option:selected').attr("value");
      if (typeof deptCode == 'undefined') deptCode = "1";
      $('#department').attr("disabled", true);
      var data = {
        'STATUS': 'SelectDocument',
        'xdocno': selectdocument,
        'deptCode': deptCode
      };
      senddata(JSON.stringify(data));
    }

    function unCheckDocDetail() {
      // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
      if ($('input[name="checkdocdetail"]:checked').length == $('input[name="checkdocdetail"]').length) {
        $('input[name="checkAllDetail').prop('checked', true);
      } else {
        $('input[name="checkAllDetail').prop('checked', false);
      }
    }

    function ShowDetail() {
      var docno = $("#docno").val();
      var Hotpx = $('#hotpital option:selected').attr("value");
      var deptCodex = $('#department option:selected').attr("value");

      var data = {
        'STATUS': 'ShowDetail',
        'DocNo': docno,
        'deptCodex': deptCodex,
        'Hotpx': Hotpx

      };
      senddata(JSON.stringify(data));
    }

    function CancelBill() {
      var docno = $("#docno").val();
      var data = {
        'STATUS': 'CancelBill',
        'DocNo': docno,
        'selecta': '0'
      };
      senddata(JSON.stringify(data));
      $('#profile-tab').tab('show');
      ShowDocument();
    }

    function getImport(Sel) {
      var docno = $("#docno").val();
      var DepCode = $("#department").val();
      var iArray = [];
      var qtyArray = [];
      var chkArray = [];
      var unitArray = [];
      var i = 0;
      console.log(docno);
      console.log(iArray);
      console.log(qtyArray);
      console.log(chkArray);
      console.log(unitArray);
      if (Sel == 1) {
        $("#checkitem:checked").each(function () {
          iArray.push($(this).val());
        });
      } else {
        $("#checkitemSub:checked").each(function () {
          iArray.push($(this).val());
        });
      }
      for (var j = 0; j < iArray.length; j++) {
        if (Sel == 1)
          chkArray.push($("#RowID" + iArray[j]).val());
        else
        chkArray.push($("#RowIDSub" + iArray[j]).val());
        qtyArray.push($("#iqty" + iArray[j]).val());
        unitArray.push($("#iUnit_" + iArray[j]).val());
      }
      var xrow = chkArray.join(',');
      var xqty = qtyArray.join(',');
      // var xweight = weightArray.join(',') ;
      var xunit = unitArray.join(',');

      var Hotp = $('#hotpital option:selected').attr("value");
      if (typeof Hotp == 'undefined') Hotp = "1";
      $('#TableDetail tbody').empty();
      var data = {
        'STATUS': 'getImport',
        'xrow': xrow,
        'xqty': xqty,
        'xunit': xunit,
        'DocNo': docno,
        'Sel': Sel,
        'Hotp': Hotp,
        'DepCode': DepCode
      };
      senddata(JSON.stringify(data));
      dialogItemCode.dialog("close");
      dialogUsageCode.dialog("close");
      ShowDetail();
    }

    var isChecked1 = false;
    var isChecked2 = false;

    function getCheckAll(sel) {
      if (sel == 0) {
        isChecked1 = !isChecked1;
        $('input[name="checkdocno"]').each(function () {
          this.checked = isChecked1;
        });
        getDocDetail();
      } else {
        isChecked2 = !isChecked2;
        $('input[name="checkdocdetail"]').each(function () {
          this.checked = isChecked2;
        });
      }
    }

    function convertUnit(rowid, selectObject) {
      var Id = rowid;
      var DepSubCode = selectObject.value;
      var docno = $("#docno").val();

      // console.log(Id);
      // console.log(DepSubCode);
      // console.log(docno);
      var data = {
        'STATUS': 'updataDetail',
        'Rowid': Id,
        'DocNo': docno,
        'DepSubCode': DepSubCode
      };

      senddata(JSON.stringify(data));
    }

    function CreateDocument() {
      var userid = '<?php echo $Userid; ?>';
      var hotpCode = $('#hotpital option:selected').attr("value");
      var deptCode = $('#department option:selected').attr("value");
      $('#TableDetail tbody').empty();
      swal({
        title: "<?php echo $array['confirm'][$language]; ?>",
        text: "<?php echo $array['hospital'][$language]; ?> : " + $('#hotpital option:selected').text() +
          " <?php echo $array['department'][$language]; ?> : " + $('#department option:selected').text(),
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        var data = {
          'STATUS': 'CreateDocument',
          'hotpCode': hotpCode,
          'deptCode': deptCode,
          'userid': userid
        };
        senddata(JSON.stringify(data));
      })
    }

    function canceldocno(docno) {
      swal({
        title: "<?php echo $array['confirmdelete'][$language]; ?>",
        text: "<?php echo $array['confirmdelete1'][$language]; ?>" + docno +
          "<?php echo $array['confirmdelete2'][$language]; ?>",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['deleete'][$language]; ?>",
        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        var data = {
          'STATUS': 'CancelDocNo',
          'DocNo': docno
        };
        senddata(JSON.stringify(data));
        getSearchDocNo();
      })
    }

    function addnum(cnt) {
      var add = parseInt($('#iqty' + cnt).val()) + 1;
      if ((add > 0) && (add <= 500)) {
        $('#iqty' + cnt).val(add);
      }
    }

    function subtractnum(cnt) {
      var sub = parseInt($('#iqty' + cnt).val()) - 1;
      if ((sub > 0) && (sub <= 500)) {
        $('#iqty' + cnt).val(sub);
      }
    }

    function addnum1(Id) {
      var rowid = $('#id_' + Id).val();
      var ItemCode = $('#item_' + Id).data('value');
      var add = parseInt($('#qty1_' + Id).val()) + 1;
      if ((add >= 0) && (add <= 500)) {
        $('#qty1_' + Id).val(add);
      }
      var data = {
        'STATUS': 'UpdateDetailQty',
        'Id': rowid,
        'Qty': 1,
        'ItemCode': ItemCode,
        'add': add,
        // 'OleQty'  : newQty
      };
      // alert(Id);
      senddata(JSON.stringify(data));
    }

    function SaveQtyTime(Id) {
      var rowid = $('#id_' + Id).val();
      var ItemCode = $('#item_' + Id).data('value');
      var add = parseInt($('#qty1_' + Id).val());
      var DocNo = $('#docno').val();
      // alert(add);

      var data = {
        'STATUS': 'SaveQtyTime',
        'RowID': rowid,
        'ItemCode': ItemCode,
        'Sel': Id,
        'DocNo': DocNo,
        'add': add
      };
      // // console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function subtractnum1(Id) {
      var rowid = $('#id_' + Id).val();
      var sub = parseInt($('#qty1_' + Id).val()) - 1;
      if ((sub >= 0) && (sub <= 500)) {
        $('#qty1_' + Id).val(sub);
      }
      var data = {
        'STATUS': 'UpdateDetailQty',
        'Id': rowid,
        'Qty': sub
        // 'OleQty'		: newQty,
        // 'unitcode'	: unitcode
      };
      senddata(JSON.stringify(data));
    }

    function updateWeight(row, rowid) {
      var docno = $("#docno").val();
      var weight = $("#weight_" + row).val();
      var price = 0; //$("#price_"+row).val();
      var isStatus = $("#IsStatus").val();
      //alert(rowid+" :: "+docno+" :: "+weight);
      if (isStatus == 0) {
        var data = {
          'STATUS': 'UpdateDetailWeight',
          'Rowid': rowid,
          'DocNo': docno,
          'Weight': weight,
          'Price': price
        };
        senddata(JSON.stringify(data));
      }
    }

    function SaveBill() {
      var docno = $("#docno").val();
      var isStatus = $("#IsStatus").val();
      var dept = $('#department').val();
      var HptCode = $('#hotpital').val();

      if (isStatus == 1){
        isStatus = 0;
      }
      else{
        isStatus = 1;
      }

      if (isStatus == 1) {
        // alert(docno);
        var data = {
          'STATUS': 'SaveBill',
          'xdocno': docno,
          'isStatus': isStatus,
          'deptCode': dept,
          'HptCode': HptCode
        };
        senddata(JSON.stringify(data));

        $('#profile-tab').tab('show');
        $("#bImport").prop('disabled', true);
        $("#bDelete").prop('disabled', true);
        $("#bSave").prop('disabled', true);
        $("#bCancel").prop('disabled', true);
        ShowDocument();
      } else {
        $("#bImport").prop('disabled', false);
        $("#bDelete").prop('disabled', false);
        $("#bSave").prop('disabled', false);
        $("#bCancel").prop('disabled', false);
        $("#bSave").text('<?php echo $array['save'][$language]; ?>');
        $("#IsStatus").val("0");
        $("#docno").prop('disabled', false);
        $("#docdate").prop('disabled', false);
        $("#recorder").prop('disabled', false);
        $("#timerec").prop('disabled', false);
        $("#total").prop('disabled', false);
        var rowCount = $('#TableItemDetail >tbody >tr').length;
        for (var i = 0; i < rowCount; i++) {
          $('#qty1_' + i).prop('disabled', false);
          $('#weight_' + i).prop('disabled', false);
          $('#price_' + i).prop('disabled', false);

          $('#unit' + i).prop('disabled', false);
        }
      }
    }

    function UpdateRefDocNo() {
      var docno = $("#docno").val();
      var RefDocNo = $("#RefDocNo").val();
      // alert( isStatus );
      //	  if(isStatus==1)
      //	  		isStatus=0;
      //	  else
      //	  		isStatus=1;

      var data = {
        'STATUS': 'UpdateRefDocNo',
        'xdocno': docno,
        'RefDocNo': RefDocNo
      };
      senddata(JSON.stringify(data));
    }

    function logoff() {
      swal({
        title: '',
        text: '<?php echo $array['
        logout '][$language]; ?>',
        type: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showConfirmButton: false,
        timer: 1000,
        confirmButtonText: '<?php echo $array['confirm'][$language]; ?>'
      }).then(function () {
        window.location.href = "../logoff.php";
      }, function (dismiss) {
        window.location.href = "../logoff.php";
        if (dismiss === 'cancel') {

        }
      })
    }
    <!-- ===================================================== -->
    function ShowDocument_dis(selecta) {
      var searchdocument = $('#searchdocument').val();
      if (typeof searchdocument == 'undefined') searchdocument = "";
      var DepCode_dis = $('#Dep3 option:selected').attr("value");
      if (typeof DepCode_dis == 'undefined') DepCode_dis = "1";
      var data = {
        'STATUS': 'ShowDocument_dis',
        'xdocno': searchdocument,
        'selecta': selecta,
        'DepCode_dis': DepCode_dis
      };
      senddata(JSON.stringify(data));
    }
    function SelectDocument_dis() {
      var selectdocument = "";
      $("#checkdocno:checked").each(function () {
        selectdocument = $(this).val();
      });
      var data = {
        'STATUS': 'SelectDocument_dis',
        'DocNo': selectdocument
      };
      senddata(JSON.stringify(data));
    }

    function get_inStock()
    {
      var userid = '<?php echo $Userid; ?>';
      var DocNo = $('#Docno2').val();
      // var DepCode =$('#DepCode_x').data('value');
      // var DepSubCode =$('#DepSubCode_x').data('value');
      // var DepCodeFrom =$('#DepCodeFrom_x').data('value');
      // var DepSubCodeFrom =$('#DepSubCodeFrom_x').data('value');
      // var Qty =$('#Qty_get').data('value');

      var data = 
      {
        'STATUS':'Get_inStock',
        'DocNo':DocNo,
        'userid':userid
        // 'DepCode':DepCode,
        // 'DepSubCode':DepSubCode,
        // 'DepCodeFrom':DepCodeFrom,
        // 'DepSubCodeFrom':DepSubCodeFrom,
        // 'Qty':Qty
      }
      senddata(JSON.stringify(data));
    }
    <!-- ===================================================== -->
    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = '../process/stock_in.php';
      $.ajax({
        url: URL,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function (result) {
          try {
            var temp = $.parseJSON(result);
          } catch (e) {
            console.log('Error#542-decode error');
          }

          if (temp["status"] == 'success') {
            if (temp["form"] == 'OnLoadPage') {
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var Str = "<option value=" + temp[i]['HptCode'] + ">" + temp[i]['HptName'] + "</option>";
                $("#hotpital").append(Str);
              }
            } else if (temp["form"] == 'getDepartment') {
              $("#department").empty();
              $("#Dep2").empty();
              $("#Dep3").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var Str = "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
                $("#department").append(Str);
                $("#Dep2").append(Str);
                $("#Dep3").append(Str);
              }
            } else if ((temp["form"] == 'CreateDocument')) {
              swal({
                title: "<?php echo $array['createdocno'][$language]; ?>",
                text: temp[0]['DocNo'] + " <?php echo $array['success'][$language]; ?>",
                type: "success",
                showCancelButton: false,
                timer: 2000,
                // confirmButtonText: '<?php echo $array['confirm'][$language]; ?>',
                showConfirmButton: false
              });
              setTimeout(function () {
                OpenDialogItem();
              }, 2000);


              $("#docno").val(temp[0]['DocNo']);
              $("#docdate").val(temp[0]['DocDate']);
              $("#recorder").val(temp[0]['Record']);
              $("#timerec").val(temp[0]['RecNow']);
              $("#TableItemDetail tbody").empty();
              $("#bSave").text('<?php echo $array['save'][$language]; ?>');
              $("#bImport").prop('disabled', false);
              $("#bDelete").prop('disabled', false);
              $("#bSave").prop('disabled', false);
              $("#bCancel").prop('disabled', false);
              $("#docno").prop('disabled', false);
              $("#docdate").prop('disabled', false);
              $("#recorder").prop('disabled', false);
              $("#timerec").prop('disabled', false);
              $("#total").prop('disabled', false);

              //*-*-*-
            } else if (temp["form"] == 'ShowDocument') {
              $("#TableDocument tbody").empty();
              $("#TableItemDetail tbody").empty();

              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var rowCount = $('#TableDocument >tbody >tr').length;
                var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' value='" + temp[i]['DocNo'] +
                  "' >";
                var Status = "";
                var Style = "";
                if (temp[i]['IsStatus'] == 1) {
                  Status = "<?php echo $array['savesuccess'][$language]; ?>";
                  Style = "style='width: 10%;color: #20B80E;'";
                } else {
                  Status = "<?php echo $array['draft'][$language]; ?>";
                  Style = "style='width: 10%;color: #3399ff;'";
                }
                if (temp[i]['IsStatus'] == 2) {
                  Status = "<?php echo $array['cancelbill'][$language]; ?>";
                  Style = "style='width: 10%;color: #ff0000;'";
                }

                $StrTr = "<tr id='tr" + temp[i]['DocNo'] + "'>" +
                  "<td style='width: 10%;'>" + chkDoc + "</td>" +
                  "<td style='width: 15%;'>" + temp[i]['DocDate'] + "</td>" +
                  "<td style='width: 15%;'>" + temp[i]['DocNo'] + "</td>" +
                  "<td style='width: 15%;'>" + temp[i]['DepName'] + "</td>" +
                  "<td style='width: 18%;'>" + temp[i]['Record'] + "</td>" +
                  "<td style='width: 17%;'>" + temp[i]['RecNow'] + "</td>" +
                  // "<td style='width: 10%;'>"+temp[i]['Total']+"</td>"+
                  "<td " + Style + ">" + Status + "</td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableDocument tbody").append($StrTr);
                } else {
                  $('#TableDocument tbody:last-child').append($StrTr);
                }
              }
            } else if (temp["form"] == 'SelectDocument') {
              $('#home-tab').tab('show')
              $("#TableItemDetail tbody").empty();
              $("#docno").val(temp[0]['DocNo']);
              $("#docdate").val(temp[0]['DocDate']);
              $("#recorder").val(temp[0]['Record']);
              $("#timerec").val(temp[0]['RecNow']);
              $("#wTotal").val(temp[0]['Total']);
              $("#IsStatus").val(temp[0]['IsStatus']);
              $("#department").val(temp[0]['DepCode']);
              if (temp[0]['IsStatus'] == 0) {
                $("#bSave").text('<?php echo $array['save'][$language]; ?>');
                $("#bImport").prop('disabled', false);
                $("#bDelete").prop('disabled', false);
                $("#bSave").prop('disabled', false);
                $("#bCancel").prop('disabled', false);
              } else if (temp[0]['IsStatus'] == 1) {
                $("#bSave").text('<?php echo $array['edit'][$language]; ?>');
                $("#bImport").prop('disabled', true);
                $("#bDelete").prop('disabled', true);
                $("#bSave").prop('disabled', false);
                $("#bCancel").prop('disabled', true);
              } else {
                $("#bImport").prop('disabled', true);
                $("#bDelete").prop('disabled', true);
                //$("#bSave").prop('disabled', true);
                $("#bCancel").prop('disabled', true);

                $("#docno").prop('disabled', true);
                $("#docdate").prop('disabled', true);
                $("#recorder").prop('disabled', true);
                $("#timerec").prop('disabled', true);
                $("#total").prop('disabled', true);

                $('#qty1_' + i).prop('disabled', true);
                $('#weight_' + i).prop('disabled', true);
                $('#price_' + i).prop('disabled', true);

                $('#unit' + i).prop('disabled', true);
              }
              ShowDetail();
            } else if (temp["form"] == 'getImport' || temp["form"] == 'ShowDetail') {
              $("#TableItemDetail tbody").empty();
              var isStatus = $("#IsStatus").val();
              for (var i = 0; i < temp["Row"]; i++) {
                var rowCount = $('#TableItem >tbody >tr').length;
                var chkunit = "<select onchange='convertUnit(\"" + temp[i]['RowID'] +"\",this)' class='form-control' id='Unit_" + i + "'>";
                chkunit += "<option selected>-</option>";
                var nUnit = temp[i]['UnitName'];
                for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {

                  if (temp[i]['DepSubCode'] == temp['DepSubCode_' + temp[i]['ItemCode'] + '_' + i][j]) {
                    chkunit += "<option selected value='" + temp['DepSubCode_' + temp[i]['ItemCode'] + '_' + i][j] + "'>" + temp['DepSubName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";

                  } else {
                    chkunit += "<option value='" + temp['DepSubCode_' + temp[i]['ItemCode'] + '_' + i][j] + "'>" + temp['DepSubName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                  }

                }
                chkunit += "</select>";

                var chkDoc = "<input type='radio' name='checkrow' id='checkrow' value='" + temp[i]['RowID'] + "," + temp[i]['ItemName'] + "'>";

                var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum1(\"" + i +"\")'>-</button><input class='form-control' style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;' " + st2 + " id='qty1_" + i + "' value='" + temp[i]['Qty'] +"' onKeyPress='if(event.keyCode==13){SaveQtyTime(" + i + ")}'><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum1(\"" + i +"\")'>+</button></div>";
                // var Price ="<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:110px; margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='price_" +i + "' value='" + temp[i]['Price'] + "' OnBlur='updateWeight(\"" + i + "\",\"" + temp[i]['RowID'] + "\")'></div>";

                $StrTR = "<tr id='tr" + temp[i]['RowID'] + "'>" +
                  "<td style='width: 10%;'>" + chkDoc + " <label style='margin-left:10px;'> " + (i + 1) +
                  "</label></td>" +
                  "<td style='width: 10%;' data-value='" + temp[i]['ItemCode'] + "' id='item_" + i + "'>" + temp[i]['ItemCode'] + "</td>" +
                  "<td style='width: 19%;'>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 17%;font-size:24px;'>" + nUnit + "</td>" +
                  "<td style='width: 22%;'>" + Qty + "</td>" +
                  "<td style='width: 20%;'>" + chkunit + "</td>" +
                  "<td hidden ><input  id='id_" + i + "' value='" + temp[i]['Id'] + "'></td>" +
                  "</tr>";
                if (rowCount == 0) {
                  $("#TableItemDetail tbody").append($StrTR);
                } else {
                  $('#TableItemDetail tbody:last-child').append($StrTR);
                }
                if (isStatus == 0) {
                  $("#docno").prop('disabled', false);
                  $("#docdate").prop('disabled', false);
                  $("#recorder").prop('disabled', false);
                  $("#timerec").prop('disabled', false);
                  $("#total").prop('disabled', false);
                  $('#qty1_' + i).prop('disabled', false);
                  $('#weight_' + i).prop('disabled', false);
                  $('#price_' + i).prop('disabled', false);
                  $('#price_' + i).prop('disabled', false);
                  $('#unit' + i).prop('disabled', false);
                } else {
                  $("#docno").prop('disabled', true);
                  $("#docdate").prop('disabled', true);
                  $("#recorder").prop('disabled', true);
                  $("#timerec").prop('disabled', true);
                  $("#total").prop('disabled', true);
                  $('#qty1_' + i).prop('disabled', true);
                  $('#weight_' + i).prop('disabled', true);
                  $('#price_' + i).prop('disabled', true);
                  $('#unit' + i).prop('disabled', true);
                }
              }
            } else if ((temp["form"] == 'SaveQtyTime')) {
                // $('#RowID').val("");
                // $('#HotName').val("");
                // $('#CategoryMain').val("");
                // $('#CategorySub').val(temp['CategoryName']);
                // $('#Price').val(temp['Price']);
                // var rowCount = $('#TableDoc >tbody >tr').length;
                var Sel = temp["Sel"];
                var sv = "<?php echo $array['save'][$language]; ?>";
                var svs = "<?php echo $array['savesuccess'][$language]; ?>";

                if ((Sel + 1) == 6)
                    $('#qty1_0').focus().select();
                else
                    $('#qty1_' + (Sel + 1)).focus().select();

                swal({
                    title: sv,
                    text: svs,
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    showConfirmButton: false,
                    timer: 1000,
                    confirmButtonText: '<?php echo $array['confirm'][$language]; ?>'
                })
            } else if ((temp["form"] == 'ShowItem')) {
              var st1 = "style='font-size:24px;margin-left:20px; width:160px;font-family:THSarabunNew'";
              var st2 =
                "style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;font-family:THSarabunNew'"
              $("#TableItem tbody").empty();
              for (var i = 0; i < temp["Row"]; i++) {
                var rowCount = $('#TableItem >tbody >tr').length;

                var chkunit = "<select " + st1 + " class='form-control' style='font-size:24px;' id='iUnit_" + i +
                  "'>";
                var nUnit = "";

                for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {
                  if (temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] == temp[i]['UnitCode']) {
                    chkunit += "<option selected value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] +
                      ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                    nUnit = temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j];
                  } else {
                    chkunit += "<option value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp[
                      'UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                    nUnit = temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j];
                  }
                }
                chkunit += "</select>";

                var chkDoc = "<input type='checkbox' name='checkitem' id='checkitem' value='" + i +
                  "'><input type='hidden' id='RowID" + i + "' value='" + temp[i]['RowID'] + "'>";
                var Qty =
                  "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum(\"" +
                  i + "\")'>-</button><input class='form-control' " + st2 + " id='iqty" + i +
                  "' value='1' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\"" +
                  i + "\")'>+</button></div>";

                var Weight =
                  "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight" +
                  i + "' value='0' ></div>";

                $StrTR = "<tr id='tr" + temp[i]['RowID'] + "'>" +
                  "<td style='width: 10%;'>" + chkDoc + " <label style='margin-left:10px;'> " + (i + 1) +
                  "</label></td>" +
                  "<td style='width: 10%;'>" + temp[i]['ItemCode'] + "</td>" +
                  "<td style='width: 30%;'>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 33%;'>" + chkunit + "</td>" +
                  "<td style='width: 15%;'>" + Qty + "</td>" +
                  // "<td style='width: 13%;'>"+Weight+"</td>"+
                  "</tr>";
                if (rowCount == 0) {
                  $("#TableItem tbody").append($StrTR);
                } else {
                  $('#TableItem tbody:last-child').append($StrTR);
                }
              }
            } else if ((temp["form"] == 'ShowUsageCode')) {
              var st1 =
                "style='font-size:18px;margin-left:3px; width:100px;font-family:THSarabunNew;font-size:24px;'";
              var st2 =
                "style='height:40px;width:60px; margin-left:0px; text-align:center;font-family:THSarabunNew;font-size:32px;'"
              $("#TableUsageCode tbody").empty();
              for (var i = 0; i < temp["Row"]; i++) {
                var rowCount = $('#TableUsageCode >tbody >tr').length;

                var chkunit = "<select " + st1 + " onchange='convertUnit(\"" + temp[i]['RowID'] +
                  "\",this)' class='form-control' style='font-size:32px;' id='iUnit_" + i + "'>";

                for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {
                  if (temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] == temp[i]['UnitCode'])
                    chkunit += "<option selected value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] +
                    ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                  else
                    chkunit += "<option value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp[
                      'UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                }
                chkunit += "</select>";

                var chkDoc = "<input type='checkbox' name='checkitemSub' id='checkitemSub' value='" + i +
                  "'><input type='hidden' id='RowIDSub" + i + "' value='" + temp[i]['RowID'] + "'>";

                //var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' "+st2+" id='iqty"+i+"' value='1' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\""+i+"\")'>+</button></div>";

                var Weight =
                  "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight" +
                  i + "' value='0' ></div>";

                $StrTR = "<tr id='tr" + temp[i]['RowID'] + "'>" +
                  "<td style='width: 10%;'>" + chkDoc + " <label style='margin-left:10px;'> " + (i + 1) +
                  "</label></td>" +
                  "<td style='width: 20%;'>" + temp[i]['UsageCode'] + "</td>" +
                  "<td style='width: 40%;'>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 15%;'>" + chkunit + "</td>" +
                  "<td style='width: 13%;' align='center'>1</td>" +
                  "</tr>";
                if (rowCount == 0) {
                  $("#TableUsageCode tbody").append($StrTR);
                } else {
                  $('#TableUsageCode tbody:last-child').append($StrTR);
                }
              }
            } else if (temp["form"] == 'ShowDocument_dis') {
              $("#TableDocument_dis tbody").empty();
              $("#TableItemDetail tbody").empty();

              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var rowCount = $('#TableDocument >tbody >tr').length;
                var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' value='" + temp[i]['DocNo'] + "' >";
                var Status = "";
                var Style = "";
                if (temp[i]['IsStatus'] == 1) {
                  Status = "<?php echo $array['wait'][$language]; ?>";
                  Style = "style='width: 10%;color: #ffc107;'";
                }  else if(temp[i]['IsStatus'] == 2) {
                  Status = "<?php echo $array['cancelbill'][$language]; ?>";
                  Style = "style='width: 10%;color: #ff0000;'";
                } else if(temp[i]['IsStatus'] == 3){
                  Status = "<?php echo $array['getinstocked'][$language]; ?>";
                  Style = "style='width: 10%;color: #4f88e3;'";
                } else {
                  Status = "<?php echo $array['noconfirm'][$language]; ?>";
                  Style = "style='width: 10%;color: #3399ff;'";
                }

                $StrTr = "<tr id='tr" + temp[i]['DocNo'] + "'>" +
                  "<td style='width: 3%;'>" + chkDoc + "</td>" +
                  "<td style='width: 10%;'>" + temp[i]['DocDate'] + "</td>" +
                  "<td style='width: 15%;'>" + temp[i]['DocNo'] + "</td>" +
                  "<td style='width: 15%;'>" + temp[i]['DepName'] + "</td>" +
                  "<td style='width: 15%;'>" + temp[i]['DepSubName'] + "</td>" +
                  "<td style='width: 13%;'>" + temp[i]['Record'] + "</td>" +
                  "<td style='width: 17%;'>" + temp[i]['RecNow'] + "</td>" +
                  "<td " + Style + ">" + Status + "</td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableDocument_dis tbody").append($StrTr);
                } else {
                  $('#TableDocument_dis tbody:last-child').append($StrTr);
                }
              }
            } else if(temp["form"]=='SelectDocument_dis'){
              var date = temp['DocDate'];
              $('#Docno2').val('');
              $('#Docdate2').val('');
              $('#Recorder2').val('');
              $('#Timerec2').val('');
              $('#dis_department2').val('');
              $('#department_sub2').val('');
              $('#disburseModal2').modal("show");
              $('#Docdate2').val(temp['DocDate']);
              $('#Docno2').val(temp['DocNo']);
              $('#Recorder2').val(temp['Emp']);
              $('#Timerec2').val(temp['xTime']);
              $('#dis_department2').val(temp['DepFrom']);
              $('#department_sub2').val(temp['DepSubFrom']);
              $("#table_disburse2 tbody").empty();
              $("#table_disburse2 tbody").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var Qty = "<div class='row' style='margin-left:54px;'><input type='number' class='form-control text-center' style='width:155px;font-size:25px;margin:2px;' id='iqty_dis' value='"+temp[i]['Qty']+"' min='1' max='"+temp[i]['Qty']+"'></div>";
                var rowCount = $('#table_disburse2 >tbody >tr').length;

                StrTr = "<tr id='tr" + temp[i]['ItemCode'] + "'>" +
                  "<td style='width: 10%;' data-value='" + temp[i]['ItemCode'] + "' id='item_x'>" + temp[i]['ItemCode'] + "</td>" +
                  "<td style='width: 10%;' hidden data-value='" + temp[i]['DocNo'] + "' id='docno_x'>" + temp[i]['DocNo'] + "</td>" +
                  "<td style='width: 10%;' hidden data-value='" + temp[i]['DepCode'] + "' id='DepCode_x'>" + temp[i]['DepCode'] + "</td>" +
                  "<td style='width: 10%;' hidden data-value='" + temp[i]['DepSubCode'] + "' id='DepSubCode_x'>" + temp[i]['DepSubCode'] + "</td>" +
                  "<td style='width: 10%;' hidden data-value='" + temp[i]['DepCodeFrom'] + "' id='DepCodeFrom_x'>" + temp[i]['DepCodeFrom'] + "</td>" +
                  "<td style='width: 10%;' hidden data-value='" + temp[i]['DepSubCodeFrom'] + "' id='DepSubCodeFrom_x'>" + temp[i]['DepSubCodeFrom'] + "</td>" +

                  "<td style='width: 20%;'>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 25%;text-align:left;'>" + temp[i]['DepName'] + "</td>" +
                  "<td style='width: 18%;'>" + temp[i]['DepSubName'] + "</td>" +
                  "<td style='width: 20%;text-align: right;' id='Qty_get' data-value='"+temp[i]['Qty']+"'>" + temp[i]['Qty'] + "</td>" +
                  "</tr>";
     
                if (rowCount == 0) {
                  $("#table_disburse2 tbody").append(StrTr);
                } else {
                  $('#table_disburse2 tbody:last-child').append(StrTr);
                }

                if(temp['IsStatus']==3){
                  $('#btn_getStock').attr('disabled', true);
                  $('#btn_getStock').text('<?php echo $array['getinstocked'][$language]; ?>');
                } else {
                  $('#btn_getStock').attr('disabled', false);
                  $('#btn_getStock').text('<?php echo $array['getinstock'][$language]; ?>');
                }
              }
            } else if ((temp["form"] == 'Get_inStock')) {
              swal({
                title: "<?php echo $array['yesconfirm'][$language]; ?>",
                text:  " <?php echo $array['success'][$language]; ?>",
                type: "success",
                showCancelButton: false,
                timer: 2000,
                // confirmButtonText: '<?php echo $array['confirm'][$language]; ?>',
                showConfirmButton: false
              });
              setTimeout(function () {
                $('#disburseModal2').modal("toggle");
                ShowDocument_dis(1);
                parent.OnLoadPage();
              }, 2000);
            }
          } else if (temp['status'] == "failed") {
            switch (temp['msg']) {
              case "notchosen":
                temp['msg'] = "<?php echo $array['choosemsg'][$language]; ?>";
                break;
              case "cantcreate":
                temp['msg'] = "<?php echo $array['cantcreatemsg'][$language]; ?>";
                break;
              case "noinput":
                temp['msg'] = "<?php echo $array['noinputmsg'][$language]; ?>";
                break;
              case "notfound":
                temp['msg'] = "<?php echo $array['notfoundmsg'][$language]; ?>";
                break;
              case "addsuccess":
                temp['msg'] = "<?php echo $array['addsuccessmsg'][$language]; ?>";
                break;
              case "addfailed":
                temp['msg'] = "<?php echo $array['addfailedmsg'][$language]; ?>";
                break;
              case "editsuccess":
                temp['msg'] = "<?php echo $array['editsuccessmsg'][$language]; ?>";
                break;
              case "editfailed":
                temp['msg'] = "<?php echo $array['editfailedmsg'][$language]; ?>";
                break;
              case "cancelsuccess":
                temp['msg'] = "<?php echo $array['cancelsuccessmsg'][$language]; ?>";
                break;
              case "cancelfailed":
                temp['msg'] = "<?php echo $array['cancelfailed'][$language]; ?>";
                break;
              case "nodetail":
                temp['msg'] = "<?php echo $array['nodetail'][$language]; ?>";
                break;
            }
            swal({
              title: '',
              text: temp['msg'],
              type: 'warning',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 2000,
              confirmButtonText: '<?php echo $array['confirm'][$language]; ?>'
            })
            $("#TableDocumentSS tbody").empty();
            $("#TableSendSterileDetail tbody").empty();
            $("#TableUsageCode tbody").empty();
            $("#TableItem tbody").empty();
          } else {
            console.log(temp['msg']);
          }
        },
        failure: function (result) {
          alert(result);
        },
        error: function (xhr, status, p3, p4) {
          var err = "Error " + " " + status + " " + p3 + " " + p4;
          if (xhr.responseText && xhr.responseText[0] == "{")
            err = JSON.parse(xhr.responseText).Message;
          console.log(err);
          alert(err);
        }
      });
    }
  </script>
  <style media="screen">
    body {
      font-family: 'THSarabunNew';
      font-size: 22px;
    }

    .nfont {
      font-family: 'THSarabunNew';
      font-size: 22px;
    }

    button,
    input[id^='qty'],
    input[id^='order'],
    input[id^='max'] {
      font-size: 24px !important;
    }

    .table>thead>tr>th {
      background: #4f88e3 !important;
    }

    table tr th,
    table tr td {
      border-right: 0px solid #bbb;
      border-bottom: 0px solid #bbb;
      padding: 5px;
    }

    table tr th:first-child,
    table tr td:first-child {
      border-left: 0px solid #bbb;
    }

    table tr th {
      background: #eee;
      border-top: 0px solid #bbb;
      text-align: left;
    }

    /* top-left border-radius */
    table tr:first-child th:first-child {
      border-top-left-radius: 6px;
    }

    /* top-right border-radius */
    table tr:first-child th:last-child {
      border-top-right-radius: 6px;
    }

    /* bottom-left border-radius */
    table tr:last-child td:first-child {
      border-bottom-left-radius: 6px;
    }

    /* bottom-right border-radius */
    table tr:last-child td:last-child {
      border-bottom-right-radius: 6px;
    }

    a.nav-link {
      width: auto !important;
    }

    .datepicker {
      z-index: 9999 !important
    }

    .hidden {
      visibility: hidden;
    }
    #disburseModal2 .modal-content {
        width: 800px !important;
        z-index: 999998 !important;
    }
    #disburseModal2{
        padding-right: 400px !important;
    }
  </style>
</head>

<body id="page-top">
  <input class='form-control' type="hidden" style="margin-left:-60px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;"id='IsStatus'>
  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">

      <div class="row" style="margin-top:-15px;">
        <!-- start row tab -->
        <div class="col-md-12">
          <!-- tag column 1 -->
          <div class="container-fluid">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                  aria-selected="true"><?php echo $array['titlestockin'][$language]; ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                  aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="document-tab" data-toggle="tab" href="#document" role="tab"
                  aria-controls="profile" aria-selected="false"><?php echo $array['document'][$language]; ?></a>
              </li>
            </ul>

            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <!-- /.content-wrapper -->
                <div class="row">
                  <div class="col-md-11">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                      <div class="card-body" style="padding:0px; margin-top:10px;">
                        <div class="row">
                          <div style="margin-left:20px;width:100px;">
                            <label><?php echo $array['hospital'][$language]; ?></label>
                          </div>
                          <div style="width:220px;">
                            <div class="row" style="font-size:24px;margin-left:2px;">
                              <select style='font-size:24px;width:220px;' class="form-control" id="hotpital"
                                onchange="getDepartment();" disabled="true">
                              </select>
                            </div>
                          </div>
                          <div style="margin-left:30px;width:120px;">
                            <label><?php echo $array['department'][$language]; ?></label>
                          </div>
                          <div style="width:220px;">
                            <div class="row" style="font-size:24px;margin-left:2px;">
                              <select style='font-size:24px;width:220px;' class="form-control" id="department">

                              </select>
                            </div>
                          </div>

                        </div>
                        <div class="row" style="margin-top:5px">
                          <div style="margin-left:20px;width:100px;">
                            <label><?php echo $array['docdate'][$language]; ?></label>
                          </div>
                          <div style="width:220px;">
                            <input type="text" class="form-control" style="font-size:24px;width:220px;"
                              name="searchitem" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>">
                          </div>
                          <div style="margin-left:30px;width:120px;">
                            <label><?php echo $array['docno'][$language]; ?></label>
                          </div>
                          <div style="width:220px;">
                            <input type="text" class="form-control" style="font-size:24px;width:220px;"
                              name="searchitem" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>">
                          </div>
                          <div style="margin-left:20px;width:100px;visibility: hidden">
                            <label><?php echo $array['refdocno'][$language]; ?></label>
                          </div>
                          <div style="width:220px;visibility: hidden">
                            <input class='form-control' style="font-size:20px;width:220px;height:40px;padding-top:6px;"
                              id='RefDocNo' placeholder="<?php echo $array['refdocno'][$language]; ?>"
                              OnBlur='UpdateRefDocNo()'>
                          </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                          <div style="margin-left:20px;width:100px;">
                            <label><?php echo $array['employee'][$language]; ?></label>
                          </div>
                          <div style="width:220px;">
                            <input type="text" class="form-control" style="font-size:24px;width:220px;"
                              name="searchitem" id="recorder"
                              placeholder="<?php echo $array['employee'][$language]; ?>">
                          </div>
                          <div style="margin-left:30px;width:120px;">
                            <label><?php echo $array['time'][$language]; ?></label>
                          </div>
                          <div style="width:220px;">
                            <input type="text" class="form-control" style="font-size:24px;width:220px;"
                              name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>">
                          </div>


                        </div>

                      </div>
                    </div>
                  </div> <!-- tag column 1 -->
                </div>

                <div class="row">
                  <div class="col-md-10">
                    <!-- tag column 1 -->
                    <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped"
                      id="TableItemDetail" width="100%" cellspacing="0" role="grid" style="">
                      <thead id="theadsum" style="font-size:24px;">
                        <tr role="row">
                          <th style='width: 10%;'><?php echo $array['no'][$language]; ?></th>
                          <th style='width: 10%;'><?php echo $array['code'][$language]; ?></th>
                          <th style='width: 19%;'><?php echo $array['item'][$language]; ?></th>
                          <th style='width: 21%;'><?php echo $array['unit'][$language]; ?></th>
                          <th style='width: 20%;'><?php echo $array['qty'][$language]; ?></th>
                          <th style='width: 20%;'><?php echo $array['department_sub'][$language]; ?></th>
                          <!-- <th style='width: 15%;'><center><?php echo $array['weight'][$language]; ?></center></th> -->
                        </tr>
                      </thead>
                      <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
                      </tbody>
                    </table>
                  </div> <!-- tag column 1 -->
                  <div class="col-md-1">
                    <!-- tag column 2 -->
                    <div class="container-fluid" style="margin-top:5px;">
                      <div class="card-body" style="padding:0px; margin-top:10px;">
                        <div class="row" style="margin-top:0px;">
                          <div class="col-md-1">
                            <div class="row" style="margin-left:2px;">
                              <div class="row" style="margin-left:20px;">
                                <button style="width:105px" ; type="button" class="btn btn-info" onclick="CreateDocument()" id="bCreate"><?php echo $array['createdocno'][$language]; ?></button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row" style="margin-top:4px;">
                          <div class="col-md-1">
                            <div class="row" style="margin-left:2px;">
                              <div class="row" style="margin-left:20px;">
                                <button onclick="OpenDialogItem()" type="button" style="width:105px" class="btn btn-warning" id="bImport"><?php echo $array['import'][$language]; ?></button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row" style="margin-top:4px;">
                          <div class="col-md-1">
                            <div class="row" style="margin-left:2px;">
                              <div class="row" style="margin-left:20px;">
                                <button onclick="DeleteItem()" type="button" style="width:105px" class="btn"
                                  style="background : #F98707;"
                                  id="bDelete"><?php echo $array['delitem'][$language]; ?></button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row" style="margin-top:4px;">
                          <div class="col-md-1">
                            <div class="row" style="margin-left:2px;">
                              <div class="row" style="margin-left:20px;">
                                <button style="width:105px" type="button" class="btn btn-success" onclick="SaveBill()"
                                  id="bSave"><?php echo $array['save'][$language]; ?></button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row" style="margin-top:4px;">
                          <div class="col-md-1">
                            <div class="row" style="margin-left:2px;">
                              <div class="row" style="margin-left:20px;">
                                <button style="width:105px" ; type="button" class="btn btn-danger"
                                  onclick="CancelDocument()"
                                  id="bCancel"><?php echo $array['cancel'][$language]; ?></button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>

              </div>
              <!-- search document -->
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row" style="margin-top:10px;">
                  <div class="col-md-4">
                    <div class="row" style="font-size:24px;margin-left:2px;">
                      <select class="form-control" style='font-size:24px;' id="Dep2">
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="row" style="margin-left:2px;">
                      <input type="text" class="form-control" style="font-size:24px;width:50%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                      <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowDocument(0);"><?php echo $array['search'][$language]; ?></button>
                      <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowDocument(1);"><?php echo $array['searchalldep'][$language]; ?></button>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <button type="button" style="margin-left:90px;" class="btn btn-warning" name="button"  onclick="SelectDocument();"><?php echo $array['show'][$language]; ?></button>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <!-- tag column 1 -->
                    <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid">
                      <thead id="theadsum" style="font-size:24px;">
                        <tr role="row">
                          <th style='width: 10%;'>&nbsp;</th>
                          <th style='width: 15%;'><?php echo $array['docdate'][$language]; ?></th>
                          <th style='width: 15%;'><?php echo $array['docno'][$language]; ?></th>
                          <th style='width: 15%;'><?php echo $array['department'][$language]; ?></th>
                          <th style='width: 18%;'><?php echo $array['employee'][$language]; ?></th>
                          <th style='width: 17%;'><?php echo $array['time'][$language]; ?></th>
                          <th style='width: 10%;'><?php echo $array['status'][$language]; ?></th>
                        </tr>
                      </thead>
                      <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:400px;">
                      </tbody>
                    </table>
                  </div> <!-- tag column 1 -->
                </div>

              </div> <!-- end row tab -->

              <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-tab">
                <div class="row" style="margin-top:10px;">
                  <div class="col-md-4">
                    <div class="row" style="font-size:24px;margin-left:2px;">
                      <select class="form-control" style='font-size:24px;' id="Dep3">
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="row" style="margin-left:2px;">
                      <input type="text" class="form-control" style="font-size:24px;width:50%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                      <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowDocument_dis(0);"><?php echo $array['search'][$language]; ?></button>
                      <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowDocument_dis(1);"><?php echo $array['searchall'][$language]; ?></button>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <button type="button" style="margin-left:90px;" class="btn btn-warning" name="button"
                      onclick="SelectDocument_dis();"><?php echo $array['show'][$language]; ?></button>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <!-- tag column 1 -->
                    <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableDocument_dis" width="100%" cellspacing="0" role="grid">
                      <thead id="theadsum" style="font-size:24px;">
                        <tr role="row">
                          <th style='width: 3%;'>&nbsp;</th>
                          <th style='width: 10%;'><?php echo $array['docdate'][$language]; ?></th>
                          <th style='width: 15%;'><?php echo $array['docno'][$language]; ?></th>
                          <th style='width: 15%;'><?php echo $array['department'][$language]; ?></th>
                          <th style='width: 15%;'><?php echo $array['department_sub'][$language]; ?></th>
                          <th style='width: 13%;'><?php echo $array['employee'][$language]; ?></th>
                          <th style='width: 17%;'><?php echo $array['time'][$language]; ?></th>
                          <th style='width: 12%;'><?php echo $array['status'][$language]; ?></th>
                        </tr>
                      </thead>
                      <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:400px;">
                      </tbody>
                    </table>
                  </div> <!-- tag column 1 -->
                </div>

              </div> 

            </div>

            <!-- /#wrapper -->
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
              <i class="fas fa-angle-up"></i>
            </a>

            <!-- /#wrapper -->
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
              <i class="fas fa-angle-up"></i>
            </a>

            <!-- Dialog Modal-->
            <div id="dialogItemCode" title="<?php echo $array['import'][$language]; ?>"
              style="z-index:999998 !important;font-family: 'THSarabunNew';font-size:24px;">
              <div class="container">
                <div class="row">
                  <div class="col-md-10">
                    <div class="row">
                      <label><?php echo $array['searchplace'][$language]; ?></label>
                      <div class="row" style="font-size:16px;margin-left:20px;width:350px;">
                        <input type="text" class="form-control"
                          style="font-size:24px;width:100%;font-family: 'THSarabunNew'" name="searchitem"
                          id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                      </div>
                      <button type="button"
                        style="font-size:18px;margin-left:30px; width:100px;font-family: 'THSarabunNew'"
                        class="btn btn-primary" name="button"
                        onclick="ShowItem();"><?php echo $array['search'][$language]; ?></button>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <button type="button"
                      style="font-size:18px;margin-left:70px; width:100px;font-family: 'THSarabunNew'"
                      class="btn btn-warning" name="button"
                      onclick="getImport(1);"><?php echo $array['import'][$language]; ?></button>
                  </div>
                </div>

                <div class="dropdown-divider" style="margin-top:20px;; margin-bottom:20px;"></div>

                <div class="row">
                  <div class="card-body" style="padding:0px;">
                    <table class="table table-fixed table-condensed table-striped" id="TableItem" width="100%"
                      cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
                      <thead style="font-size:24px;">
                        <tr role="row">
                          <th style='width: 10%;'><?php echo $array['no'][$language]; ?></th>
                          <th style='width: 10%;'><?php echo $array['code'][$language]; ?></th>
                          <th style='width: 15%;'><?php echo $array['item'][$language]; ?></th>
                          <th style='width: 50%;'>
                            <center><?php echo $array['unit'][$language]; ?></center>
                          </th>
                          <th style='width: 15%;'><?php echo $array['numofpiece'][$language]; ?></th>
                        </tr>
                      </thead>
                      <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:300px;">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- Dialog Modal-->
            <div id="dialogUsageCode" title="<?php echo $array['import'][$language]; ?>"
              style="z-index:999999 !important;font-family: 'THSarabunNew';font-size:24px;">
              <div class="container">
                <div class="row">
                  <div class="col-md-10">
                    <!--
                              <div class="row">
                              <label><?php echo $array['searchplace'][$language]; ?></label>
                              <div class="row" style="font-size:16px;margin-left:20px;width:350px;">
                              <input type="text" class="form-control" style="font-size:24px;width:100%;font-family: 'THSarabunNew'" name="searchitem1" id="searchitem1" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
                            </div>
                            <button type="button" style="font-size:18px;margin-left:30px; width:100px;font-family: 'THSarabunNew'" class="btn btn-primary" name="button" onclick="ShowUsageCode();"><?php echo $array['search'][$language]; ?></button>
                          </div>
                        -->
                  </div>
                  <div class="col-md-1">
                    <button type="button"
                      style="font-size:18px;margin-left:70px; width:100px;font-family: 'THSarabunNew'"
                      class="btn btn-warning" name="button"
                      onclick="getImport(2);"><?php echo $array['import'][$language]; ?></button>
                  </div>
                </div>

                <div class="dropdown-divider" style="margin-top:20px;; margin-bottom:20px;"></div>

                <div class="row">
                  <div class="card-body" style="padding:0px;">
                    <table class="table table-fixed table-condensed table-striped" id="TableUsageCode" cellspacing="0"
                      role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
                      <thead style="font-size:24px;">
                        <tr role="row">
                          <th style='width: 10%;'><?php echo $array['no'][$language]; ?></th>
                          <th style='width: 20%;'><?php echo $array['rfid'][$language]; ?></th>
                          <th style='width: 40%;'><?php echo $array['item'][$language]; ?></th>
                          <th style='width: 15%;'><?php echo $array['unit'][$language]; ?></th>
                          <th style='width: 15%;'><?php echo $array['numofpiece'][$language]; ?></th>
                        </tr>
                      </thead>
                      <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:300px;">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

                <!-- bisburse Modal2 -->
            <div class="modal" id="disburseModal2">
              <div class="modal-dialog">
                <div class="modal-content">
                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title"><?php echo $array['disburse'][$language]; ?></h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <!-- Modal body -->
                  <div class="modal-body">
                    <div class="row">
                        <div class="form-group row col-md-6">
                          <label class='col-md-4 text-right'><?php echo $array['docdate'][$language]; ?></label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" style="font-size:24px;" disabled id="Docdate2" placeholder="<?php echo $array['docdate'][$language]; ?>">
                          </div>
                        </div>
                        <div class="form-group row col-md-6">
                          <label class='col-md-4 text-right'><?php echo $array['docno'][$language]; ?></label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" style="font-size:24px;width:220px;" disabled id="Docno2" placeholder="<?php echo $array['docno'][$language]; ?>">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group row col-md-6">
                          <label class='col-md-4 text-right'><?php echo $array['employee'][$language]; ?></label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" style="font-size:24px;" disabled id="Recorder2" placeholder="<?php echo $array['employee'][$language]; ?>">
                          </div>
                        </div>
                        <div class="form-group row col-md-6">
                          <label class='col-md-4 text-right'><?php echo $array['time'][$language]; ?></label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" style="font-size:24px;width:220px;" id="Timerec2" disabled  placeholder="<?php echo $array['time'][$language]; ?>">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group row col-md-6">
                          <label class='col-md-4 text-right'><?php echo $array['fromstock'][$language]; ?></label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" style="font-size:24px;" disabled id="dis_department2"  placeholder="<?php echo $array['fromstock'][$language]; ?>">
                          </div>
                        </div>
                        <div class="form-group row col-md-6">
                          <label class='col-md-4 text-right'><?php echo $array['department_sub'][$language]; ?></label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" style="font-size:24px;width:220px;" id="department_sub2" disabled  placeholder="<?php echo $array['department_sub'][$language]; ?>">
                          </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="card-body" style="padding:0px;">
                        <table class="table table-fixed table-condensed table-striped" id="table_disburse2"  cellspacing="0" role="grid" style="font-size:24px;font-family: 'THSarabunNew'">
                          <thead style="font-size:24px;">
                            <tr role="row">
                              <th style='width: 10%;'><?php echo $array['code'][$language]; ?></th>
                              <th style='width: 20%;'><?php echo $array['item'][$language]; ?></th>
                              <th style='width: 25%;'><?php echo $array['department'][$language]; ?></th>
                              <th style='width: 25%;'><?php echo $array['department_sub'][$language]; ?></th>
                              <th style='width: 20%;'><center><?php echo $array['getitem'][$language]; ?></center></th>
                            </tr>
                          </thead>
                          <tbody id="tbody1_disburse" class="nicescrolled" style="font-size:23px;height:100px;">
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button type="button" id='btn_getStock' class="btn btn-info" onclick='get_inStock()'>รับเข้าคลัง</button>
                  </div>

                </div>
              </div>
            </div>
            <!-- Bootstrap core JavaScript-->
            <script src="../template/vendor/jquery/jquery.min.js"></script>
            <script src="../template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="../template/vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Page level plugin JavaScript-->
            <script src="../template/vendor/datatables/jquery.dataTables.js"></script>
            <script src="../template/vendor/datatables/dataTables.bootstrap4.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="../template/js/sb-admin.min.js"></script>

            <!-- Demo scripts for this page-->
            <script src="../template/js/demo/datatables-demo.js"></script>

</body>

</html>