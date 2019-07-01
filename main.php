<?php
    session_start();
    $Userid = $_SESSION['Userid'];
    $PmID= $_SESSION['PmID'];
    $TimeOut = $_SESSION['TimeOut'];
    $last_move = $_GET["last_move"];

    if($Userid==""){
      header("location:index.html");
    }
    $language = $_GET['lang'];
    if($language=="en"){
      $language = "en";
    }else{
      $language = "th";
    }

    header ('Content-type: text/html; charset=utf-8');
    $xml = simplexml_load_file('xml/main_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);


    function getDatetimeNow() {
        date_default_timezone_set("Asia/Bangkok");
        $datetime = new DateTime();
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

    switch ($PmID) {
        case "1":
            //genneral
            $gen_head=1;
            $gen_s1=1;
            $gen_s2=1;
            $gen_s3=1;
            $gen_s4=1;
            $gen_s5=1;
            $gen_s6=1;
            $gen_s7=0;
            $gen_s8=1;
            $gen_s9=1;
            $gen_s10=1;
            //account
            $ac_head=0;
            $ac_s1=0;
            $ac_s2=0;
            $ac_s3=0;
            $ac_s4=0;
            $ac_s5=0;
            //factory
            $fac_head=0;
            $fac_s1=0;
            $fac_s2=0;
            //report
            $re_head=0;
            $re_s1=0;
            //system
            $sys_head=1;
            $sys_s1=1;
            $sys_s2=1;
            $sys_s3=1;
            $sys_s4=1;
            $sys_s5=1;
            $sys_s6=1;
            $sys_s7=1;
            $sys_s8=1;
            $sys_s8=1;
            $sys_s9=1;
            $sys_s10=1;
            $sys_s11=1;
            $sys_s12=1;
            $sys_s13=1;
            break;
        case "2":
            //genneral
            $gen_head=1;
            $gen_s1=1;
            $gen_s2=1;
            $gen_s3=1;
            $gen_s4=1;
            $gen_s5=1;
            $gen_s6=1;
            $gen_s7=0;
            $gen_s8=0;
            $gen_s9=1;
            $gen_s10=1;
            //account
            $ac_head=0;
            $ac_s1=0;
            $ac_s2=0;
            $ac_s3=0;
            $ac_s4=0;
            $ac_s5=0;
            //factory
            $fac_head=0;
            $fac_s1=0;
            $fac_s2=0;
            //report
            $re_s1=1;
            $re_head=1;
            //system
            $sys_head=1;
            $sys_s1=0;
            $sys_s2=0;
            $sys_s3=0;
            $sys_s4=0;
            $sys_s5=0;
            $sys_s6=0;
            $sys_s7=0;
            $sys_s8=1;
            $sys_s9=0;
            $sys_s10=0;
            $sys_s11=0;
            $sys_s12=0;
            $sys_s13=0;
            break;
        case "3":
            //genneral
            $gen_head=1;
            $gen_s1=1;
            $gen_s2=1;
            $gen_s3=1;
            $gen_s4=1;
            $gen_s5=1;
            $gen_s6=1;
            $gen_s7=1;
            $gen_s8=1;
            $gen_s9=1;
            $gen_s10=1;
            //account
            $ac_head=1;
            $ac_s1=1;
            $ac_s2=1;
            $ac_s3=1;
            $ac_s4=1;
            $ac_s5=1;
            //factory
            $fac_head=1;
            $fac_s1=1;
            $fac_s2=1;
            //report
            $re_head=1;
            $re_s1=1;
            //system
            $sys_head=1;
            $sys_s1=1;
            $sys_s2=1;
            $sys_s3=1;
            $sys_s4=1;
            $sys_s5=1;
            $sys_s6=1;
            $sys_s7=1;
            $sys_s8=1;
            $sys_s9=0;
            $sys_s10=0;
            $sys_s11=0;
            $sys_s12=0;
            $sys_s13=0;
            break;
        case "4":
            //genneral
            $gen_head=0;
            $gen_s1=0;
            $gen_s2=0;
            $gen_s3=0;
            $gen_s4=0;
            $gen_s5=0;
            $gen_s6=0;
            $gen_s7=0;
            $gen_s8=0;
            $gen_s9=1;
            $gen_s10=1;
            //account
            $ac_head=1;
            $ac_s1=0;
            $ac_s2=0;
            $ac_s3=0;
            $ac_s4=1;
            $ac_s5=0;
            //factory
            $fac_head=0;
            $fac_s1=0;
            $fac_s2=0;
            //report
            $re_head=0;
            $re_s1=0;
            //system
            $sys_head=1;
            $sys_s1=0;
            $sys_s2=0;
            $sys_s3=0;
            $sys_s4=0;
            $sys_s5=0;
            $sys_s6=0;
            $sys_s7=0;
            $sys_s8=1;
            $sys_s9=0;
            $sys_s10=0;
            $sys_s11=0;
            $sys_s12=0;
            $sys_s13=0;
            break;
        case "5":
            //genneral
            $gen_head=0;
            $gen_s1=0;
            $gen_s2=0;
            $gen_s3=0;
            $gen_s4=0;
            $gen_s5=0;
            $gen_s6=0;
            $gen_s7=0;
            $gen_s8=0;
            $gen_s9=1;
            $gen_s10=1;
            //account
            $ac_head=1;
            $ac_s1=0;
            $ac_s2=0;
            $ac_s3=0;
            $ac_s4=0;
            $ac_s5=1;
            //factory
            $fac_head=0;
            $fac_s1=0;
            $fac_s2=0;
            //report
            $re_head=0;
            $re_s1=0;
            //system
            $sys_head=1;
            $sys_s1=0;
            $sys_s2=0;
            $sys_s3=0;
            $sys_s4=0;
            $sys_s5=0;
            $sys_s6=0;
            $sys_s7=0;
            $sys_s8=1;
            $sys_s9=0;
            $sys_s10=0;
            $sys_s11=0;
            $sys_s12=0;
            $sys_s13=0;
            break;
        case "6":
            //genneral
            $gen_head=0;
            $gen_s1=0;
            $gen_s2=0;
            $gen_s3=0;
            $gen_s4=0;
            $gen_s5=0;
            $gen_s6=0;
            $gen_s7=0;
            $gen_s8=0;
            $gen_s9=1;
            $gen_s10=1;
            //account
            $ac_head=1;
            $ac_s1=1;
            $ac_s2=1;
            $ac_s3=1;
            $ac_s4=1;
            $ac_s5=1;
            //factory
            $fac_head=0;
            $fac_s1=0;
            $fac_s2=0;
            //report
            $re_head=0;
            $re_s1=0;
            //system
            $sys_head=1;
            $sys_s1=0;
            $sys_s2=0;
            $sys_s3=0;
            $sys_s4=0;
            $sys_s5=0;
            $sys_s6=0;
            $sys_s7=0;
            $sys_s8=1;
            $sys_s9=0;
            $sys_s10=0;
            $sys_s11=0;
            $sys_s12=0;
            $sys_s13=0;
            break;
    }

 ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Bootstrap core CSS -->

    <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" >
    <link href="css/accordionmenu.css" type="text/css" media="screen" rel="stylesheet" />
    <link href="bootstrap/css/tbody.css" rel="stylesheet">
    <link href="bootstrap/css/myinput.css" rel="stylesheet">
    <link href="dist/css/sweetalert2.min.css" rel="stylesheet">
    <link href="datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <link href="template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="template/css/sb-admin.css" rel="stylesheet">

    <script src="template/vendor/jquery/jquery.min.js"></script>

    <script type="text/javascript" src="js/functions.js"></script>
    <script src="dist/js/sweetalert2.min.js"></script>

    <script src="datepicker/dist/js/datepicker.min.js"></script>
    <script src="datepicker/dist/js/i18n/datepicker.en.js"></script>
    <title>H-stock</title>
    <script src="template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="template/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="template/vendor/datatables/jquery.dataTables.js"></script>
    <script src="template/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="template/js/sb-admin.min.js"></script>
    <script src="template/js/demo/datatables-demo.js"></script>

    <script type="text/javascript">
      var summary = [];
      var last_move, cur_date, target;
      var redirectInSecond=<?=$TimeOut?>;
      var redirect_url = 'http://localhost:8181/hstock/index.html'; // 'http://poseintelligence.dyndns.biz:8181/linen/index.html';

      $(document).ready(function(e) {
        OnLoadPage();
          $('.current_page').click(function() {
              $("a"). removeClass("active_li");
              $(this). attr("class", "active_li");
          });
          target = redirectInSecond * 1000; // แปลงค่าเป็น microsecond
          target = target * 60;
          last_move = new Date() // กำหนดค่าเริ่มต้นให้ last_move
          // $('#ShowTime').val(last_move);
          setTimeout( 'chk_last_move()', 1000 ); // กำหนดเวลาตรวจเช็คเริ่มต้น
      }).mousemove(function(e) { afk();
      }).keyup(function(e) { afk();
      });

      function afk() {
          last_move = new Date();
          if(redirectInSecond>=60)
              hms = "00:00:00";
          else
              hms = "00:00";
          $('#ShowTime').val( redirectInSecond + ' / TimeOut : ' + hms );
      }

      function chk_last_move(){
          cur_date = new Date(); // อ่านเวลาปัจจุบันไว้ใน cur_date
          if( cur_date>last_move){ // ตรวจสอบเวลา
              var micro = parseInt(cur_date.getTime() - last_move.getTime());
              var newDate = new Date();
              newDate.setTime((target - micro));
              var h = newDate.getHours();
              var m = newDate.getMinutes();
              var s = newDate.getSeconds();
              var hms = "";
              m = checkTime(m);
              s = checkTime(s);

              if(redirectInSecond>=60)
                  hms = h + ":" + m + ":" + s;
              else
                  hms = m + ":" + s;

              $('#ShowTime').val( 'TimeOut : ' + hms );

              if( micro > target ) location.href=redirect_url;
              else {
                  var new_time = target - micro;
                  setTimeout('chk_last_move()', 1000 ); //new_time
              }
          }else{
              setTimeout('chk_last_move()', 1000 );
          }
      }

      function checkTime(i) {
          if (i < 10) {
              i = "0" + i;
          }
          return i;
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
          window.location.href="index.html";
        }, function (dismiss) {
          window.location.href="index.html";
          if (dismiss === 'cancel') {

          }
        })
      }

      function showPopup(messageid,subject) {

        var mypicture = 'https://www.thaicreate.com/upload/icon-topic/communication.jpg';
        var titletext = 'You have new messages.';
        var bodytext = subject;
        var popup = window.webkitNotifications.createNotification(mypicture, titletext, bodytext);
        popup.show();
        jQuery(popup).css( 'cursor', 'pointer' );
        jQuery(popup).click(function(){
        window.location = "view.php?MessageID="+messageid;
        });
          setTimeout(function () {
          popup.cancel();
          }, '5000');
      }

      function switchlang(lang) {
        if(document.URL.indexOf('#')>=0){
          var url = document.URL.split("#");
          if(url[1]==""){
            window.location.href="main.php?lang="+lang;
          }else{
            window.location.href="main.php?lang="+lang+"#"+url[1];
          }
        }else{
          window.location.href="main.php?lang="+lang;
        }
      }

      <!-- =======================JAPCreate======================== -->
      function OnLoadPage() {
        var data = {
          'STATUS': 'OnLoadPage'
        };
        senddata(JSON.stringify(data));
      }
      function senddata(data) {
          var form_data = new FormData();
          form_data.append("DATA", data);
          var URL = 'process/main.php';
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
                $("#C_disburse").text(temp["C_disburse"]);
                $("#disburse_stockIn").text(temp["disburse_stockIn"]);
                var a1 = parseInt(temp["C_disburse"]);
                var a2 = parseInt(temp["disburse_stockIn"]);
                $("#main_Cnt").text(a1 + a2);
              }
            } else if (temp['status'] == "failed") {
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

              $("#docnofield").val(temp[0]['DocNo']);
              $("#TableDocumentSS tbody").empty();
              $("#TableSendSterileDetail tbody").empty();

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

    <style>
        @font-face {
          font-family: 'THSarabunNew';
          src: url('fonts/THSarabunNew.ttf');
        }

        @font-face {
          font-family: 'THSarabunB';
          src: url('fonts/THSarabunNew Bold.ttf');
        }

        @font-face {
          font-family: 'THSarabunI';
          src: url('fonts/THSarabunNew Italic.ttf');
        }

        @font-face {
          font-family: 'THSarabunBI';
          src: url('fonts/THSarabunNew BoldItalic.ttf');
        }

        body {
          display: grid;
          grid-template-areas:
          "header header header"
          "nav article ads"
          "footer footer footer";
          grid-template-rows: 35px 1fr 0px;
          grid-template-columns: 15% 1fr 0;
          grid-gap: 0px;
          height: 100vh;
          margin: 0;
        }
          #pageHeader {
            grid-area: header;
          }
          #pageFooter {
            grid-area: footer;
          }
          #mainArticle {
            grid-area: article;
            }
          #mainNav {
            grid-area: nav;
            }
          #siteAds {
            grid-area: ads;
            }
          header, footer, article, nav, div {
            padding: 10px;
            background:#FFF;
          }

          nav ul {
            list-style: none; overflow: hidden; position: relative;
          }

        .bluebg {
          background:#4f88e3!important;
          color:white!important;
        }
        .bluebg:hover {
          color:white!important;
        }

        #mainArticle {
          border: 2px solid #ececec;
          border-radius: 8px;
          margin-right: 10px;
          margin-bottom: 10px;
        }

        #navmenu {
          border: 2px solid #ececec;
          border-radius: 8px;
        }
      .sub-menu li a {
        color: #797979;
        text-shadow: 1px 1px 0px rgba(255,255,255, .2);
        background: #f2f2f2!important;
        border-bottom: 1px solid #c9c9c9;
        -webkit-box-shadow: inset 0px 1px 0px 0px rgba(255,255,255, .1), 0px 1px 0px 0px rgba(0,0,0, .1);
        -moz-box-shadow: inset 0px 1px 0px 0px rgba(255,255,255, .1), 0px 1px 0px 0px rgba(0,0,0, .1);
        box-shadow: inset 0px 1px 0px 0px rgba(255,255,255, .1), 0px 1px 0px 0px rgba(0,0,0, .1);
      }

      .active_li{
            color:#24246A!important;
            font-size: 24px!important;
            font-weight: bold!important;
        }
    </style>

