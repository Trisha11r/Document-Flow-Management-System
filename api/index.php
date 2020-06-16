<?php
	session_start();
	if(isset($_SESSION['UserId']))
	{
		header('Location: dashboard.php');
	}
    $sessionId = session_id();
    function test_input($data) 
    {
        //function to return the data part of user input
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;        
    }
    //function to create connection to database module using soap
    $options = array('location' => 'http://localhost/authmodule/authsys.php','uri' => 'http://localhost/','trace'=>true);
    //create an instante of the SOAPClient (the API will be available)
    $authSysSoapObj = new SoapClient(NULL, $options);
    try
    {            
    	if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $username=test_input($_POST["Username"]);
            $password=test_input($_POST["Password"]);
            $password = md5($password);
            $result =  json_decode($authSysSoapObj->__soapCall("setUser",array($username,$password,$sessionId)),true);
            if($result['status'] == 'ok')
            {            	
            	$_SESSION['UserId'] = $result['res'][0]['Id'];
            	$_SESSION['Username'] = $username;
            	            	
            	$options = array('location' => 'http://localhost/dbmodule/dbsys.php','uri' => 'http://localhost/', 'trace'=>true);
				//create an instante of the SOAPClient (the API will be available)
				$dbSysSoapObj = new SoapClient(NULL, $options);
				$ug = json_decode($dbSysSoapObj->__SoapCall("query",array("UserToUserGroup",array("UserGroupId"),array("UserId"),array($_SESSION['UserId']),$_SESSION['UserId'],session_id())),true);				
				
				$ug = $ug['res'];

            	$userGroupId = array();
            	$userPerms = array(0,0,0);

            	for($i = 0;$i < sizeof($ug); $i++)
            	{
            		array_push($userGroupId, $ug[$i]['UserGroupId']);
            		$uperms = json_decode($dbSysSoapObj->__SoapCall("query",array("UserGroup",array("UserManipulationPerms","DocumentTypeManipulationPerms","DocumentFlowManipulationPerms"),array("Id"),array($ug[$i]['UserGroupId']),$_SESSION['UserId'],session_id())),true);
            		$userPerms[0] = $userPerms[0] | $uperms['res'][0]['UserManipulationPerms'];
            		$userPerms[1] = $userPerms[1] | $uperms['res'][0]['DocumentTypeManipulationPerms'];
            		$userPerms[2] = $userPerms[2] | $uperms['res'][0]['DocumentFlowManipulationPerms'];
            	}
            	$_SESSION['UserGroupId'] = $userGroupId;
            	$_SESSION['UserPerms'] = $userPerms;
            	?>
	    			<script type="text/javascript"> 
	    				window.location="dashboard.php";
	    			</script>
    			<?php
			}
            else
            {    
				?>
                    <div class="col-xs-offset-3 col-xs-6">
	                    <br/><br/>
                        <div class="alert alert-danger" style="text-align: center; font-size: 20px;">
		                            Login Unsuccessful!
                        </div>
                    </div>
                    <script>
                        setTimeout(function(){window.location.href="index.php"},2000);
                    </script>
		        <?php       
		    }
        }
	}
	catch(SoapFault $ex)
    {
        //catch exceptions
    	echo $ex;            
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login :: DFMS 2016</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<br/><br/><br/><br/><br/><br/><br/><br/><br/>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-horizontal" method="post" autocomplete="off" >
	    <div class="col-sm-4 col-sm-offset-4">
	        <div class="panel panel-default">
	            <div class="panel-heading well" align="center">
	                <span style="font-size: 16px; ">DFMS : Please Sign-In to continue</span>
	            </div><br/>
	            <div class="panel-body">
	                <input name="Username" type="text" class="form-control" placeholder="Username" required/><br/>
	                <input name="Password" type="password" class="form-control" placeholder="Password" required/><br/>
	                <button type="submit" class="btn btn-block btn-success btn-lg btn-raised">
	                        <i class="fa fa-sign-in"></i> Log In
	                </button>
	            </div>
	        </div>
	    </div>
	</form>
</body>
<script type="text/javascript" src="./assets/js/sidepane.js"></script>
</html>