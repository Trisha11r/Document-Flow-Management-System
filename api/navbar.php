<style type="text/css">
.top-bar
{
	box-shadow: 0 2px 4px rgba(12,13,14,0.15);
	background-color: #fafafb;
	position: fixed;
	top:0px;
	left: 0px;
	width: 100%;
	z-index: 1000;
}
.top-bar > .container
{
	margin:5px auto;	
}
.brand
{
	font-size: 1.8em;
	font-weight: 600;
}
.search
{
	font-size: 1em;
}
.search > input
{
	width: 250px;
}
.options
{
	text-align: right;
}
</style>
<div class="container-fluid top-bar">
	<div class="container">
		<div class="row">
			<div class="col-md-5 brand text-primary">
				<a href="dashboard.php">Document Flow Management System</a>
			</div>
			<div class="col-md-4 search">					
				<input type="text" name="search" placeholder="Search Files, Users, Flows ...">
				<div class="btn btn-primary">
					Search <i class="fa fa-search" aria-hidden="true"></i>
				</div>
			</div>
			<div class="col-md-3 options">
				<div class="btn btn-primary">
					Settings <i class="fa fa-bars" aria-hidden="true"></i>	
				</div>
				<a href="logout.php">
					<div class="btn btn-warning">				
						Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i>	
					</div>
				</a>
			</div>
		</div>
	</div>
</div>