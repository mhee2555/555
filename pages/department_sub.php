<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
if ($Userid == "") {
  // header("location:../index.html");
}

$language = $_GET['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, true);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
        <?php echo $array['department'][$language]; ?>
    </title>

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

        $(document).ready(function(e) {
            OnLoadPage();
            getDepartment();
            Department2();
            //On create
            $('.TagImage').bind('click', {
                imgId: $(this).attr('id')
            }, function(evt) {
                alert(evt.imgId);
            });
            //On create
            // var userid = '<?php echo $Userid; ?>';
            // if(userid!="" && userid!=null && userid!=undefined){

            var HptCode = $('#hptsel').val();
            var Keyword = $('#searchitem').val();
            var data = {
                'STATUS': 'ShowItem',
                'HptCode': HptCode,
                'Keyword': Keyword
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
            // }


            $('#searchitem').keyup(function(e) {
                if (e.keyCode == 13) {
                    ShowItem();
                }
            });

            $('.editable').click(function() {
                alert('hi');
            });

            $('.numonly').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
            });
            $('.charonly').on('input', function() {
                this.value = this.value.replace(/[^a-zA-Zก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ. ]/g, ''); //<-- replace all other than given set of values
            });

        }).mousemove(function(e) { parent.last_move = new Date();;
        }).keyup(function(e) { parent.last_move = new Date();;
        });

        dialog = jqui("#dialog").dialog({
            autoOpen: false,
            height: 650,
            width: 1200,
            modal: true,
            buttons: {
                "<?php echo $array['close'][$language]; ?>": function() {
                    dialog.dialog("close");
                }
            },
            close: function() {
                console.log("close");
            }
        });

        jqui("#dialogreq").button().on("click", function() {
            dialog.dialog("open");
        });

        function OnLoadPage(){
            var data = {
                'STATUS'  : 'OnLoadPage'
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
            $('#isStatus').val(0)
        }
        function Department2(){
            var data = {
                'STATUS'  : 'Department2'
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
            $('#isStatus').val(0)
        }
        function getDepartment(){
            var Hotp = $('#hospital option:selected').attr("value");
            if( typeof Hotp == 'undefined' ) Hotp = "1";
            var data = {
                'STATUS'  : 'getDepartment',
                'Hotp'	: Hotp
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }
        function getDepartment2(){
            var Hotp = $('#hospital2 option:selected').attr("value");
            if( typeof Hotp == 'undefined' ) Hotp = "1";
            var data = {
                'STATUS'  : 'getDepartment2',
                'Hotp'	: Hotp
            };
            console.log(JSON.stringify(data));
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

        function getDocDetail() {
            // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
            if ($('input[name="checkdocno"]:checked').length == $('input[name="checkdocno"]').length) {
                $('input[name="checkAllDoc').prop('checked', true);
            } else {
                $('input[name="checkAllDoc').prop('checked', false);
            }

            /* declare an checkbox array */
            var chkArray = [];

            /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
            $("#checkdocno:checked").each(function() {
                chkArray.push($(this).val());
            });

            /* we join the array separated by the comma */
            var DocNo = chkArray.join(',');
            // alert( DocNo );
            $('#TableDetail tbody').empty();
            var dept = '<?php echo $_SESSION['Deptid ']; ?>';
            var data = {
                'STATUS': 'getDocDetail',
                'HptCode': HptCode,
                'DocNo': DepCode
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        var isChecked1 = false;
        var isChecked2 = false;

        function getCheckAll(sel) {
            if (sel == 0) {
                isChecked1 = !isChecked1;
                // $( "div #aa" )
                //   .text( "For this isChecked " + isChecked1 + "." )
                //   .css( "color", "red" );

                $('input[name="checkdocno"]').each(function() {
                    this.checked = isChecked1;
                });
                getDocDetail();
            } else {
                isChecked2 = !isChecked2;
                $('input[name="checkdocdetail"]').each(function() {
                    this.checked = isChecked2;
                });
            }
        }

        function getSearchDocNo() {
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';

            $('#TableDocumentSS tbody').empty();
            var str = $('#searchtxt').val();
            var datepicker = $('#datepicker').val();
            datepicker = datepicker.substring(6, 10) + "-" + datepicker.substring(3, 5) + "-" + datepicker.substring(0, 2);

            var data = {
                'STATUS': 'getSearchDocNo',
                'DEPT': dept,
                'DocNo': str,
                'Datepicker': datepicker
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function CreateSentSterile() {
            var userid = '<?php echo $Userid; ?>';
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';
            /* declare an checkbox array */
            var chkArray1 = [];

            /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
            $("#checkdocno:checked").each(function() {
                chkArray1.push($(this).val());
            });

            /* we join the array separated by the comma */
            var DocNo = chkArray1.join(',');

            /* declare an checkbox array */
            var chkArray2 = [];

            /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
            $("#checkdocdetail:checked").each(function() {
                chkArray2.push($(this).val());
            });

            /* we join the array separated by the comma */
            var UsageCode = chkArray2.join(',');
            var data = {
                'STATUS': 'CreateSentSterile',
                'DEPT': dept,
                'DocNo': DocNo,
                'UsageCode': UsageCode,
                'userid': userid
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function setTag() {
            var DocNo = $("#docnofield").val();
            /* declare an checkbox array */
            var chkArray = [];

            /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
            $("#IsTag:checked").each(function() {
                chkArray.push($(this).val());
            });

            /* we join the array separated by the comma */
            var UsageCode = chkArray.join(',');
            var userid = '<?php echo $Userid; ?>';
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';
            var data = {
                'STATUS': 'SSDTag',
                'DEPT': dept,
                'userid': userid,
                'DocNo': DocNo,
                'UsageCode': UsageCode
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function CreatePayout() {
            var userid = '<?php echo $Userid; ?>';
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';
            var data = {
                'STATUS': 'CreatePayout',
                'DEPT': dept,
                'userid': userid
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function AddPayoutDetail() {
            var userid = '<?php echo $Userid; ?>';
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';
            var data = {
                'STATUS': 'CreatePayout',
                'DEPT': dept,
                'userid': userid
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function ShowItem() {
            var HptCode = $('#hospital').val();
            var DepCode = $('#department').val();
            var keyword = $('#searchitem').val();
            var data = {
                'STATUS': 'ShowItem',
                'HptCode': HptCode,
                'DepCode': DepCode,
                'Keyword': keyword
            };
            // alert(DepCode);

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function AddItem() {
            var count = 0;
            $(".checkblank").each(function() {
                if ($(this).val() == "" || $(this).val() == undefined) {
                    count++;
                }
            });
            console.log(count);

            var HptCode = $('#hospital2').val();
            var DepCode = $('#department2').val();
            var depSubName = $('#depSubName').val();
            var depSubCode = $('#depSubCode').val();

            if (count == 0) {
                $('.checkblank').each(function() {
                    if ($(this).val() == "" || $(this).val() == undefined) {
                        $(this).css('border-color', 'red');
                    } else {
                        $(this).css('border-color', '');
                    }
                });
                if (depSubCode == "") {
                    swal({
                        title: "<?php echo $array['adddata'][$language]; ?>",
                        text: "<?php echo $array['adddata1'][$language]; ?>",
                        type: "question",
                        showCancelButton: true,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "<?php echo $array['add'][$language]; ?>",
                        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                        confirmButtonColor: '#6fc864',
                        cancelButtonColor: '#3085d6',
                        closeOnConfirm: false,
                        closeOnCancel: false,
                        showCancelButton: true
                    }).then(result => {
                        var data = {
                            'STATUS': 'AddItem',
                            'HptCode': HptCode,
                            'DepCode': DepCode,
                            'depSubName': depSubName
                        };
                        console.log(JSON.stringify(data));
                        senddata(JSON.stringify(data));
                    })
                } else {
                    swal({
                        title: "<?php echo $array['editdata'][$language]; ?>",
                        text: "<?php echo $array['editdata1'][$language]; ?>",
                        type: "question",
                        showCancelButton: true,
                        confirmButtonClass: "btn-warning",
                        confirmButtonText: "<?php echo $array['edit'][$language]; ?>",
                        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                        confirmButtonColor: '#6fc864',
                        cancelButtonColor: '#3085d6',
                        closeOnConfirm: false,
                        closeOnCancel: false,
                        showCancelButton: true
                    }).then(result => {
                        var data = {
                            'STATUS': 'EditItem',
                            'HptCode': HptCode,
                            'DepCode': DepCode,
                            'depSubName': depSubName,
                            'depSubCode': depSubCode
                        };
                        console.log(JSON.stringify(data));
                        senddata(JSON.stringify(data));
                    })

                }
            } else {
                swal({
                    title: '',
                    text: "<?php echo $array['required'][$language]; ?>",
                    type: 'info',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    showConfirmButton: false,
                    timer: 2000,
                    confirmButtonText: 'Ok'
                }).catch(function(timeout) { });
                    setTimeout(function () {
                        $('.checkblank').focus();
                    }, 2000);
                $('.checkblank').each(function() {
                    if ($(this).val() == "" || $(this).val() == undefined) {
                        $(this).css('border-color', 'red');
                    } else {
                        $(this).css('border-color', '');
                    }
                });
            }
        }

        function CancelItem() {
            swal({
                title: "<?php echo $array['canceldata'][$language]; ?>",
                text: "<?php echo $array['canceldata1'][$language]; ?>",
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
                var depSubCode = $('#depSubCode').val();
                var data = {
                    'STATUS': 'CancelItem',
                    'DepSubCode': depSubCode
                }
                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            })
        }

        function getdetail(DepSubCode) {
            if (DepSubCode != "" && DepSubCode != undefined) {
                var data = {
                    'STATUS': 'getdetail',
                    'DepSubCode': DepSubCode
                };
                // alert(DepSubCode);
                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }
        }

        function SavePY() {
            $('#TableDocumentSS tbody').empty();
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';
            var datepicker = $('#datepicker').val();
            datepicker = datepicker.substring(6, 10) + "-" + datepicker.substring(3, 5) + "-" + datepicker.substring(0, 2);

            var DocNo = $("#docno").val();
            $("#searchtxt").val(DocNo);

            if (DocNo.length > 0) {
                swal({
                    title: '<?php echo $array['
                    savesuccess '][$language]; ?>',
                    text: DocNo,
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    showConfirmButton: false,
                    timer: 2000,
                    confirmButtonText: 'Ok'
                })
                var data = {
                    'STATUS': 'SavePY',
                    'DocNo': DocNo,
                    'DEPT': dept,
                    'Datepicker': datepicker
                };

                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }
        }

        function DelItem() {
            var DocNo = $("#docno").val();
            /* declare an checkbox array */
            var chkArray = [];
            /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
            $("#checkitemdetail:checked").each(function() {
                chkArray.push($(this).val());
            });

            /* we join the array separated by the comma */
            var UsageCode = chkArray.join(',');

            // alert(DocNo + " : " + UsageCode);
            var data = {
                'STATUS': 'DelItem',
                'DocNo': DocNo,
                'UsageCode': UsageCode
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function canceldocno(docno) {
            swal({
                title: "<?php echo $array['canceldata'][$language]; ?>",
                text: "<?php echo $array['canceldata2'][$language]; ?>" + docno + "<?php echo $array['canceldata3'][$language]; ?>",
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
                    'STATUS': 'CancelDocNo',
                    'DocNo': docno
                };

                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
                getSearchDocNo();
            })
        }

        function addnum(cnt) {
            var add = parseInt($('#qty' + cnt).val()) + 1;
            if ((add >= 0) && (add <= 500)) {
                $('#qty' + cnt).val(add);
            }
        }

        function subtractnum(cnt) {
            var sub = parseInt($('#qty' + cnt).val()) - 1;
            if ((sub >= 0) && (sub <= 500)) {
                $('#qty' + cnt).val(sub);
            }
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
                confirmButtonText: 'Ok'
            }).then(function() {
                window.location.href = "../logoff.php";
            }, function(dismiss) {
                window.location.href = "../logoff.php";
                if (dismiss === 'cancel') {

                }
            })
        }

        function Blankinput() {
            $('.checkblank').each(function() {
                $(this).val("");
                $('.checkblank').prop('checked', false);
            });
            $('#hospital2').val("1");
            $('#department2').val("1");
            $('#depSubName').val("");
            $('#depSubCode').val("");depSubCode
            Department2();
            OnLoadPage();
        }

        function senddata(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = '../process/department_sub.php';
            $.ajax({
                url: URL,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                beforeSend: function() {
                    swal({
                        title: '<?php echo $array['
                        pleasewait '][$language]; ?>',
                        text: '<?php echo $array['
                        processing '][$language]; ?>',
                        allowOutsideClick: false
                    })
                    swal.showLoading();
                },
                success: function(result) {
                    try {
                        var temp = $.parseJSON(result);
                    } catch (e) {
                        console.log('Error#542-decode error');
                    }
                    swal.close();
                    // console.log(temp["form"]);
                    if (temp["status"] == 'success') {
                        if ((temp["form"] == 'ShowItem')) {
                            $("#TableItem tbody").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableItem >tbody >tr').length;
                                var chkDoc = "<input type='radio' name='checkitem' id='checkitem' class='checkblank' value='" + temp[i]['DepSubCode'] + "' onclick='getdetail(\"" + temp[i]["DepSubCode"] + "\")'>";
                                // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                                StrTR = "<tr id='tr" + temp[i]['DepSubCode'] + "'>" +
                                    "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                    "<td style='width: 10%;'>" + (i + 1) + "</td>" +
                                    "<td style='width: 15%;'>" + temp[i]['DepSubCode'] + "</td>" +
                                    "<td style='width: 40%;'>" + temp[i]['DepName'] + "</td>" +
                                    "<td style='width: 25%;'>" + temp[i]['DepSubName'] + "</td>" +
                                    "</tr>";
                                if (rowCount == 0) {
                                    $("#TableItem tbody").append(StrTR);
                                } else {
                                    $('#TableItem tbody:last-child').append(StrTR);
                                }
                            }
                        } else if ((temp["form"] == 'getdetail')) {
                            if ((Object.keys(temp).length - 2) > 0) {
                                console.log(temp);
                                // $('#hospital2').val(temp['HptCode']);
                                $('#depSubName').val(temp['DepSubName']);
                                $('#depSubCode').val(temp['DepSubCode']);
                                var HptCode = temp['HptCode'];
                                var DepCode = temp['DepCode'];
                                var StrTr="";
                                $("#hospital2").empty();
                                for (var i = 0; i < temp['HptCnt']; i++) {
                                    if(temp['Hpt'+i]['HptCode']==HptCode){
                                        StrTr = "<option selected value = '" + temp['Hpt'+i]['HptCode'] + "'> " + temp['Hpt'+i]['HptName'] + " </option>";
                                    }else{
                                        StrTr = "<option value = '" + temp['Hpt'+i]['HptCode'] + "'> " + temp['Hpt'+i]['HptName'] + " </option>";
                                    }
                                    $("#hospital2").append(StrTr);
                                }
                                $("#department2").empty();
                                for (var i = 0; i < temp['DepCnt']; i++) {
                                    if(temp['Dep'+i]['DepCode']==DepCode){
                                        StrTr = "<option selected value = '" + temp['Dep'+i]['DepCode'] + "'> " + temp['Dep'+i]['DepName'] + " </option>";
                                    }else{
                                      StrTr = "<option value = '" + temp['Dep'+i]['DepCode'] + "'> " + temp['Dep'+i]['DepName'] + " </option>";
                                    }
                                    $("#department2").append(StrTr);
                                }
                            }
                        } else if ((temp["form"] == 'AddItem')) {
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
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 2000,
                                confirmButtonText: 'Ok'
                            }).then(function() {

                            }, function(dismiss) {
                                $('.checkblank').each(function() {
                                    $(this).val("");
                                });

                                // $('#DepCode').val("");
                                // $('#hptsel2').val("1");
                                ShowItem();
                            })
                        } else if ((temp["form"] == 'EditItem')) {
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
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 2000,
                                confirmButtonText: 'Ok'
                            }).then(function() {
                                
                            }, function(dismiss) {
                                $('.checkblank').each(function() {
                                    $(this).val("");
                                });

                                $('#depSubCode').val("");
                                $('#hospital').val("1");
                                $('#department2').val("1");
                                ShowItem();
                            })
                        } else if ((temp["form"] == 'CancelItem')) {
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
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 2000,
                                confirmButtonText: 'Ok'
                            }).then(function() {
                                
                            }, function(dismiss) {
                                $('.checkblank').each(function() {
                                    $(this).val("");
                                });

                                // $('#DepCode').val("");
                                // $('#hospital').val("1");
                                ShowItem();
                            })
                        } else if (temp["form"]=='OnLoadPage'){
							for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                                var Str = "<option value="+temp[i]['HptCode']+">"+temp[i]['HptName']+"</option>";
                                $("#hospital").append(Str);
                                $("#hospital2").append(Str);
						    }
						} else if(temp["form"]=='getDepartment'){
                            $("#department").empty();
							for (var i = 0; i < (Object.keys(temp).length-2); i++) {
							    var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
								$("#department").append(Str);
                            }
                        } else if(temp["form"]=='getDepartment2'){
                            $("#department2").empty();
							for (var i = 0; i < (Object.keys(temp).length-2); i++) {
							    var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
								$("#department2").append(Str);
                            }
                        }else if(temp["form"]=='Department2'){
							for (var i = 0; i < (Object.keys(temp).length-2); i++) {
							    var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
								$("#department2").append(Str);
                            }
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
                            confirmButtonText: 'Ok'
                        })

                    } else if (temp['status'] == "notfound") {
                        // swal({
                        //   title: '',
                        //   text: temp['msg'],
                        //   type: 'info',
                        //   showCancelButton: false,
                        //   confirmButtonColor: '#3085d6',
                        //   cancelButtonColor: '#d33',
                        //   showConfirmButton: false,
                        //   timer: 2000,
                        //   confirmButtonText: 'Ok'
                        // })
                        $("#TableItem tbody").empty();
                    }
                },
                failure: function(result) {
                    alert(result);
                },
                error: function(xhr, status, p3, p4) {
                    var err = "Error " + " " + status + " " + p3 + " " + p4;
                    if (xhr.responseText && xhr.responseText[0] == "{")
                        err = JSON.parse(xhr.responseText).Message;
                    console.log(err);
                }
            });
        }
    </script>
    <style media="screen">
        body{
		   font-family: 'THSarabunNew';
		   font-size:22px;
		}
        input,select{
        font-size:24px!important;
        }
        th,td{
        font-size:24px!important;
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
        button{
        font-size: 24px!important;
        }
        a.nav-link{
            width:auto!important;
        }
        .datepicker{z-index:9999 !important}
        .hidden{visibility: hidden;}
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- content-wrapper -->
        <div id="content-wrapper">
            <!--
          <div class="mycheckbox">
            <input type="checkbox" name='useful' id='useful' onclick='setTag()'/><label for='useful' style='color:#FFFFFF'> </label>
          </div>
-->

            <div class="row">
                <div class="col-md-12">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                        <div class="card-body" style="padding:0px; margin-top:-12px;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row" style="margin-left:5px;">
                                        <select class="form-control" id="hospital" onchange="getDepartment();">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row" style="margin-left:5px;">
                                        <select class="form-control" id="department" >
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row" style="margin-left:5px;">
                                        <input type="text" class="form-control" style="width:70%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                                        <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowItem();">
                                            <?php echo $array['search'][$language]; ?></button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                                <thead id="theadsum" style="font-size:11px;">
                                    <tr role="row">
                                        <th style='width: 5%;'>&nbsp;</th>
                                        <th style='width: 10%;'>
                                            <?php echo $array['no'][$language]; ?>
                                        </th>
                                        <th style='width: 15%;'>
                                            <?php echo $array['codecode'][$language]; ?>
                                        </th>
                                        <th style='width: 40%;'>
                                            <?php echo $array['department'][$language]; ?>
                                        </th>
                                        <th style='width: 30%; text-align: center;'>
                                            <?php echo $array['department_sub'][$language]; ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:250px;">
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- tag column 1 -->
            </div>
            <!-- /.content-wrapper -->
            <div class="row">
                <div class="col-md-8">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                        <div class="card-body" style="padding:0px; margin-top:10px;">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                        <?php echo $array['detail'][$language]; ?></a>
                                </li>
                            </ul>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['hospital'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" id="hospital2" onchange="getDepartment2();">
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['department'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" id="department2">
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['department_sub'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <input type="text" class="form-control checkblank" style="width:100%;" name="depSubName" id="depSubName" placeholder="<?php echo $array['department_name'][$language]; ?>">
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['codecode'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-left:15px;">
                                    <div class="row">
                                        <input type="text" class="form-control" style="width:95%;" name="depSubCode" id="depSubCode" placeholder="<?php echo $array['codecode'][$language]; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- tag column 2 -->
                <div class="col-md-4">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                        <div class="card-body" style="padding:0px; margin-top:50px;">
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <div class="row" style="margin-left:5px;">
                                        <div class="row" style="margin-left:30px;">
                                            <button style="width:150px" ; type="button" class="btn btn-success" onclick="AddItem()">
                                                <?php echo $array['save'][$language]; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <div class="row" style="margin-left:5px;">
                                        <div class="row" style="margin-left:30px;">
                                            <button style="width:150px" ; type="button" class="btn btn-info" onclick="Blankinput()">
                                                <?php echo $array['clear'][$language]; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <div class="row" style="margin-left:5px;">
                                        <div class="row" style="margin-left:30px;">
                                            <button style="width:150px" ; type="button" class="btn btn-danger" onclick="CancelItem()">
                                                <?php echo $array['cancel'][$language]; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- tag column 1 -->
            </div>


            <!-- /#wrapper -->
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>


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