<body>
  <header id="pageHeader" class="navbar navbar-expand static-top">
      <a class="navbar-brand mr-1" href="javascript:void(0)" style="color: black;">
        <img src="img/logo.png" style="width: 100px;margin-top:55px;margin-bottom:20px;" alt="">
      </a>
      <!-- Navbar Search -->
      <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        <div class='row' style='margin-left:2px;'>
          <input class='form-control' type="hidden" style="margin-top:20px;font-size:24px;width:800px;height:40px;padding-top: 5px;" id='ShowTime' >
        </div>
      </form>
      <!-- Navbar -->
      <ul class="navbar-nav ml-auto ml-md-0" >
        <div style="padding-top:15px;"><a href="#" onclick="switchlang('th');">TH</a> / <a href="#" onclick="switchlang('en');">EN</a></div>
        <li class="nav-item dropdown no-arrow" style="padding-top:12px;">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle fa-fw"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#" data-toggle="modal" onclick="logoff();"><?php echo $array['menu']['logout'][$language]; ?></a>
          </div>
        </li>
      </ul>
  </header>

  <article id="mainArticle" style="margin-top:25px;">
    <iframe name="ifrm" id="ifrm" src="pages/menu.php?lang=<?php echo $language; ?>" frameborder="0"
    style="height:100%; width:100%; "></iframe>
  </article>

  <nav id="mainNav" style="margin-top:25px;">
		<ul class="accordion" id="navmenu">
      <?php if($gen_head== 1){ ?>
        <li id="general">
          <a  class="bluebg" style="font-family: 'THSarabunNew'; font-size:20px;" href="#general"><?php echo $array['menu']['general']['title'][$language]; ?></a>
          <ul class="sub-menu">
            <?php if($gen_s2== 1){ ?>
              <li><a style="font-family: 'THSarabunNew'; font-size:20px;" href="pages/department.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)">
                <em></em><?php echo $array['menu']['system']['sub'][0][$language]; ?></a>
              </li>
            <?php } ?>
            <?php if($gen_s2== 1){ ?>
              <li><a style="font-family: 'THSarabunNew'; font-size:20px;" href="pages/department_sub.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)">
                <em></em><?php echo $array['menu']['system']['sub'][1][$language]; ?></a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if($sys_head== 1){ ?>
        <li id="system">
          <a class="bluebg" style="font-family: 'THSarabunNew'; font-size:20px;" href="#system"><?php echo $array['menu']['system']['title'][$language]; ?><span id='main_Cnt'>0</span></a>
          <ul class="sub-menu">
          <?php if($sys_s3== 1){ ?>
              <li><a style="font-family: 'THSarabunNew'; font-size:20px;" href="pages/stock.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)">
                <em></em><?php echo $array['menu']['system']['sub'][7][$language]; ?></a>
              </li>
            <?php } ?>
            <?php if($sys_s3== 1){ ?>
              <li><a style="font-family: 'THSarabunNew'; font-size:20px;" href="pages/item.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)">
                <em></em><?php echo $array['menu']['system']['sub'][4][$language]; ?></a>
              </li>
            <?php } ?>
            <?php if($sys_s3== 1){ ?>
              <li><a style="font-family: 'THSarabunNew'; font-size:20px;" href="pages/stock_in.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)">
                <em></em><?php echo $array['menu']['system']['sub'][2][$language]; ?><span id='disburse_stockIn'>0</span></a>
              </li>
            <?php } ?>
            <?php if($sys_s4== 1){ ?>
              <li><a style="font-family: 'THSarabunNew'; font-size:20px;" href="pages/stock_out.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)">
                <em></em><?php echo $array['menu']['system']['sub'][3][$language]; ?><span id='C_disburse'>0</span></a>
              </li>
            <?php } ?>
            <?php if($sys_s4== 1){ ?>
              <li><a style="font-family: 'THSarabunNew'; font-size:20px;" href="pages/stock_in_sub.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)">
                <em></em><?php echo $array['menu']['system']['sub'][5][$language]; ?></a>
              </li>
            <?php } ?>
            <?php if($sys_s4== 1){ ?>
              <li><a style="font-family: 'THSarabunNew'; font-size:20px;" href="pages/stock_out_sub.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)">
                <em></em><?php echo $array['menu']['system']['sub'][6][$language]; ?></a>
              </li>
            <?php } ?>
            <?php if($sys_s4== 1){ ?>
              <li><a style="font-family: 'THSarabunNew'; font-size:20px;" id='dis' href="pages/disburse.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)">
                <em></em><?php echo $array['menu']['system']['sub'][8][$language]; ?></a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>

		</ul>
  </nav>
  <!-- div id="siteAds">Ads</div -->
  <!-- footer id="pageFooter">Footer</footer -->
</body>
</html>
