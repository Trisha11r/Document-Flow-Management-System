<?php
$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
//create an instante of the SOAPClient (the API will be available)
$dbSysSoapObj = new SoapClient(NULL, $options);
$username = $_SESSION["Username"];
$userDetails = $dbSysSoapObj->__SoapCall("query",array("User",array("*"),array("Id"),array($_SESSION["UserId"]),$_SESSION['UserId'],session_id()));
$userDetails = json_decode($userDetails,true);
$name = $userDetails['res'][0]['FirstName']." ".$userDetails['res'][0]['MiddleName']." ".$userDetails['res'][0]['LastName'];
$designation = $userDetails['res'][0]['Designation'];
$department = $userDetails['res'][0]['Department'];
$photoURL = $userDetails['res'][0]['Photograph'];
$userGroupId = $_SESSION["UserGroupId"];
$userGroupName = array();
foreach ($userGroupId as $uGId)
{
	$uGName = json_decode($dbSysSoapObj->__SoapCall("query",array("UserGroup",array("Name"),array("Id"),array($uGId),$_SESSION['UserId'],session_id())),true);
	array_push($userGroupName, $uGName['res'][0]['Name']);
}
?>
<div class="side-pane opened-side-pane">
	<i class="fa toggle-side-pane close-side-pane-button fa-caret-square-o-left"></i>
	<div class="user-section">
		<div class="user-photo">
			<?php if($photoURL==""){ ?>
				<i class="fa fa-user-circle"  aria-hidden="true"></i>
			<?php }else{ ?>
				<img src="<?=$photoURL?>" title= "<?=$name.'\'s photo'?>">
			<?php } ?>
		</div>
		<div class="user-info">
			<div class="user-name"><?= $name." [".$username."]" ; ?></div>
			<div class="user-designation"><?= $designation?></div>
			<div class="user-department"><?= $department?></div>
		</div>					
		<div class="user-groups">
			<ul class="pager">
				<?php
					for($i=0;$i < min(5,sizeof($userGroupName));$i++)
					{
						echo "<li><a href='#'>".$userGroupName[$i]."</a></li>";
					}
					if(sizeof($userGroupName)>5)
					{
						?>
						<li><a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a></li>
						<?php
					}
				?>
			</ul>						
		</div>
	</div>
	<hr>
	<div class="function-section">
		<ul class="main-menu">
			<?php 
				if($_SESSION["UserPerms"][0]==1) 
				{
			?>
					<li>
						<p>Manage Users<i class="fa fa-caret-down toggle-sub-menu" aria-hidden="true"></i></p>
						<ul class="sub-menu hidden">
							<li><a href="register.php">Add User</a></li>
							<li>Edit User</li>
							<li>Remove User</li>
						</ul>
					</li>
			<?php 
				} 
				if($_SESSION["UserPerms"][1]==1) 
				{
			?>
					<li>
						<p>Manage Document Types<i class="fa fa-caret-down toggle-sub-menu" aria-hidden="true"></i></p>
						<ul class="sub-menu hidden">
							<li><a href="addDocType.php">Add Document Type</a></li>
							<li><a href="viewDocType.php">View Document Type</a></li>
						</ul>
					</li>
			<?php
				} 
				if($_SESSION["UserPerms"][2]==1) 
				{
			?>
					<li>
						<p>Manage Document Flows<i class="fa fa-caret-down toggle-sub-menu" aria-hidden="true"></i></p>
						<ul class="sub-menu hidden">
							<li><a href="addDocFlow.php">Add Document Flow</a></li>
							<li><a href="viewDocFlow.php">View Document Flow</a></li>
						</ul>
					</li>
			<?php
				}
			?>
			<li>
				<p><a href="createDoc.php">Create a Document Instance</a></p>
			</li>
		</ul>	
	</div>				
</div>