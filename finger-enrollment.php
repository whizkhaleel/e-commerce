<?php
require('db.php');
session_start();

if(isset($_POST['enroll-finger'])){
    $admin=$_POST['admn'];
    $finger=$_POST['fingerdata'];
//studentid	student_name	admin_number	gender	department	fingerprint	datejoin
    //$sql="UPDATE student_tbl SET fingerprint=$finger WHERE admin_number=$admin";
	
	
	$sql2=mysqli_query ($connection,"UPDATE student_tbl SET fingerprint='$finger' WHERE admin_number='$admin'");
     if(!$sql2)
        {
            die("Query Failed" . mysqli_error($connection));
          }else{
          echo'Successfully Enrolled';
		  header("Location:admin-page.php");  
		  }
		  
	
  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

   <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">

<!-- jQuery library -->
<script src="js/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="js/bootstrap.min.js"></script>   
   
    <meta charset="UTF-8">
    <title>Student Monitoring System</title>
	<script language="javascript" type="text/javascript">

var flag =0;
var quality = 60; //(1 to 100) (recommanded minimum 55)
var timeout = 10; // seconds (minimum=10(recommanded), maximum=60, unlimited=0 )

//function to initialize the device

function GetInfo() {
    document.getElementById('tdSerial').innerHTML = "";
    document.getElementById('tdCertification').innerHTML = "";
    document.getElementById('tdMake').innerHTML = "";
    document.getElementById('tdModel').innerHTML = "";
    document.getElementById('tdWidth').innerHTML = "";
    document.getElementById('tdHeight').innerHTML = "";
    document.getElementById('tdLocalMac').innerHTML = "";
    document.getElementById('tdLocalIP').innerHTML = "";
    document.getElementById('tdSystemID').innerHTML = "";
    document.getElementById('tdPublicIP').innerHTML = "";


    var key = document.getElementById('txtKey').value;

    var res;
    if (key.length == 0) {
        res = GetMFS100Info();
    }
    else {
        res = GetMFS100KeyInfo(key);
    }

    if (res.httpStaus) {

        document.getElementById('txtStatus').value = "ErrorCode: " + res.data.ErrorCode + " ErrorDescription: " + res.data.ErrorDescription;

        if (res.data.ErrorCode == "0") {
            document.getElementById('tdSerial').innerHTML = res.data.DeviceInfo.SerialNo;
            document.getElementById('tdCertification').innerHTML = res.data.DeviceInfo.Certificate;
            document.getElementById('tdMake').innerHTML = res.data.DeviceInfo.Make;
            document.getElementById('tdModel').innerHTML = res.data.DeviceInfo.Model;
            document.getElementById('tdWidth').innerHTML = res.data.DeviceInfo.Width;
            document.getElementById('tdHeight').innerHTML = res.data.DeviceInfo.Height;
            document.getElementById('tdLocalMac').innerHTML = res.data.DeviceInfo.LocalMac;
            document.getElementById('tdLocalIP').innerHTML = res.data.DeviceInfo.LocalIP;
            document.getElementById('tdSystemID').innerHTML = res.data.DeviceInfo.SystemID;
            document.getElementById('tdPublicIP').innerHTML = res.data.DeviceInfo.PublicIP;
        }
    }
    else {
        alert(res.err);
    }
    return false;
}
//function to capture the finger prints. 

function Capture() {
    try {
        document.getElementById('txtStatus').value = "";
        document.getElementById('imgFinger').src = "data:image/bmp;base64,";
        document.getElementById('txtImageInfo').value = "";
        document.getElementById('txtIsoTemplate').value = "";
        document.getElementById('txtAnsiTemplate').value = "";
        document.getElementById('txtIsoImage').value = "";
        document.getElementById('txtRawData').value = "";
        document.getElementById('txtWsqData').value = "";

        var res = CaptureFinger(quality, timeout);
        if (res.httpStaus) {
              flag = 1;
            document.getElementById('txtStatus').value = "ErrorCode: " + res.data.ErrorCode + " ErrorDescription: " + res.data.ErrorDescription;

            if (res.data.ErrorCode == "0") {
                document.getElementById('imgFinger').src = "data:image/bmp;base64," + res.data.BitmapData;
                var imageinfo = "Quality: " + res.data.Quality + " Nfiq: " + res.data.Nfiq + " W(in): " + res.data.InWidth + " H(in): " + res.data.InHeight + " area(in): " + res.data.InArea + " Resolution: " + res.data.Resolution + " GrayScale: " + res.data.GrayScale + " Bpp: " + res.data.Bpp + " WSQCompressRatio: " + res.data.WSQCompressRatio + " WSQInfo: " + res.data.WSQInfo;
                document.getElementById('txtImageInfo').value = imageinfo;
                document.getElementById('txtIsoTemplate').value = res.data.IsoTemplate;
                document.getElementById('txtAnsiTemplate').value = res.data.AnsiTemplate;
                document.getElementById('txtIsoImage').value = res.data.IsoImage;
                document.getElementById('txtRawData').value = res.data.RawData;
                document.getElementById('txtWsqData').value = res.data.WsqImage;
            }
        }
        else {
            alert(res.err);
        }
    }
    catch (e) {
        alert(e);
    }
    return false;
}

function Verify() {
    try {
        var isotemplate = document.getElementById('txtIsoTemplate').value;
        var res = VerifyFinger(isotemplate, isotemplate);

        if (res.httpStaus) {
            if (res.data.Status) {
                alert("Finger matched");
            }
            else {
                if (res.data.ErrorCode != "0") {
                    alert(res.data.ErrorDescription);
                }
                else {
                    alert("Finger not matched");
                }
            }
        }
        else {
            alert(res.err);
        }
    }
    catch (e) {
        alert(e);
    }
    return false;

}

function Match() {
    try {
        var isotemplate = document.getElementById('txtIsoTemplate').value;
        var res = MatchFinger(quality, timeout, isotemplate);

        if (res.httpStaus) {
            if (res.data.Status) {
                alert("Finger matched");
            }
            else {
                if (res.data.ErrorCode != "0") {
                    alert(res.data.ErrorDescription);
                }
                else {
                    alert("Finger not matched");
                }
            }
        }
        else {
            alert(res.err);
        }
    }
    catch (e) {
        alert(e);
    }
    return false;

}

function GetPid() {
    try {
        var isoTemplateFMR = document.getElementById('txtIsoTemplate').value;
        var isoImageFIR = document.getElementById('txtIsoImage').value;

        var Biometrics = Array(); // You can add here multiple FMR value
        Biometrics["0"] = new Biometric("FMR", isoTemplateFMR, "UNKNOWN", "", "");

        var res = GetPidData(Biometrics);
        if (res.httpStaus) {
            if (res.data.ErrorCode != "0") {
                alert(res.data.ErrorDescription);
            }
            else {
                document.getElementById('txtPid').value = res.data.PidData.Pid
                document.getElementById('txtSessionKey').value = res.data.PidData.Sessionkey
                document.getElementById('txtHmac').value = res.data.PidData.Hmac
                document.getElementById('txtCi').value = res.data.PidData.Ci
                document.getElementById('txtPidTs').value = res.data.PidData.PidTs
            }
        }
        else {
            alert(res.err);
        }

    }
    catch (e) {
        alert(e);
    }
    return false;
}
function GetProtoPid() {
    try {
        var isoTemplateFMR = document.getElementById('txtIsoTemplate').value;
        var isoImageFIR = document.getElementById('txtIsoImage').value;

        var Biometrics = Array(); // You can add here multiple FMR value
        Biometrics["0"] = new Biometric("FMR", isoTemplateFMR, "UNKNOWN", "", "");

        var res = GetProtoPidData(Biometrics);
        if (res.httpStaus) {
            if (res.data.ErrorCode != "0") {
                alert(res.data.ErrorDescription);
            }
            else {
                document.getElementById('txtPid').value = res.data.PidData.Pid
                document.getElementById('txtSessionKey').value = res.data.PidData.Sessionkey
                document.getElementById('txtHmac').value = res.data.PidData.Hmac
                document.getElementById('txtCi').value = res.data.PidData.Ci
                document.getElementById('txtPidTs').value = res.data.PidData.PidTs
            }
        }
        else {
            alert(res.err);
        }

    }
    catch (e) {
        alert(e);
    }
    return false;
}
function GetRbd() {
    try {
        var isoTemplateFMR = document.getElementById('txtIsoTemplate').value;
        var isoImageFIR = document.getElementById('txtIsoImage').value;

        var Biometrics = Array();
        Biometrics["0"] = new Biometric("FMR", isoTemplateFMR, "LEFT_INDEX", 2, 1);
        Biometrics["1"] = new Biometric("FMR", isoTemplateFMR, "LEFT_MIDDLE", 2, 1);
        // Here you can pass upto 10 different-different biometric object.


        var res = GetRbdData(Biometrics);
        if (res.httpStaus) {
            if (res.data.ErrorCode != "0") {
                alert(res.data.ErrorDescription);
            }
            else {
                document.getElementById('txtPid').value = res.data.RbdData.Rbd
                document.getElementById('txtSessionKey').value = res.data.RbdData.Sessionkey
                document.getElementById('txtHmac').value = res.data.RbdData.Hmac
                document.getElementById('txtCi').value = res.data.RbdData.Ci
                document.getElementById('txtPidTs').value = res.data.RbdData.RbdTs
            }
        }
        else {
            alert(res.err);
        }

    }
    catch (e) {
        alert(e);
    }
    return false;
}

function GetProtoRbd() {
    try {
        var isoTemplateFMR = document.getElementById('txtIsoTemplate').value;
        var isoImageFIR = document.getElementById('txtIsoImage').value;

        var Biometrics = Array();
        Biometrics["0"] = new Biometric("FMR", isoTemplateFMR, "LEFT_INDEX", 2, 1);
        Biometrics["1"] = new Biometric("FMR", isoTemplateFMR, "LEFT_MIDDLE", 2, 1);
        // Here you can pass upto 10 different-different biometric object.


        var res = GetProtoRbdData(Biometrics);
        if (res.httpStaus) {
            if (res.data.ErrorCode != "0") {
                alert(res.data.ErrorDescription);
            }
            else {
                document.getElementById('txtPid').value = res.data.RbdData.Rbd
                document.getElementById('txtSessionKey').value = res.data.RbdData.Sessionkey
                document.getElementById('txtHmac').value = res.data.RbdData.Hmac
                document.getElementById('txtCi').value = res.data.RbdData.Ci
                document.getElementById('txtPidTs').value = res.data.RbdData.RbdTs
            }
        }
        else {
            alert(res.err);
        }

    }
    catch (e) {
        alert(e);
    }
    return false;
}



</script>
<style>
    .finger-print-enroll{
        margin:0 auto;
        width:60%;
        padding:25px;
    }
    .finger-print-enroll,table{
        padding:15px;
    }
</style>
</head>
<body>
     <!-- main-container -->
   <div class="container-fluied" style="background:#eee">
   <!-- header-section -->
   <div class="header" style="height:200px; padding-top:20px; color:green">
   <img src="img/logo.png" alt="" style="width:250px;height:150px;float:left;margin-right:10px;">    
    <h1>  <div class="text-center " style="font-weight:bold;">Student Monitoring System</div> </h1> 
      
   </div>
   <!-- header-section -->
  <?php include('admin-nav.php');?>

<!-- nav -->
   <!-- content-area-section -->
<div class="content-area" style="height:500px;">
	<!-- fingerprint-->
	
	  <div class="finger-print-enroll text-center">
                <form action="" method="post">
            <table class="table">
                <tr class="text-center">
                    <td><input type="text" style="text-align:center" name="admn" placeholder="Enter Student Admin Number" class="form-control"></td>
                </tr>    
            </table>

            <table class="table">
            <tr>
              <td >
                <img id="imgFinger" width="145px" height="188px" Falt="Finger Image" class="padd_top" />
				</td>
            </tr>
   
                <input type="hidden" id="txtIsoTemplate" name="fingerdata" value="" class="form-control"/>
                <input type="hidden" value="" id="txtStatus" class="form-control hide" />
                <input type="hidden" value="" id="txtImageInfo" class="form-control" />
                <input type="hidden"id="txtIsoTemplate" name="txtIsoTemplate" value="" class="form-control"/>
                <input type="hidden" id="txtAnsiTemplate" class="form-control"/>
                <input type="hidden" id="txtIsoImage" class="form-control"/>
                <input type="hidden" id="txtRawData" class="form-control"/>
                <input type="hidden" id="txtWsqData" class="form-control"/>
            <tr>
            
            <td>
			 <input type="submit" id="btnCapture" value="Capture" class="art-button btn btn-success my-3" onclick="return Capture()" />
			<input type="submit" value="Enroll-Student" name="enroll-finger" class="art-button btn btn-success" onclick=" return validateform()" name="submit" id="sub" /></td> 
            </tr>
            </table>
          </form>
          
                </div>
	<!-- fingerprint-->
    
<!-- content-area-section -->
   </div>
        
    


<!-- main-container -->
   </div>
<div class="footer text-center" style="height:100px;padding:20px;">&copy Copy right 2020 Computer Science Department</div>

<script src="jquery-1.8.2.js"></script>
<script src="mfs100-9.0.2.6.js"></script>
</body>
</html>