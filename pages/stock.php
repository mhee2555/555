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

  <title><?php echo $array['inventory'][$language]; ?></title>

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

  $(document).ready(function(e){
    OnLoadPage();
    getDepartment();
  }).mousemove(function(e) { parent.last_move = new Date();;
  }).keyup(function(e) { parent.last_move = new Date();;
  });

  jqui(document).ready(function($){

    dialog = jqui( "#dialog" ).dialog({
      autoOpen: false,
      height: 650,
      width: 1200,
      modal: true,
      buttons: {
        "ปิด": function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        console.log("close");
      }
    });

    jqui( "#dialogItem" ).button().on( "click", function() {
      dialog.dialog( "open" );
    });

  });

  function OpenDialogItem(){
    var docno = $("#docno").val();
    if( docno != "" ) dialog.dialog( "open" );
  }

  //======= On create =======
  //console.log(JSON.stringify(data));
  function OnLoadPage(){
    var data = {
      'STATUS'  : 'OnLoadPage'
    };
    senddata(JSON.stringify(data));
    $('#isStatus').val(0)
  }

  function getDepartment(){
    var Hotp = $('#hotpital option:selected').attr("value");
    if( typeof Hotp == 'undefined' ) Hotp = "1";
    var data = {
      'STATUS'  : 'getDepartment',
      'Hotp'	: Hotp
    };
    senddata(JSON.stringify(data));
  }

  function ShowDocument(selecta){
    var hos = $('#hotpital').val();
    var dept = $('#department').val();
    var search = $('#searchtxt').val();
    if( typeof deptCode == 'undefined' ) deptCode = "1";
    var data = {
      'STATUS'  	: 'ShowDocument',
      'dept'	: dept,
      'hos'	: hos,
      'selecta' : selecta,
      'search'	: search
    };
    console.log(JSON.stringify(data));
    senddata(JSON.stringify(data));
  }

  function logoff() {
    swal({
      title: '',
      text: '<?php echo $array['logout'][$language]; ?>',
      type: 'success',
      showCancelButton: false,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      showConfirmButton: false,
      timer: 1000,
      confirmButtonText: 'Ok'
    }).then(function () {
      window.location.href="../logoff.php";
    }, function (dismiss) {
      window.location.href="../logoff.php";
      if (dismiss === 'cancel') {

      }
    })
  }
  function selectTotalQty(rowid, selectObject) {
      var ItemCode = $('#item_'+rowid).data('value');
      var DepSubCode = selectObject.value;
      var RowId = rowid;
      // alert(ItemCode);
      // console.log('ItemCode = '+ItemCode);
      // console.log('DepSubCode ='+DepSubCode);
      // console.log('RowId ='+RowId);
      var data = {
        'STATUS': 'selectTotalQty',
        'ItemCode': ItemCode,
        'DepSubCode': DepSubCode,
        'RowId': RowId
      };

      senddata(JSON.stringify(data));
  }
  <!--=================================================-->
  function show_li(rowID){
    var row = rowID;
    $('#show_li'+row).attr('hidden', true);
    $('#hide_li'+row).removeAttr('hidden');
    $('#li_show'+row).removeAttr('hidden');
    $('#li_qty'+row).removeAttr('hidden');
  }
  function hide_li(rowID){
    var row = rowID;
    $('#hide_li'+row).attr('hidden', true);
    $('#li_show'+row).attr('hidden', true);
    $('#li_qty'+row).attr('hidden', true);
    $('#show_li'+row).removeAttr('hidden');
  }
  <!--=================================================-->
  function senddata(data){
    var form_data = new FormData();
    form_data.append("DATA",data);
    var URL = '../process/stock.php';
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
        if(temp["status"]=='success'){
          if(temp["form"]=='OnLoadPage'){
            for (var i = 0; i < (Object.keys(temp).length-2); i++) {
              var Str = "<option value="+temp[i]['HptCode']+">"+temp[i]['HptName']+"</option>";
              $("#hotpital").append(Str);
            }
          }else if(temp["form"]=='getDepartment'){
            $("#department").empty();
            $("#Dep2").empty();
            for (var i = 0; i < (Object.keys(temp).length-2); i++) {
              var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
              $("#department").append(Str);
              $("#Dep2").append(Str);
            }
          }else if(temp["form"]=='ShowDocument'){
            $( "#TableDocument tbody" ).empty();
            var st1 = "style='font-size:18px;margin-left:20px; width:160px;font-family:THSarabunNew'";

            for (var i = 0; i < (Object.keys(temp).length-2); i++) {

              var rowCount = $('#TableDocument >tbody >tr').length;
              var btn_li = "<a href='javascript:void(0)' id='show_li"+i+"'><h3><span class='badge badge-info' onclick='show_li("+i+")'>แสดง</span></h3></a>";
              var btn_hide = "<a href='javascript:void(0)'><h3><span class='badge badge-warning text-light' onclick='hide_li("+i+")'>ซ่อน</span></h3></a>";

              var depSub = "<ol id='li_show"+i+"' hidden style='list-style-type:none;'>";
              var Qty = "<ol id='li_qty"+i+"' hidden style='list-style-type:none;'>";

              for(var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++){
                  depSub+="<li>"+temp['DepSubName_' + temp[i]['ItemCode'] + '_' + i][j] +"</li>";
                  if(temp['Qty_' + temp[i]['ItemCode'] + '_' + i][j]==null || temp['Qty_' + temp[i]['ItemCode'] + '_' + i][j]==undefined || temp['Qty_' + temp[i]['ItemCode'] + '_' + i][j] == ''){
                    Qty+="<li>0</li>";
                  }else{
                    Qty+="<li>"+temp['Qty_' + temp[i]['ItemCode'] + '_' + i][j] +"</li>";
                  }
              }
              depSub += "<ol>";
              Qty += "<ol>";
         

              StrTr="<tr id='tr"+i+"'>"+
              "<td style='width: 5%;'>"+(i+1)+"</td>"+
              "<td style='width: 10%;' data-value='"+temp[i]['ItemCode']+"' id='item_"+i+"'>"+temp[i]['ItemCode']+"</td>"+
              "<td style='width: 15%;'>"+temp[i]['ItemName']+"</td>"+
              "<td style='width: 20%;'>"+temp[i]['CategoryName']+"</td>"+
              "<td style='width: 10%;'><center>"+temp[i]['DepName']+"</center></td>"+
              "<td style='width: 15%;'><center>"+temp[i]['total']+"</center></td>"+
              "<td style='width: 13%;'><center>"+btn_li+depSub+"</center><div id='hide_li"+i+"' hidden class='text-center'>"+btn_hide+"</div></td>"+
              "<td style='width: 12%;' class='text-right'><center>"+Qty+"</center></td>"+
              "</tr>";

              if(rowCount == 0){
                $("#TableDocument tbody").append( StrTr );
              }else{
                $('#TableDocument tbody:last-child').append(  StrTr );
              }
            }
          }else if(temp["form"]=='selectTotalQty'){
            $('#total_'+temp['RowId']).text(temp['total']);
          }
        }else if (temp['status']=="failed") {
            $( "#TableDocument tbody" ).empty();
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
            confirmButtonText: 'Ok'
          })

          $( "#docnofield" ).val( temp[0]['DocNo'] );
          $( "#TableDocumentSS tbody" ).empty();
          $( "#TableSendSterileDetail tbody" ).empty();

        }else{
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

  body{
    font-family: 'THSarabunNew';
    font-size:22px;
  }

  .nfont{
    font-family: 'THSarabunNew';
    font-size:22px;
  }

  button,input[id^='qty'] {
    font-size: 24px!important;
  }
  .table > thead > tr >th {
    background: #4f88e3!important;
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
  a.nav-link{
    width:auto!important;
  }
  .datepicker{z-index:9999 !important}
  .hidden{visibility: hidden;}
  </style>
</head>

<body id="page-top">
  <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>

  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">

      <div class="row" style="margin-top:-15px;"> <!-- start row tab -->
        <div class="col-md-12"> <!-- tag column 1 -->
          <!-- /.content-wrapper -->
          <div class="row">
            <div class="col-md-11"> <!-- tag column 1 -->
              <div class="container-fluid">
                <div class="card-body" style="padding:0px; margin-top:10px;">
                  <div class="row">
                    <div style="margin-left:20px;width:80px;">
                      <label><?php echo $array['hospital'][$language]; ?></label>
                    </div>
                    <div style="width:220px;">
                      <div class="row" style="font-size:24px;margin-left:2px;">
                        <select style='font-size:24px;width:220px;' class="form-control" id="hotpital" onchange="getDepartment();" disabled>
                        </select>
                      </div>
                    </div>
                    <div style="margin-left:30px;margin-right:25px;">
                      <label><?php echo $array['department'][$language]; ?></label>
                    </div>
                    <div style="width:220px;">
                      <div class="row" style="font-size:24px;margin-left:2px;">
                        <select style='font-size:24px;width:220px;' class="form-control" id="department">

                        </select>
                      </div>
                    </div>
                    <div style="width:260px;">
                      <div class="row" style="font-size:24px;margin-left:50px;">
                        <input type="text" style="font-size:24px;" class="form-control" name="searchtxt" id="searchtxt" value="" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                      </div>
                    </div>
                    <div style="margin-left:30px;width:300px;">
                      <button  style="width:130px" type="button" class="btn btn-info" onclick="ShowDocument(0)" id="bSearch"><?php echo $array['searchdep'][$language]; ?></button>
                    </div>

                  </div>

                </div>
              </div>
            </div> <!-- tag column 1 -->
          </div>

          <div class="row">
            <div style='width: 98%;'> <!-- tag column 1 -->
              <table style="margin-top:10px;margin-left:15px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid" style="">
                <thead id="theadsum" style="font-size:24px;">
                  <tr role="row">
                    <th style='width: 5%;'><?php echo $array['no'][$language]; ?></th>
                    <th style='width: 10%;'><?php echo $array['code'][$language]; ?></th>
                    <th style='width: 15%;'><?php echo $array['item'][$language]; ?></th>
                    <th style='width: 20%;'><?php echo $array['category'][$language]; ?></th>
                    <th style='width: 10%;'><center><?php echo $array['department'][$language]; ?></center></th>
                    <th style='width: 15%;'><center><?php echo $array['total'][$language]; ?></center></th>
                    <th style='width: 13%;'><center><?php echo $array['department_sub'][$language]; ?></center></th>
                    <th style='width: 12%;'><center><?php echo $array['total'][$language]; ?></center></th>
                  </tr>
                </thead>
                <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:360px;">
                </tbody>
              </table>
            </div> <!-- tag column 1 -->
          </div>

        </div>
      </div> <!-- end row tab -->


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
