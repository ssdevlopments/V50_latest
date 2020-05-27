@include ('school.includes.top')
   </head>
   <body class="theme-bright breakpoint-1200">
    
    	@include ('school.includes.header')
    	
      	<div id="container" class="fixed-header">
        
        @include ('school.includes.left')
        
        <style>
        	.dataTable{
				margin-top:0;
			}
			table.table {
			    font-size: inherit;
    			background: #f9f9f9;
			}
			.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
		    	padding: 0px;

		    }
		    .table>thead>tr>th {
			    vertical-align: middle;
			    font-weight: normal;
			}
			.tabbable-custom>.tab-content {
			    overflow: hidden;
			}
			.table_bg_header{
				background: #e2e2e2;
				background: -moz-linear-gradient(top,  #e2e2e2 0%, #dbdbdb 50%, #d1d1d1 51%, #fefefe 100%);
				background: -webkit-linear-gradient(top,  #e2e2e2 0%,#dbdbdb 50%,#d1d1d1 51%,#fefefe 100%);
				background: linear-gradient(to bottom,  #e2e2e2 0%,#dbdbdb 50%,#d1d1d1 51%,#fefefe 100%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e2e2e2', endColorstr='#fefefe',GradientType=0 );
			}
			.table_bg_header1{
				background: #f2f6f8;
				background: -moz-linear-gradient(top,  #f2f6f8 0%, #d8e1e7 50%, #b5c6d0 51%, #e0eff9 100%);
				background: -webkit-linear-gradient(top,  #f2f6f8 0%,#d8e1e7 50%,#b5c6d0 51%,#e0eff9 100%);
				background: linear-gradient(to bottom,  #f2f6f8 0%,#d8e1e7 50%,#b5c6d0 51%,#e0eff9 100%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f6f8', endColorstr='#e0eff9',GradientType=0 );
			}
			.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td
			{
				vertical-align: middle;
			}
			.chosen-container{
				width: 99% !important;
			}
			.panel {
		    	margin-bottom: 0px;
			}
			.modal-footer {
			    margin-top: 15px;
			    padding: 8px 2px 2px;
			    text-align: right;
			    border-top: 1px solid #e5e5e5;
			}
			
			
			
			a.lightbox img {border: 3px solid white;box-shadow: 0px 0px 8px rgba(0,0,0,.3);}

			.lightbox-target {
				top: -100%;
				width: 100%;
				background: rgba(0,0,0,.7);
				width: 100%;
				opacity: 0;
				-webkit-transition: opacity .5s ease-in-out;
				-moz-transition: opacity .5s ease-in-out;
				-o-transition: opacity .5s ease-in-out;
				transition: opacity .5s ease-in-out;
				overflow: hidden;
			}
			.lightbox-target img {
				margin: auto;
				position: absolute;
				top: 0;
				left:0;
				right:0;
				bottom: 0;
				max-height: 0%;
				max-width: 0%;
				border: 3px solid white;
				box-shadow: 0px 0px 8px rgba(0,0,0,.3);
				box-sizing: border-box;
				-webkit-transition: .5s ease-in-out;
				-moz-transition: .5s ease-in-out;
				-o-transition: .5s ease-in-out;
				transition: .5s ease-in-out;
			}

			a.lightbox-close {
				display: block;
				width:50px;
				height:50px;
				box-sizing: border-box;
				background: white;
				color: black;
				text-decoration: none;
				position: absolute;
				top: 80px !important;
				right: 38.5%;
				-webkit-transition: .5s ease-in-out;
				-moz-transition: .5s ease-in-out;
				-o-transition: .5s ease-in-out;
				transition: .5s ease-in-out;
			}

			a.lightbox-close:before {
				content: "";
				display: block;
				height: 30px;
				width: 1px;
				background: black;
				position: absolute;
				left: 26px;
				top:10px;
				-webkit-transform:rotate(45deg);
				-moz-transform:rotate(45deg);
				-o-transform:rotate(45deg);
				transform:rotate(45deg);
			}

			a.lightbox-close:after {
				content: "";
				display: block;
				height: 30px;
				width: 1px;
				background: black;
				position: absolute;
				left: 26px;
				top:10px;
				-webkit-transform:rotate(-45deg);
				-moz-transform:rotate(-45deg);
				-o-transform:rotate(-45deg);
				transform:rotate(-45deg);
			}

			.lightbox-target:target {
				opacity: 1;
				top: 0;
				bottom: 0;
			}

			.lightbox-target:target img {
				max-height: 60%;
				max-width: 60%;
			}
			.lightbox-target:target a.lightbox-close {
				top: 0px;
			}
        </style>
        
        <div id="content">
            <div class="container">
               	<div class="crumbs">
                  	<ul id="breadcrumbs" class="breadcrumb">
						<li> <i class="icon-home"></i> <a href="#">Dashboard</a> </li>
						<li> <a title="">Student Activity</a></li>
						<li class="current"> <a title="">Students</a></li>
                  	</ul>
               </div>
               <br>
               <div class="row">
                 	<div class="col-md-12">
                    	
                       	<div class="row" style="margin-bottom: 10px;">
	                    	<div class="form-group">
								<div class="col-sm-2">
									<select class="form-control" id="cmbCourseFilter">
										<option value="">Course</option>								
									</select>
								</div>
								<div class="col-sm-1">
									<select class="form-control" id="cmbClassFilter" style="width: 94px;">
										<option value="">Class</option>
									</select>
								</div>
								<div class="col-sm-2">
									<select class="form-control" id="cmbSectionFilter">
										<option value="">Section</option>
									</select>
								</div>
								<div class="col-sm-2">
									<select class="form-control" id="cmbStatusFilter">
										<option value="">Status</option>
									</select>
								</div>
								<div class="col-sm-1" style="display: none;">
									<select class="form-control" id="cmbactiveInactive" style="width: 151%;">
										<option value="">Active</option>
										<option value="INACTIVE">In Active</option>
									</select>
								</div>
								<div class="col-sm-2">
									<input type="text" class="form-control" id="cmbAdmissionFilter" placeholder="Admission No">
								</div>
								<div class="col-sm-2">
									<button type="button" class="btn btn-flat" data-loading-text="..." id="btnFilter_CCOptional" title="Filter" onclick="filter();" style="height: 32px;"><i class="icon-search"></i></button>
								</div>
							</div>
						</div>
						
	                	<div class="row" style="margin-bottom: 10px;">
	                    	<div class="form-group">							
								<div class="col-sm-12">
									<button class="btn btn-sm btn-primary" onclick="add_student();"><i class="icon-plus"></i> Add</button>
									<button class="btn btn-sm btn-info tmp_new_bg" id="btnUpload" onclick="UploadStudent();"><i class="icon-upload-alt"></i> Upload Students</button>
									<button class="btn btn-sm btn-warning tmp_new_bg" id="btnPhotoUpload" onclick="PhotoUpload()" ><i class="icon-cloud-upload"></i> Upload Document</button>
									<button class="btn btn-sm btn-danger tmp_new_bg" id="showFields" onclick="return selectfields()"><i class="icon-download"></i> Download Selected Fileds</button>
									<button class="btn btn-sm btn-success tmp_new_bg" id="btnUploadToEdit" onclick="uploadtoedit()"><i class="icon-upload"></i> Upload Selected Fields To Update</button>
									<!--<button class="btn btn-sm btn-info tmp_new_bg" id="btnManageCategory" onclick="manageCategoryOne()"><i class="icon-pushpin"></i> Manage Category One</button>-->
									<button class="btn btn-sm btn-primary tmp_new_bg" id="btnExport" ><i class="icon-th"></i> Save As Excel</button>
								</div>
								<!--<div class="col-sm-2">								
									<input type="text" class="form-control" id="txtStudentNameFilter" placeholder="Student Name">
								</div>-->
							</div>
	                	</div>
	                	
	                	<div class="col-lg-12 col-sm-12" style="display: none;">
							<span class="label label-default tmp_new_bg" style="background-color: rgb(242, 234, 223) !important;color: #000;border: solid 1px #ccc;">Active (<span id="lbl_active_count"></span>)</span>&nbsp;<span class="label label-default tmp_new_bg" style="background-color: rgb(255, 102, 0) !important;">&nbsp;&nbsp;&nbsp;TC (<span id="lbl_tc_count"></span>)&nbsp;&nbsp;&nbsp;</span>&nbsp;<span class="label label-default tmp_new_bg" style="background-color:rgb(255, 0, 130) !important;">Struck Off (<span id="lbl_struckof_count">12</span>)</span>
							<!--&nbsp;<span class="label label-default tmp_new_bg" style="background-color:rgb(255, 24, 7) !important;">Cancelled (<span id="lbl_cancelled_count">0</span>)</span>-->
						</div>
							
						<div class="row" style="margin-bottom: 10px;">	
							<div class="form-group">
								<div class="col-sm-12">
									<table class="table table-bordered" id="dtblStudentAssign">
										<thead>
											<tr>
												<th hidden="">ID</th>
												<th height="26" class="text-center table_bg_header1">Sl No.</th>
												<th class="text-center table_bg_header1">Photo</th>
												<th class="text-center table_bg_header1">Adm.No</th>
												<th class="text-left table_bg_header1">&nbsp;&nbsp;Name</th>
												<th class="text-center table_bg_header1">Course</th>
												<th class="text-center table_bg_header1">Class</th>
												<th class="text-center table_bg_header1">Section</th>
												<th class="text-center table_bg_header1">Father Name</th>
												<th class="text-center table_bg_header1">Mobile No</th>
												<th hidden="">ID</th>
												<th hidden="">ID</th>
												<th hidden="">ID</th>
												<th hidden="">ID</th>
												<th class="text-center table_bg_header1">Action</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
	                			</div>
	                		</div>
	                	</div>
	                	
					</div>
               	</div>
            </div>
        </div>
      	
      	
    	<div class="modal fade" id="modalAddStudentDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop = "static">
        	<div class="modal-dialog" style="width: 90%;">
        		<div class="modal-content">
        			<div class="modal-header tmp_bg_header">
        				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        				<h4 class="modal-title" id="lblStudentInfo" style="color:#FFFFFF;"></h4>
        				<!--<h4 class="modal-title" id="myModalLabel"><span id="lblStudentDetails"></span></h4>-->
        			</div>
        			<div class="modal-body">
        				<div class="panel with-nav-tabs panel-primaryx">
        					<div class="panel-heading" style="background: #fff;color: #000000;border-color: #fff;padding: 0;">
								<ul class="nav nav-tabs" role="tablist" >
									<li id="liprofile" class="active"><a href="#profile" data-toggle="tab">Profile</a></li>
									<li id="licontact"><a href="#contact" data-toggle="tab">Contact Details</a></li>
									<li id="lidocument"><a href="#document" data-toggle="tab">Documents</a></li>
									<li id="liachivements"><a href="#achivements" data-toggle="tab">Achivements</a></li>
									
									<li id="lisibling" ><a href="#sibling" data-toggle="tab">Sibling</a></li>
									<li id="lipreschooldetails" ><a href="#previousSchoolDetails" data-toggle="tab">Previous School Details</a></li>
									
									<li id="limisc" ><a href="#misc" data-toggle="tab">Miscellaneous</a></li>
								</ul>
							</div>
							<div class="panel-body">
           						<div class="tab-content">
           							
           							<!-- PROFILE -->
           							<div class="tab-pane active" id="profile">
           								<form class="form-horizontal" role="form" id="frmProfile" name="frmProfile">
											
											 	<!--Unique Id info-->
											 	<input type="hidden" class="form-control" id="hidStudentCode" name="hidStudentCode">
												<input type="hidden" class="form-control" id="hidCourseCode" name="hidCourseCode">
												<input type="hidden" class="form-control" id="hidClassCode" name="hidClassCode">
												<input type="hidden" class="form-control" id="hidCategoryCode1" name="hidCategoryCode1">
												<input type="hidden" class="form-control" id="hidCategoryCode2" name="hidCategoryCode2">
												<input type="hidden" class="form-control" id="hidStudentSection" name="hidStudentSection">
												<input type="hidden" class="form-control" id="hidStudentRollNo" name="hidStudentRollNo">
												<input type="hidden" class="form-control" id="hidAdmNo" name="hidAdmNo">
												
												<div class="row">
													<div class="form-group">
														<label for="admissionNobtn" class="col-sm-2 control-label">Admission No<span style="color: red;"> *</span></label>
														<div class="col-sm-2">
															<input type="text" class="form-control" id="admissionnumber" name="admissionnumber" style="width: 80%; float: left;" >
															<button type="button" name="admissionNobtn" id="admissionNobtn" class="btn btn-danger" title="See Last Admission No" style="border-style: groove;height: 31px; border: 0;" onclick="showadmisinoninfo()"><i class="icon-info"></i></button>
														</div>
														<label for="studentname" class="col-sm-1 control-label">Name<span style="color: red;"> *</span></label>
														<div class="col-xs-4">
															<input type="text" class="form-control" id="studentname" name="studentname" required="">
														</div>
														<label for="gender" class="col-sm-1 control-label">Gender</label>
														<div class="col-xs-2">
															<select class="form-control" id="gender" name="gender">
																<option value="M" selected="">Male</option>
																<option value="F">Female</option>
															</select>
														</div>
													</div>
												</div>
												
											  	<div class="row">
											  		<div class="form-group">
											  			<label for="" class="col-sm-2 control-label">Father's Name<span style="color: red;">*</span></label>
														<div class="col-sm-4">
															<input type="text" class="form-control" id="fathersname" name="fathersname" required />
														</div>
														<label for="" class="col-sm-2 control-label">Mother's Name<span style="color: red;">*</span></label>
														<div class="col-sm-4">
															<input type="text" class="form-control" id="mothersname" name="mothersname">
														</div>
													</div>
											  	</div>
											  	<div class="row">
												 	<div class="form-group">
										  				<label for="inputname" class="col-sm-2 control-label">Blood Group <span style="color: red;"> *</span></label>
														<div class="col-md-2">
															<select class="form-control" id="cmbBloodgroup" name="cmbBloodgroup"">
																<option value="">SELECT</option>
																<option value="B+">B+</option>
																<option value="A+">A+</option>
																<option value="O+">O+</option>
																<option value="AB+">AB+</option>
																<option value="B-">B-</option>
																<option value="A-">A-</option>
																<option value="O-">O-</option>
																<option value="AB-">AB-</option>
																<option value="NA">Not Available</option>
															</select>
														</div>
														<label for="" class="col-sm-1 control-label">D.O.B <span style="color: red;"> *</span></label>
														<div class="col-sm-2">
															<input type="text" class="form-control" id="txtBirthDate" name="txtBirthDate"  data-date-end-date="0d" placeholder="dd-mm-yyyy" readonly1>
														</div>
														<label for="" class="col-sm-1 control-label">Mobile <span style="color: red;"> *</span></label>
														<div class="col-sm-4">
															<input type="text" class="form-control" id="regdmobileno" name="regdmobileno">
														</div>
													</div>													 	
											  	</div>
												<div class="row">
												 	<div class="form-group">
										  				<label for="cmbCourseAdd" class="col-sm-2 control-label">Course <span style="color: red;"> *</span></label>
														<div class="col-md-2">
															<select class="form-control" id="cmbCourseAdd" name="cmbCourseAdd" >
															</select>
														</div>
														<label for="cmbClassAdd" class="col-sm-1 control-label">Class <span style="color: red;"> *</span></label>
														<div class="col-md-2">
															<select class="form-control" id="cmbClassAdd" name="cmbClassAdd" style="width: 80%; float:left;" >
																<option value="">Select</option>
															</select>
															<button type="button" id="sectionAssignBtn" class="btn btn-info" title="Assigned Sections To Class" disabled="" style="border-style: groove; border: 0; height: 31px;" onclick="showSectionDetails()"><i class="icon-info"></i></button>
														</div>
														<label for="cmbSectionAdd" class="col-sm-1 control-label">Section</label>
														<div class="col-md-2">
															<select class="form-control" id="cmbSectionAdd" name="cmbSectionAdd" style="width: 80%; float:left;">
																<option value="">Select</option>
															</select>
															<button type="button" id="rollnoAssignBtn" class="btn btn-success" disabled="" title="Assigned Rolls To Student" style="border-style: groove; border: 0; height: 31px;" onclick="showSectionRolls()"><i class="icon-info"></i></button>
														</div>
														<label for="txtRollNo" class="col-sm-1 control-label">Roll No</label>
														<div class="col-md-1">
															<input type="text" class="form-control" id="txtRollNo" name="txtRollNo" autocomplete="off">
														</div>
													</div>
												</div>
											 	
											 	<div class="row">
											 		<div class="form-group">
														<label for="" class="col-sm-2 control-label">Category-1<span style="color: red;"> *</span></label>
														<div class="col-sm-2">
															<select class="form-control" id="cmbCategory1" name="cmbCategory1">
																<option value="">Select</option>
															</select>
														</div>
														<label for="" class="col-sm-1 control-label" style="width: unset;">Category 2<span style="color: red;"> *</span></label>
														<div class="col-sm-2">
															<select class="form-control" id="cmbCategory2" name="cmbCategory2">
																<option value="">Select</option>
															</select>
														</div>
														<label for="" class="col-sm-2 control-label">Admission Type<span style="color: red;"> *</span></label>
														<div class="col-sm-2">
															<select class="form-control" id="cmbAdmissionType" name="cmbAdmissionType" >
																<option value="">Select</option>
															</select>
														</div>
											 		</div>
											 	</div>
											 	<div class="row">
											 		<div class="form-group">
														<label for="" class="col-sm-2 control-label">Fee Calculate Date<span style="color: red;"> *</span></label>
														<div class="col-sm-2">
															<input type="text" class="form-control" id="admissiondate" name="admissiondate" placeholder="dd-mm-yyyy" >
														</div>
														<label for="" class="col-sm-1 control-label">Aadhar No</label>
														<div class="col-sm-2">
															<input type="text" class="form-control" id="aadhar_no" >
														</div>
														<label for="" class="col-sm-3 control-label">Enrollment Date / Admission Date<span style="color: red;"> *</span></label>
														<div class="col-sm-2">
															<input type="text" class="form-control" id="enrollmentdate" name="enrollmentdate" placeholder="dd-mm-yyyy" >
															<input type="hidden" class="form-control" id="hidenrollmentdate" name="hidenrollmentdate">
														</div>
											 		</div>
											 	</div>
											 	<div class="row">
											 		<div class="form-group">	
														<label for="inputname" class="col-sm-2 control-label">CBSE Registration No</label>
														<div class="col-sm-2">
															<input type="text" class="form-control" id="txtRegistrationno" name="txtRegistrationno" placeholder="Registration No" >
														</div>
														<label for="inputname" class="col-sm-2 control-label">House Name</label>
														<div class="col-sm-2">
															<select class="form-control" id="cmbHouseName" name="cmbHouseName" style="width: 80%; float: left;">
															</select>
															<button type="button" name="houseInfobtn" id="houseInfobtn" class="btn btn-warning" title="See House Info" style="border-style: groove; border: 0; height: 31px;" onclick="ShowhouseInfo()"><i class="icon-info"></i></button>
														</div>
														<label for="inputname" class="col-sm-2 control-label">Service Category</label>
														<div class="col-sm-2">
															<select class="form-control" id="cmbServiceCategory" name="cmbServiceCategory">
															</select>
														</div>
											 		</div>
											 	</div>
											
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" id="btnOpenFeeModal" onclick="save_student_data()" style="width: 10%;" >Save</button>
												<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
												</div>
										</form>
           							</div>
           							
									<!-- CONTACT -->
									<div class="tab-pane" id="contact">
										<form class="form-horizontal" role="form" id="frmContact" name="frmContact">
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Alternate Contact</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="alternatemobileno" name="alternatemobileno"placeholder="Alternate Mobile Number" title="Enter Alternate Contact Number">
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Email ID</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="emailid" name="emailid"placeholder="Email Address" title="Enter Email ID">
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Present Address</label>
												<div class="col-sm-8">
													<textarea class="form-control" id="address" name="address" placeholder="Address" title="Enter Address"></textarea>
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Permanent Address</label>
												<div class="col-sm-8">
													<textarea class="form-control" id="txtPermanentAddress" name="txtPermanentAddress" placeholder="Address" title="Enter Address"></textarea>
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">State</label>
												<div class="col-sm-8">
													<select class="form-control" id="cmbState" name="cmbState">
														<option value="">SELECT</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">City</label>
												<div class="col-sm-8">
													<select class="form-control" id="cmbCity" name="cmbCity">
														<option value="">SELECT</option>
													</select>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" onclick="save_contact_data()">Save</button>
												<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
											</div>
											
										</form>
									</div>
			           				
			           				<!-- DOCUMENT -->
									<div class="tab-pane" id="document">
										<div class="table-responsive">
										   	<form id="frmDocumentUpload" name="frmDocumentUpload" method="post" action="" enctype="multipart/form-data">
											<table class="table table-bordered  dataTable no-footer">
												<thead>
												  	<tr>
														<th style="text-align:center;">Sl.No</th>
														<th style="text-align:center;">Document Type</th>
														<th style="text-align:center;">Browse</th>
														<th style="text-align:center;">Preview</th>
														<th style="text-align:center;">Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
							 				</table>
											</form>
										</div>
									</div>
			           				
			           				<!-- ACHIEVEMENTS -->
									<div class="tab-pane" id="achivements">
										<button class="btn btn-sm btn-primary" onclick="add_acvivements();"><i class="icon-plus"></i> Add</button>
										<table class="table table-bordered" id="dtblAchievements">
						                	<thead>
						                		<tr>
					                				<th class="text-center">#</th>
					                				<th class="text-center" hidden="hidden">id</th>
					                				<th class="text-center" hidden="hidden">Type</th>
					                				<th class="text-center" hidden="hidden">Category Code</th>
					                				<th class="text-center" hidden="hidden">Sub Category Code</th>
					                				<th class="text-center">Category</th>
													<th class="text-center">Sub Category</th>
													<th class="text-center">Level</th>
													<th class="text-center">Position</th>
													<th class="text-center">Date</th>
													<th class="text-center">Venue</th>
													<th class="text-center">Conducted By</th>
													<th class="text-center">Remark</th>
													<th class="text-center">Action</th>
					                			</tr>
					                		</thead>
					                		<tbody>
					                		
					                		</tbody>
					                	</table>
									</div>
									
									
									<!-- SIBLINGS -->
									<div class="tab-pane" id="sibling">
										<form class="form-horizontal" role="form" id="frmSiblings" name="frmSiblings">
											<div class="row">
												<label for="" class="col-sm-offset-1 col-lg-3 col-md-3 col-sm-3 control-label">Search by Student Name / Admission No :</label>
												<div class="col-lg-6 col-md-6 col-sm-6">
													<!--<select multiple style="width:260px;" class="sel" id="cmbSearchStudent">class="chosen-select"-->
													<select data-placeholder="Enter Student Name/Admission No" style="width:260px;" class="chosen-container chosen-container-multi" id="cmbSearchStudent" name="cmbSearchStudent[]" multiple >;
														<option value="">SELECT</option>
														<option value="7904">AHASKAR</option>
														<option value="11323">AMRIT DASH</option>
														<option value="11669">SAIMAA DAS</option>
														<option value="7906">ALLEN PRADHAN</option>
														<option value="7909">AMIT KUMAR</option>
													</select>
													<!--<select class="form-control" id="cmbSearchStudent" name="cmbSearchStudent" placeholder="Enter Student Name/Admission No">
														<option value="">SELECT</option>
													</select>-->
													
												</div>
												<div class="col-lg-1">
													<button type="button" class="btn btn-primary" id="btnAddSibling" onclick="AddSiblings()">Add</button>
												</div>
											</div>
										</form>
										<table class="table table-bordered" id="dtblSiblings">
						                	<thead>
						                		<tr>
					                				<th class="text-center">#</th>
					                				<th hidden="">studentcode</th>
					                				<th class="text-center">Name</th>
													<th class="text-center">Course</th>
													<th class="text-center">Class</th>
													<th class="text-center">Section</th>
													<th class="text-center">Relation</th>
													<th class="text-center">Action</th>
					                			</tr>
					                		</thead>
					                		<tbody>
					                		</tbody>
					                	</table>
									</div>	
			           				
			           				<!-- PREVIOUS SCHOOL DETAILS -->
									<div class="tab-pane" id="previousSchoolDetails">
										<form class="form-horizontal" role="form" id="frmPreviousSchoolDetails" name="frmPreviousSchoolDetails">
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Name of the School</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="txtschoolname" name="txtschoolname"placeholder="School Name" title="Enter School Name">
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">TC No</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="txttcno" name="txttcno"placeholder="TC Number" title="Enter TC Number">
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Issue Date</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="issuedate" name="issuedate"placeholder="Issue Date" title="Enter Issue Date"  />
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Examination / Class Passed</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="txtpassedexamclass" name="txtpassedexamclass" placeholder="Examination / Class Passed" title="Enter Examination / Class Passed" />
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Percentage of Marks</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="txtmarksperc" name="txtmarksperc" placeholder="Percentage of Marks" title="Enter Percentage of Marks">
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Is the school recognised</label>
												<div class="col-sm-8" id="radschoolrecog">
													<div class="col-sm-3 "><input type="radio" name="optradio" id="yesrecog" value="YES"><label class="radio-inline" for="yesrecog" style="margin-left: 20%"><span></span><b>Yes</b></label></div>
													<div class="col-sm-3"><input type="radio" name="optradio" id="norecog" value="NO" checked><label class="radio-inline" for="norecog" style="margin-left: 20%"><span></span><b>No</b></label></div>
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Mode of Instrution</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="txtmodeofinst" name="txtmodeofinst" placeholder="Mode of Instrution" title="Enter Mode of Instrution" />
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Exam Body</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="txtexambody" name="txtexambody" placeholder="Exam Body" title="Enter Exam Body" />
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Roll No</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="txtrollno" name="txtrollno" placeholder="Roll No" title="Enter Roll No" />
												</div>
											</div>
											<div class="form-group">
												<label for="" class="col-sm-3 control-label">Registration No</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="txtregno" name="txtregno" placeholder="Registration No" title="Enter Registration No" />
												</div>
											</div>
											<div class="modal-footer">
													<button type="button" class="btn btn-primary" onclick="save_previous_school_details_data()">Save</button>
													<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
											</div>
											<!--<div class="modal-footer">
												<button type="submit" class="btn btn-primary" id="btnSaveSchoolPreDetails">Save</button>
												<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
											</div>-->
										</form>
									</div>
									
									<!-- MISC -->
									<div class="tab-pane" id="misc">
										<table class="table table-bordered">
											<tbody id="ShowMiscFields">
											</tbody>
											<button type="button" class="btn btn-primary" onclick="save_misc_data()">Save</button>
										</table>
									</div>	
									
           						</div>
           					</div>
           				</div>
           				
           			</div>
           		</div>
           	</div>
        </div>
			
			
      	<!-- Model For Student Exit Start-->
		<div class="modal fade-scale" id="exitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop = "static">
        	<div class="modal-dialog" style="margin-top:6%;">
        		<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header tmp_bg_header ">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								&times;
							</button>
							<h4 class="modal-title" id="lbl_student_cotrol_heading">Student Control</h4>
						</div>
						<div class="modal-body">
							<form class="form-horizontal" role="form" id="duecancelformid" method="post" action="manage_student_payment.php" target="_blank">
								<div class="col-lg-12 col-md-12 col-sm-12">
									<input type="hidden" class="form-control" id="admissionNounique" name="admissionNounique">
									<div class='alert alert-dismissable alert-danger alertBox' style="display:none;" id="showAlert">
										<!--<button type='button' class='close' data-dismiss='alert'>&times;</button>-->
										<div id="alertmessage">
											Some dues are pending against this student. So the student cannot be exited. For cancellation of dues <button type="submit" class="btn-primary" style="color:#09876"><b>Click Here</b></button>
										</div>
									</div>
								</div>
							</form>
							<form class="form-horizontal" role="form" id="exitformid">
								<input type="hidden" class="form-control" id="studentCodeunique" name="studentCodeunique">
								<input type="hidden" class="form-control" id="classCodeunique" name="classCodeunique">
								<input type="hidden" class="form-control" id="courseCodeunique" name="courseCodeunique">
								<div class="form-group" style="display: none;">
									<div class="col-sm-3">
										<input type="text" class="form-control" id="admissionno" name="admissionno" disabled style="background-color:#496CAD;color:white;">
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="name" name="name" disabled style="background-color:#496CAD;color:white;">
									</div>
									<div class="col-sm-2">
										<input type="text" class="form-control" id="classn" name="classn" disabled style="background-color:#496CAD;color:white;">
									</div>
								</div>
								<div class="form-group">
									<label for="inputname" class="col-sm-3 control-label">Type</label>
									<div class="col-sm-4">
										<select class="form-control" id="type1" name="type1" placeholder= "Select Status">
										</select>
									</div>
									<label for="" class="col-sm-1 control-label">Date</label>
									<div class="col-sm-3">
										<?php
										$now = date('d-m-Y');
										?>
										<input type="text" class="form-control" id="date" name="date" placeholder="Date" value="<?php echo $now; ?>" >
									</div>
								</div>
								
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">Remark</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="remark" name="remark" placeholder="Remark">
									</div>
								</div>
								<div class="modal-footer" style="padding: 19px 20px 0;">
									<button type="button" class="btn btn-primary" id="exitrec" onclick="exitStudent_submit()">Submit</button>	
									<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
									<!--<div class="page-header history" style="text-align: left;"><h3>History</h3></div>-->
									<div class="row" style="height:150px; padding: 2%;text-align: left;">
										<table class="table table-striped table-bordered" id="exitDetails" width="100%">
											<thead>
												<tr>
													<th class="text-center">Sl No</th>
													<th class="text-center">Exit Type</th>
													<th class="text-center">Date</th>
													<th class="text-center">Remark</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
        	</div>
        </div>
      	
      	<!-- Modal for Last Admission No-->
		<div class="modal fade" id="divModalAdmissionNo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop = "static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							&times;
						</button>
						<h4 class="modal-title"  style="color:#FFFFFF;">Admission No Info</h4>
					</div>
					<div class="modal-body" style="padding-bottom: 0px;">
						<div class="panel panel-default" style="border-width: 2px;border-color: #496cad; border-radius: 1%;">
							<div class="row" style="padding: 5%;" id="divAdmissionNoInfo">
    						</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
      	
      	<!-- Modal for Student Achievement -->
		<div class="modal fade" id="divModalAchievement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop = "static">
			<div class="modal-dialog" style="width: 50%">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							&times;
						</button>
						<h4 class="modal-title"  style="color:#FFFFFF;">Achievements</h4>
					</div>							
					<div class="modal-body">
						<form class="form-horizontal" role="form" id="frmAchievement" name="frmAchievement">
							<input  type="hidden" id="hidAchievementID" name="hidAchievementID"/>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Category</label>
								<div class="col-sm-8">
									<select class="form-control" id="cmbAchievementCategory" name="cmbAchievementCategory">
										<option value="">SELECT</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Sub Category</label>
								<div class="col-sm-8">
									<select class="form-control" id="cmbAchievementSubCategory" name="cmbAchievementSubCategory">
										<option value="">SELECT</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Level</label>
								<div class="col-sm-8">
									<select class="form-control" id="cmbAchievementLevel" name="cmbAchievementLevel">
										<option value="">SELECT</option>
										<option value="STATE">STATE</option>
										<option value="NATIONAL">NATIONAL</option>
										<option value="OTHERS">OTHERS</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Position</label>
								<div class="col-sm-8">
									<select class="form-control" id="cmbPosition" name="cmbPosition">
										<option value="">SELECT</option>
										<option value="1ST">1st</option>
										<option value="2ND">2nd</option>
										<option value="3RD">3rd</option>
										<option value="PARTICIPATED">Participated</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Date</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="txtAchievementDate" name="txtAchievementDate" placeholder="Date" readonly>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Venue</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="txtVenue" name="txtVenue" placeholder="Venue" title="Venue">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Conducted By</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="txtConductedBy" name="txtConductedBy" placeholder="Conducted By" title="Conducted By">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Remark</label>
								<div class="col-sm-8">
									<textarea class="form-control" id="txtAchievementRemark" name="txtAchievementRemark"></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary" id="btnSaveAchievement">Save</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>		
	 	
	 	<!-- Modal for Student Section Roll Information -->
		<div class="modal fade" id="divModalStudentSectionRollinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop = "static">
			<div class="modal-dialog" style="width: 50%">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							&times;
						</button>
						<h4 class="modal-title"  id="modatTitleStudentSectionRoll" style="color:#FFFFFF;">See Records</h4>
					</div>
					<div class="modal-body" style="padding-bottom: 0">
						<div class="panel panel-default" style="max-height: 450px; border-width: 2px;overflow-y: scroll;overflow-x: hidden;  border-color: #496cad; border-radius: 1%">
							<div class="row" style="height:150px; padding: 5%">
                				<table class="table table-bordered" id="dtblStudentSectionRoll" width="100%">
				                	<thead>
				                		<tr>
			                				<th class="text-center">#</th>
			                				<th class="text-center">Adm No</th><!--CODE WILL BE HIDE-->
			                				<th class="text-center">Name</th>
			                				<th class="text-center">Section</th>
											<th class="text-center">Roll</th>
											<th class="text-center" hidden>Student Status</th>
			                			</tr>
			                		</thead>
			                		<tbody>
			                		
			                		</tbody>
                				</table>	
    						</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Modal for Student Info -->
		<div class="modal fade" id="modalAddStudentDetails1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop = "static">
        	<div class="modal-dialog" style="width: 90%;">
        		<div class="modal-content">
        			<div class="modal-header tmp_bg_header">
        				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        				<h4 class="modal-title" id="lblStudentInfo" style="color:#FFFFFF;"></h4>
        				<!--<h4 class="modal-title" id="myModalLabel"><span id="lblStudentDetails"></span></h4>-->
        			</div>
        			<div class="modal-body">
            			<div class="row" style="padding: 5%;" id="divStudentInfo1">
    					</div>
        				<div class="panel with-nav-tabs panel-primaryx">
        					<div class="panel-heading" style="background: #fff;color: #000000;border-color: #fff;padding: 0;">
								<ul class="nav nav-tabs" role="tablist" >
									<li id="liprofile1" class="active"><a href="#profile1" data-toggle="tab">Profile</a></li>
									<li id="licontact1"><a href="#contact1" data-toggle="tab">Contact Details</a></li>
									<li id="lidocument1"><a href="#document1" data-toggle="tab">Documents</a></li>
									<li id="lidocument1"><a href="#achivements1" data-toggle="tab">Achivements</a></li>
									
									<li id="lisibling1" ><a href="#sibling1" data-toggle="tab">Sibling</a></li>
									<li id="lisibling1" ><a href="#previousSchoolDetails1" data-toggle="tab">PreviousSchoolDetails</a></li>
									
									<li id="limisc1" ><a href="#misc1" data-toggle="tab">Miscellaneous</a></li>
								</ul>
							</div>
							<div class="panel-body">
           						<div class="tab-content">
           							<!-- PROFILE -->
	           						<div class="tab-pane active" id="profile1">
	           							<div class="col-md-3">
											<div class="row" style="padding: 5%;" id="divProfileInfo1">
										</div>
										</div>
										<div class="col-md-4">
											<div class="row" style="padding: 5%;" id="divProfileInfo2">
										</div>
										</div>
	           						</div>	
									<!-- Contact -->
									<div class="tab-pane" id="contact1">
										<div class="col-md-6">
											<div class="row" style="padding: 5%;" id="divContactInfo1">
										</div>
										</div>
										<div class="col-md-4">
											<div class="row" style="padding: 5%;" id="divContactInfo2">
										</div>
										</div>
									</div>		
           						</div>
           					</div>
           				</div>
           				
           			</div>
           		</div>
           	</div>
        </div>	
		
		<!-- Modal for House Information -->
		<div class="modal fade" id="divModalHouseinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop = "static">
			<div class="modal-dialog" style="width: 50%">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							&times;
						</button>
						<h4 class="modal-title"  style="color:#FFFFFF;">House Info</h4>
					</div>
					<div class="modal-body">
						<div class="panel panel-default" style="min-height: 200px; border-width: 2px;overflow: hidden; border-color: #496cad; border-radius: 1%">
							<div class="row" style="height:150px; padding: 5%">
                				<table class="table table-bordered" id="dtblHouseInfo" width="50%">
				                	<thead>
				                		<tr>
			                				<th class="text-center">#</th>
			                				<th class="text-center">House Code</th><!--CODE WILL BE HIDE-->
			                				<th class="text-center">House Name</th>
			                				<th class="text-center">No Of Boys</th>
											<th class="text-center">No Of Girls</th>
			                			</tr>
			                		</thead>
			                		<tbody>
			                		
			                		</tbody>
                				</table>	
    						</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>				
		
		<!-- Modal for Class Section  Information -->
		<div class="modal fade" id="divModalClassSectionDetailsinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop = "static">
			<div class="modal-dialog" style="width: 50%">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							&times;
						</button>
						<h4 class="modal-title"  id="modalTitleClassSectionDetails" style="color:#FFFFFF;">See Records</h4>
					</div>
					<div class="modal-body"	 style="padding-bottom: 0"> 
						<div class="panel panel-default" style="max-height: 400px; border-width: 2px;overflow-y: scroll;overflow-x: hidden; border-color: #496cad; border-radius: 1%">
							<div class="row" style="height:150px; padding: 5%">
	            				<table class="table table-bordered" id="dtblCourseClassSection" width="100%">
				                	<thead>
				                		<tr>
			                				<th class="text-center">#</th>
			                				<th class="text-center">Section</th><!--CODE WILL BE HIDE-->
			                				<th class="text-center">Strength</th>
			                				<th class="text-center">Last Roll</th>
			                			</tr>
			                		</thead>
			                		<tbody>
			                		
			                		</tbody>
	            				</table>	
						</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	
	
	
	@include ('school.includes.footer')
	
    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}" />
    <input type="hidden" id="is_photo_required" value="yes" />
    
    <!--Date Picker-->
    <script type="text/javascript" src="<?php echo url('/');?>/public/school/daterangepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="<?php echo url('/');?>/public/school/daterangepicker/daterangepicker.js"></script>
    <link href="<?php echo url('/');?>/public/school/daterangepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    
	<!-- Choosen -->
	<link href="<?php echo url('/');?>/public/school/additional_js/chosen.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="<?php echo url('/');?>/public/school/additional_js/chosen.jquery.js"></script>
	<script type="text/javascript" src="<?php echo url('/');?>/public/school/additional_js/employee_subject_assign.js"></script>
	
	<script>
		var url = '<?php echo url('/');?>';
		var csrf_token = $('#csrf_token').val();
		
		$(document).ready(function(){
    		
    		$('#admissiondate, #enrollmentdate').datepicker({
				format: "dd-mm-yyyy",
			    autoclose: true,
				todayHighlight:true
			});
			$('#txtBirthDate').datepicker({
				format: "dd-mm-yyyy",
			    autoclose: true,
			    todayHighlight: true,
	   			maxDate: false
			});
			
    		$('#cmbSearchStudent').chosen();
			
			var is_photo_required = $('#is_photo_required').val();
			if(is_photo_required == 'yes')
			{
				page = 10;
				photo_visible = true;
			}
			else
			{
				page = 15;
				photo_visible = false;
			}
			//Session Start 
			var dtblStudentAssign = $('#dtblStudentAssign').DataTable({
				"lengthMenu": [[page,30,50,100], [page,30,50,100]],
				"sAjaxSource": url+"/school/students/api/SELECT_ALL_STUDENTS",
				"bProcessing": false,
				"bServerSide": true, 
				"bStateSave":false,
				"bPaginate": true,
		        "bLengthChange": true,
		        "bFilter": true,
		        "bSort": false,
		        "bInfo": true,
		        "pageLength": page,
		        "bAutoWidth": false, 
		        "bDestroy":true,
				"sDom":"<'row'<'col-xs-4'i><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-7' <'row' <'col-xs-7' >>><'col-xs-5'p>>",
				"aoColumns": 
				[
					{ "sName": "id","bVisible":false}, 
					{ "sName": "sl_no","sWidth":"5%"},
					{ "sName": "photo","sWidth":"8%","bVisible":photo_visible,
						"mRender":function(data, type, full) {
							
							var i = url+'/public/school/images/no-image.jpg';
							
							if(full.profile_image_url != null)
							{
								i = full.profile_image_url;
							}
							return "<a class='lightbox' href='#"+full[1]+"'><img class=' flipInX animated' src='"+i+"' style='width:36%;'></a><div class='lightbox-target' id='"+full[1]+"'><img src='"+i+"'/><a class='lightbox-close' href='#'></a></div>";
						}
					},
					{ "sName": "admno","sWidth":"8%"},
					{ "sName": "student Name"},
					{ "sName": "course","sWidth":"8%"},
					{ "sName": "class","sWidth":"6%"},
					{ "sName": "Status","sWidth":"6%"},
					{ "sName": "fathers_name","sWidth":"20%"},
					{ "sName": "mobile_no","sWidth": "8%"},
					{ "sName": "Action","sWidth": "18%",
						"mRender":function(data, type, full) {
							return "<button class='btn btn-sm btn-info flipInX animated' onclick='Update_PROFILE(event)' title='Edit'><i class='icon-edit'></i></button>&nbsp;<button class='btn btn-sm btn-warning flipInX animated' onclick='showInfo(event)' title='info'><i class='icon-info'></i></button>&nbsp;<button class='btn btn-sm btn-danger flipInX animated' onclick='showCetificate(event)' title='Certificate'><i class='icon-bookmark-empty'></i></button>&nbsp;<button class='btn btn-sm btn-success flipInX animated' onclick='StudentExit(event)' title='Exit'><i class='icon-bolt'></i></button>";
							}
					}
		        ],
		        'columnDefs': 
		        [
					{
				    	"targets": [1,2,3,5,6,7,9,10],
				      	"className": "text-center"
				 	}
				],
			});

			//SIBLINGS
			var dtblSiblings = $('#dtblSiblings').dataTable({
				"lengthMenu": [[15,30,50,100], [10,30,50,100]],
				"sAjaxSource": url+"/school/students/api/GET_SIBLINGS?student_code=",
				"bProcessing": false,
				"bServerSide": true, 
				"bStateSave":false,
				"bPaginate": true,
		        "bLengthChange": true,
		        "bFilter": true,
		        "bSort": false,
		        "bInfo": true,
		        "pageLength": 15,
		        "bAutoWidth": false, 
		        "bDestroy":true,
				"sDom":"<'row'<'col-xs-4'i><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-7' <'row' <'col-xs-7' >>><'col-xs-5'p>>",
		 		"aoColumns": [			
						 { "sName": "sl_no" ,"sWidth":"10%"},
						 { "sName": "student_code", "bVisible":false},//HIDING THE COLUMN
						 { "sName": "student_name","sWidth":"40%"},
						 { "sName": "course_name","sWidth":"10%"},
						 { "sName": "class_name","sWidth":"10%"},
						 { "sName": "section_name","sWidth":"10%"},
						 { "sName": "relation","sWidth":"10%"},
						 { "sName": "Action","sWidth": "18%",
								"mRender":function(data, type, full) {
									return "<button class='btn btn-sm btn-info flipInX animated' onclick='delete_sibling(event)' title='Edit'><i class='icon-edit'></i></button>";
									//return "<button class='btn btn-info custombtn' title='Delete' onclick='delete_sibling(event)'><i class='fa fa-trash fa-lg'></i></button>";
									}
							}
				        ],
				        'columnDefs': 
				        [      
							{
						    	"targets": [1,2,4,5,6],
						      	"className": "text-center"
						 	}
						],
					  
			});
			
			//make ajax call , check if duplicate ADMISSION NUMBER exist and if duplicate is there do :
	 		$('#admissionnumber').on("change",function(event)
			{
				var admissionnumber = $('#admissionnumber').val();
				
				$.ajax({
					url:url+"/school/students/api/CHKDUPLICATE",
					mType:"get",
					async: false,
					data:{ admissionnumber:admissionnumber},
					success:function(response)
					{
						// means the admission number exist so give an error message and clear the field
						if(parseInt(response) > 0)
						{
							toastr.info('Admission Number already Taken');
							$('#admissionnumber').val("");
							$('#admissionnumber').focus();
							return false;
						}
						else
						{
							return true;
						}

					},
					error:function()
					{
						toastr.error('Unable to process please contact support');
					}
				});
				
			});
			
			// Course
			$.ajax({
				url:url+"/school/master-page/api/SELECT_ALL_COURSE_AJAX",
				mType:"get",
				async:false,
				success:function(response)
				{
					var options = "";
					var defaultoption="<option selected value=''>Select Course</option>";

					$.each(response,function(i,data)
					{
						options = options + "<option value="+data.course_code+">"+data.course_name+"</option>";
					});

					$('#cmbCourseAdd,#cmbCourseFilter').html("");
					$('#cmbCourseAdd,#cmbCourseFilter').append(defaultoption);
					$('#cmbCourseAdd,#cmbCourseFilter').append(options);
					
					
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			
		
			});
			
			$('#cmbCourseFilter').on("change",function(event)
			{
				var course_code = $('#cmbCourseFilter').val();
				
				$.ajax({
					url:url+"/school/master-page/api/SELECT_ALL_CLASS_BY_COURSE_AJAX",
					mType:"get",
					async: false,
					data:{ course_code:course_code},
					success:function(response)
					{
						var options = "";
						var defaultoption="<option selected value='' >Select</option>";
						$.each(response,function(i,data)
						{
							options = options + "<option value="+data.class_code+">"+data.class_name+"</option>";
						});

						$('#cmbClassFilter').html("");
						$('#cmbClassFilter').append(defaultoption);
						$('#cmbClassFilter').append(options);
					},
					error:function()
					{
						toastr.error('Unable to process please contact support');
					}
				});
				
			});
			
			$('#cmbCourseAdd').on("change",function(event)
			{
				var course_code = $('#cmbCourseAdd').val();
				
				$.ajax({
					url:url+"/school/master-page/api/SELECT_ALL_CLASS_BY_COURSE_AJAX",
					mType:"get",
					async: false,
					data:{ course_code:course_code,_token:csrf_token},
					success:function(response)
					{
						var options = "";
						var defaultoption="<option selected value='' >Select</option>";
						$.each(response,function(i,data)
						{
							options = options + "<option value="+data.class_code+">"+data.class_name+"</option>";
						});

						$('#cmbClassAdd').html("");
						$('#cmbClassAdd').append(defaultoption);
						$('#cmbClassAdd').append(options);
					},
					error:function()
					{
						toastr.error('Unable to process please contact support');
					}
				});
			
			});
		
			$('#cmbClassFilter').on("change",function(event)
			{
				var course_code = $('#cmbCourseFilter').val();
				var class_code = $('#cmbClassFilter').val();
				
				$.ajax({
					url:url+"/school/master-page/api/SELECT_ALL_SECTION_BY_COURSE_CLASS_AJAX",
					mType:"get",
					async: false,
					data:{ course_code:course_code,class_code:class_code},
					success:function(response)
					{
						var options = "";
						var defaultoption="<option selected value='' >Select</option>";

						$.each(response,function(i,data)
						{
							options = options + "<option value="+data.section_code+">"+data.section_name+"</option>";
						});

						$('#cmbSectionFilter').html("");
						$('#cmbSectionFilter').append(defaultoption);
						$('#cmbSectionFilter').append(options);
					},
					error:function()
					{
						toastr.error('Unable to process please contact support');
					}
				});
			});
		
			$('#cmbClassAdd').on("change",function(event)
			{
				var course_code = $('#cmbCourseAdd').val();
				var class_code = $('#cmbClassAdd').val();
				
				$('#sectionAssignBtn').attr('disabled', true);
				if(class_code != "")
				{
					$('#sectionAssignBtn').attr('disabled', false);
				}
				
				$.ajax({
					url:url+"/school/master-page/api/SELECT_ALL_SECTION_BY_COURSE_CLASS_AJAX",
					mType:"get",
					async: false,
					data:{ course_code:course_code,class_code:class_code},
					success:function(response)
					{
						var options = "";
						var defaultoption="<option selected value='' >Select</option>";

						$.each(response,function(i,data)
						{
							options = options + "<option value="+data.section_code+">"+data.section_name+"</option>";
						});

						$('#cmbSectionAdd').html("");
						$('#cmbSectionAdd').append(defaultoption);
						$('#cmbSectionAdd').append(options);
					},
					error:function()
					{
						toastr.error('Unable to process please contact support');
					}
				});
			});
		
			//LOAD THE CATEGORY 1
			$.ajax({
				url:url+"/school/students/api/SELECT_CATEGORY_1_AJAX",
				mType:"get",
				async: false,
				success:function(response)
				{
					var options = "";
					var defaultoption = "<option selected value=''>Select</option>";
					$.each(response,function(i,data)
					{
						options = options + "<option value="+data.code+">"+data.code_desc+"</option>";
					});

					$('#cmbCategory1').html("");
					$('#cmbCategory1').append(defaultoption);
					$('#cmbCategory1').append(options);
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
		
			});
			
			//LOAD THE CATEGORY 2
			$.ajax({
				url:url+"/school/students/api/SELECT_CATEGORY_2_AJAX",
				mType:"get",
				async: false,
				success:function(response)
				{
					var options = "";
					var defaultoption="<option selected value=''>Select</option>";

					$.each(response,function(i,data)
					{
						options = options + "<option value="+data.code+">"+data.code_desc+"</option>";
					});

					$('#cmbCategory2').html("");
					$('#cmbCategory2').append(defaultoption);
					$('#cmbCategory2').append(options);
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
		
			});
			
			//LOAD ADDMISSION TYPE
			$.ajax({
				url:url+"/school/students/api/ADMISSION_TYPE_AJAX",
				mType:"get",
				async: false,
				success:function(response)
				{
					var options = "";
					var defaultoption="<option selected value=''>Select</option>";
					$.each(response,function(i,data)
					{
						options = options + "<option value="+data.code+">"+data.code_desc+"</option>";
					});

					$('#cmbAdmissionType').html("");
					$('#cmbAdmissionType').append(defaultoption);
					$('#cmbAdmissionType').append(options);
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			});
			
			//LOAD HOUSE
			$.ajax({
				url:url+"/school/students/api/SELECT_HOUSE_AJAX",
				mType:"get",
				async: false,
				success:function(response)
				{
					var options = "";
					var defaultoption="<option selected value=''>Select</option>";

					$.each(response,function(i,data)
					{
						options = options + "<option value="+data.house_code+">"+data.house_name+"</option>";
					});

					$('#cmbHouseName').html("");
					$('#cmbHouseName').append(defaultoption);
					$('#cmbHouseName').append(options);
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
		
			});
			
			//LOAD SERVICE CATEGORY
			$.ajax({
				url:url+"/school/students/api/SELECT_SERVICE_CATEGORY",
				mType:"get",
				async: false,
				success:function(response)
				{
					var options = "";
					var defaultoption="<option selected value=''>Select</option>";

					$.each(response,function(i,data)
					{
						options = options + "<option value="+data.code+">"+data.code_desc+"</option>";
					});

					$('#cmbServiceCategory').html("");
					$('#cmbServiceCategory').append(defaultoption);
					$('#cmbServiceCategory').append(options);
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			});
		
			//state name
			$.ajax({
				url:url+"/school/students/api/SELECT_STATE",
				mType:"get",
				async: false,
				success:function(response)
				{  
					var options = "";
					var defaultoption="<option selected value=''>Select</option>";

					$.each(response,function(i,data)
					{
						options = options + "<option value="+data.state_code+">"+data.state_name+"</option>";
					});

					$('#cmbState').html("");
					$('#cmbState').append(defaultoption);
					$('#cmbState').append(options);		
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			});
			
			//CITY
			$("#cmbState").change(function(){
				$.ajax({
					url:url+"/school/students/api/CITY_BY_STATE",
					mType:"get",
					async: false,
					data:{state_code:$("#cmbState").val()},
					success:function(response)
					{ 
						var options = "";
						var defaultoption="<option selected value=''>Select CITY</option>";

						$.each(response,function(i,data)
						{
							options = options + "<option value="+data.city_code+">"+data.city_name+"</option>";
						});

						$('#cmbCity').html("");
						$('#cmbCity').append(defaultoption);
						$('#cmbCity').append(options);			
					},
					error:function()
					{
						toastr.error('Unable to process please contact support');
					}
				});
			});		
			// get achievement category from database
			
			/*$.ajax({
				url:url+"/school/students/api/ACHIEVEMENT_CATEGORY",
				mType:"get",
				async: false,
				success:function(response)
				{  
					$('#cmbAchievementCategory').html(""); 
					var res1 = JSON.parse(response);
					var options = "<option value=''>Select</option>";
					$.each(res1.aaData,function(i,data){
						options += "<option value='"+data.category_code+"'>"+data.category_name+"</option>";			
					});		
					$('#cmbAchievementCategory').append(options);			
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			});*/
			
			// get achievement sub category from database
			$("#cmbAchievementCategory").change(function(){
				
				$.ajax({
					url:url+"/school/students/api/ACHIEVEMENT_SUB_CATEGORY",
					type:"post",
					data:{achievement_category:$("#cmbAchievementCategory").val(),_token:csrf_token},
					success:function(response)
					{
						$('#cmbAchievementSubCategory').html(""); 
						var res1 = JSON.parse(response);
						var options = "<option value=''>SELECT</option>";
						$.each(res1.aaData,function(i,data){
							options += "<option value='"+data.achievement_subcategory_code+"'>"+data.achievement_subcategory_name+"</option>";			
						});		
						$('#cmbAchievementSubCategory').append(options);			
					},
					error:function()
					{
						toastr.error('Unable to process please contact support');
					}
				});
			});
		});	
		
		function delete_sibling(event)
		{
			var oTable = $('#dtblSiblings').dataTable();
			var row;
			if(event.target.tagName == "BUTTON" || event.target.tagName == "A")
			  row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
			  row = event.target.parentNode.parentNode.parentNode;	
	    	
			  	$.ajax({
					url:url+"/school/students/api/DELETE_SIBLING",	
					mType:"POST",
					data:{student_code:oTable.fnGetData( row )[7]},
					success:function(response)
					{  
						var dtblSiblings = $("#dtblSiblings").DataTable();
						dtblSiblings.ajax.url(url+"/school/students/api/GET_SIBLINGS?student_code="+$('#hidStudentCode').val()).load();
						
						toastr.success("Deleted!", "This record has been deleted.", "success")
					},
					error:function()
					{
						toastr.error('Unable to process please contact support');
					}
				});
			
		}
		
		function showadmisinoninfo()
		{
			$('#divModalAdmissionNo').modal('show');
			$('#divAdmissionNoInfo').html('<center>\
											<p class="text-success" style="font-size: 18px;">Loading...</p>\
										</center>');			
			//ajax call to server
			$.ajax({
				url:url+"/school/students/api/GET_LAST_ADMISSION_NO",
				mType:"get",
				success:function(response){  
					var res1 = JSON.parse(response);
					var table_data = '<table class="table table-bordered" style="font-weight:bold;margin-bottom: 0px;" width:100%;>\
										<thead>\
											<tr style="background-color: #f2f2f2;color:#000;">';
					if(res1.aaData.length > 0)
					{
						table_data += '<td class="text-center" style="width:50%;">Last Admission No</td>\
										<td class="text-center" style="width:50%;">'+res1.aaData[0].admission_no+'</td>';			
					}		
					else
					{
						table_data += '<td class="text-center" style="width:50%;">Last Admission No</td>\
										<td class="text-center" style="width:50%;"></td>';			
					}
					table_data += '</tr>\
								</thead>\
							</table>';
					$('#divAdmissionNoInfo').html(table_data);			
				},  
				error:function(){
					toastr.error("We are unable to Process.Please contact Support");
				}
			});
		}
		
		function ShowhouseInfo()
		{
			$('#divModalHouseinfo').modal('show');
			
			var dtblHouseInfo = $('#dtblHouseInfo').dataTable({
				"sAjaxSource": url+"/school/students/api/HOUSE_INFO",
				"bPaginate": true,
		        "bLengthChange": false,
		        "bFilter": true,
		        "bSort": false,
		        "bInfo": true,
		        "bAutoWidth":false, // Disable the auto width calculation
		         "sDom":"<'row'<'col-xs-4'i><'col-xs-1'><'col-xs-7'f>r>t<'row'<'col-xs-6' <'row' <'col-xs-4 ' >>><'col-xs-6' p>>", 
		 		"aoColumns": 
		 			[			
						 { "sName": "sl_no" ,"sWidth":"10%"},
						 { "sName": "house_code", bVisible:false},//HIDING THE COLUMN
						 { "sName": "house_name","sWidth":"20%"},
						 { "sName": "boys","sWidth":"10%"},
						 { "sName": "girls","sWidth":"10%"},
					],  
			});
		}
		
		function filter()
		{
			var dtblStudentAssign = $("#dtblStudentAssign").DataTable();
			dtblStudentAssign.ajax.url(url+"/school/students/api/SELECT_ALL_STUDENTS?courseFilter="+$('#cmbCourseFilter').val()+"&classFilter="+$('#cmbClassFilter').val()+"&admissionNoFilter="+$('#cmbAdmissionFilter').val()+"&sectionFilter="+$('#cmbSectionFilter').val()+"&statusFilter="+$('#cmbStatusFilter').val()).load();
		}
		
		function add_student()
		{

			
			$('#licontact').hide();
			$('#lidocument').hide();
			$('#lidisciplinary').hide();
			$('#lihealth').hide();
			$('#liselfawareness').hide();
			$('#lisiblings').hide();
			$('#lipreschooldetails').hide();
			$('#limisc').hide();
			$('#liachivements').hide();
			$('#lisibling').hide();
			

			$("#btnOpenFeeModal").html("SAVE");
			$('#hidStudentCode').val(""); //blank for add
		
			$('#txtSchoolGroupRegdNo').val("");
			$('#txtSchoolRegdNo').val("");
			$('#admissionnumber').val("");
			$('#studentname').val("");
			$('#coursename').val("");
			$('#classname').val("");
			$('#cmbSection').val("");
			$('#txtRollNo').val("");
			
			$('#birthdate').val("");
			$('#fathersname').val("");
			$('#mothersname').val("");
			$('#cmbAdmissionType').val("");
			$('#regdmobileno').val("");
			$('#alternatemobileno').val("");
			$('#emailid').val("");
			$('#address').val("");
			$('#admissiondate').val("");
			$("#lblStudentInfo").html("Add Student");
			$('#modalAddStudentDetails').modal('show');
			//$('#licontact').attr('class', 'disabled');
			//$('#lidocument').attr('class', 'disabled');
			//$('#liachievements').attr('class', 'disabled');
			// $('#lidisciplinary').attr('class', 'disabled');
			// $('#lihealth').attr('class', 'disabled');
			// $('#liselfawareness').attr('class', 'disabled');
			// $('#lisiblings').attr('class', 'disabled');
			//$('#lipreschooldetails').attr('class', 'disabled');
			//$('#limisc').attr('class', 'disabled');
			$('#modalAddStudentDetails').on('shown.bs.modal', function () 
			{ 
				$('#admissionnumber').focus(); // Focusing the textbox
				$('a[href="#profile"]').tab('show');
				
			})
			$('#modalAddStudentDetails').on('hidden.bs.modal', function (e) {
			  $(this)
			    .find("input,textarea,select")
			       .val('')
			       .end()
			    .find("input[type=checkbox], input[type=radio]")
			       .prop("checked", "")
			       .end();
			})
			$('#modalAddStudentDetails').modal('show');
		} 
		
		function add_acvivements()
		{
			$('#divModalAchievement').modal('show');
		}
		
		function Delete_PROFILE(event)
		{
			var oTable = $('#dtblStudentAssign').dataTable();			
			$(oTable.fnSettings().aoData).each(function (){
				$(this.nTr).removeClass('success');
			});
			
			var row;
			if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
			$(row).addClass('success');
			
			var admissionnumber= oTable.fnGetData(row)[2];
			alert(admissionnumber);
			$.ajax({
				url:url+"/school/students/api/Delete_STUDENT",
				type:"post",
				data:{admissionnumber:admissionnumber,_token:csrf_token},
				cache: false,
			    contentType: false,
			    processData: false,
				success:function(result){  
					toastr.success('Delete Succssfully');
					
				},
				error:function(responsedata)
				{
					
				}
			});
		}
		
		function AddSiblings(event)
		{
			if($("#cmbSearchStudent").chosen().val() == '')
	    	{
	    		toastr.info("Student not selected.");
	    		return;
	    	}
	    	var student_code = $('#hidStudentCode').val();
	    	var sibling_student_code = $('#cmbSearchStudent').chosen().val();
	    	var formdata = {
					student_code:student_code,
					sibling_student_code:sibling_student_code
				};
			
		   	//ajax call to server
			$.ajax({
				url:url+"/school/students/api/INSERT_SIBLING",	
				mType:"POST",
				data:{student_code:student_code,sibling_student_code:sibling_student_code},
				success:function(responsedata){
					var result = jQuery.parseJSON(responsedata);
					if(result.dbStatus == 'SUCCESS')
					{
						toastr.success(result.dbMessage);
						var dtbl = $("#dtblSiblings").DataTable();
						dtbl.ajax.url(url+"/school/students/api/GET_SIBLINGS?student_code="+$('#hidStudentCode').val()).load();
						var $select = $('#cmbSearchStudent').selectize();
						var control = $select[0].selectize;
						control.clear();
					}
					else
					{
						toastr.info(result.dbMessage);
						toastr.success(result.dbMessage);
						var dtbl = $("#dtblSiblings").DataTable();
						dtbl.ajax.url(url+"/school/students/api/GET_SIBLINGS?student_code="+$('#hidStudentCode').val()).load();
					}
				},  
				error:function(){
					//alert("We are unable to Process.Please contact Support");
				}
			}); 
		}
		
		// When edit icon on the list
		function Update_PROFILE(event)
		{
			$('#licontact').show();
			$('#lidocument').show();
			$('#lidisciplinary').show();
			$('#lihealth').show();
			$('#liselfawareness').show();
			$('#lisiblings').show();
			$('#lipreschooldetails').show();
			$('#limisc').show();
			$('#liachivements').show();
			$('#lisibling').show();

			$("#btnOpenFeeModal").html("UPDATE");
			var oTable = $('#dtblStudentAssign').dataTable();			
			$(oTable.fnSettings().aoData).each(function (){
				$(this.nTr).removeClass('success');
			});
			
			var row;
			if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
			$(row).addClass('success');
			
			$('#admissionnumber').val(oTable.fnGetData(row)[2]);
			$('#studentname').val(oTable.fnGetData(row)[3]);
			$('#gender').val(oTable.fnGetData(row)[9]);
			//$('#gender').val(options);
			$('#cmbBloodgroup').val(oTable.fnGetData(row)[21]);
			$('#txtBirthDate').val(oTable.fnGetData(row)[15]);
			$('#fathersname').val(oTable.fnGetData(row)[7]);
			$('#mothersname').val(oTable.fnGetData(row)[18]);
			$('#regdmobileno').val(oTable.fnGetData(row)[8]);
			$('#cmbCourseAdd').val(oTable.fnGetData(row)[11]);
			$('#cmbClassAdd').val(oTable.fnGetData(row)[12]);//12
			$('#cmbSectionAdd').val(oTable.fnGetData(row)[13]);
			$('#txtRollNo').val(oTable.fnGetData(row)[17]);
			$('#txtRegistrationno').val(oTable.fnGetData(row)[25]);
			$('#cmbCategory1').val(oTable.fnGetData(row)[22]);
			$('#cmbCategory2').val(oTable.fnGetData(row)[23]);
			$('#cmbAdmissionType').val(oTable.fnGetData(row)[24]);
			$('#admissiondate').val(oTable.fnGetData(row)[16]);
			$('#enrollmentdate').val(oTable.fnGetData(row)[19]);
			$('#cmbHouseName').val(oTable.fnGetData(row)[26]);
			
			$('#alternatemobileno').val(oTable.fnGetData(row)[20]);
			$('#cmbServiceCategory').val(oTable.fnGetData(row)[27]);
			$('#emailid').val(oTable.fnGetData(row)[28]);
			$('#address').val(oTable.fnGetData(row)[29]);
			$('#txtPermanentAddress').val(oTable.fnGetData(row)[30]);
			$('#cmbState').val(oTable.fnGetData(row)[32]);
			$('#cmbCity').val(oTable.fnGetData(row)[31]);
			$("#lblStudentInfo").html(oTable.fnGetData( row )[3]+' ('+$('#admissionnumber').val()+')');
			
			$('#hidStudentCode').val(oTable.fnGetData(row)[10]);
			
			var course_code = $('#cmbCourseAdd').val();
			
			$.ajax({
				url:url+"/school/master-page/api/SELECT_ALL_CLASS_BY_COURSE_AJAX",
				mType:"get",
				async: false,
				data:{ course_code:course_code},
				success:function(response)
				{
					var options = "";
					var defaultoption="<option selected value='' >Select</option>";

					$.each(response,function(i,data)
					{
						if(data.class_name==oTable.fnGetData(row)[5])
						{
							defaultoption ="<option value="+data.class_code+">"+data.class_name+"</option>";
						}
						else
						{
							options = options + "<option value="+data.class_code+">"+data.class_name+"</option>";	
						}
					});

					$('#cmbClassAdd').html("");
					$('#cmbClassAdd').append(defaultoption);
					$('#cmbClassAdd').append(options);
					
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			});
			
			//edit time this ajax is used for loading section(akash swain)
			var course_code = $('#cmbCourseAdd').val();
			var class_code = $('#cmbClassAdd').val();
			
			$.ajax({
				url:url+"/school/master-page/api/SELECT_ALL_SECTION_BY_COURSE_CLASS_AJAX",
				mType:"get",
				async: false,
				data:{ course_code:course_code,class_code:class_code},
				success:function(response)
				{
					var options = "";
					var defaultoption="<option selected value='' >Select Section</option>";

					$.each(response,function(i,data)
					{
						if(data.section_code==oTable.fnGetData(row)[13])
						{
							defaultoption ="<option value="+data.section_code+">"+data.section_name+"</option>";
						}
						else
						{
							options = options + "<option value="+data.section_code+">"+data.section_name+"</option>";	
						}
					});

					$('#cmbSectionAdd').html("");
					$('#cmbSectionAdd').append(defaultoption);
					$('#cmbSectionAdd').append(options);
					
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			});
			
			$.ajax({
				url:url+"/school/students/api/CITY_BY_STATE",
				mType:"get",
				async: false,
				data:{state_code:$("#cmbState").val()},
				success:function(response)
				{ 
					var options = "";
					var defaultoption="<option selected value=''>Select CITY</option>";

					$.each(response,function(i,data)
					{
						if(data.city_code==oTable.fnGetData(row)[31])
						{
							defaultoption ="<option value="+data.city_code+">"+data.city_name+"</option>";
						}
						else
						{
							options = options + "<option value="+data.city_code+">"+data.city_name+"</option>";	
						}
					});

					$('#cmbCity').html("");
					$('#cmbCity').append(defaultoption);
					$('#cmbCity').append(options);	 		
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			});
			var admissionnumber = $('#admissionnumber').val();
			$.ajax({
				url:url+"/school/students/api/SELECT_SIBLINGS",
				mType:"get",
				async: false,
				data:{admissionnumber:admissionnumber},
				success:function(response)
				{ console. log(response);
					var options = "";
					var defaultoption="<option selected value=''>Select CITY</option>";

					$.each(response,function(i,data)
					{
						options = options + "<option value="+data.admission_no+">"+data.student_name+'('+data.admission_no+")</option>";
					});

					$('#cmbSearchStudent').html("");
					$('#cmbSearchStudent').append(defaultoption);
					$('#cmbSearchStudent').append(options);		
				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			});
			
			var student_code = $('#hidStudentCode').val();
			Show_MISC_Details(student_code);
			
			$('#modalAddStudentDetails').modal('show');
		}
		
		function showSectionDetails()
		{
			if($('#cmbCourseAdd').val() == '' )
			{
				toastr.info('Please Select Course');
				$('#cmbCourseAdd').focus();
			}
			else if($('#cmbClassAdd').val() == '')
			{
				toastr.info('Please Select Class');
				$('#cmbClassAdd').focus();
				
			}
			else
			{
				var selected_class_code = $('#cmbClassAdd').val();
				
				var selected_course_code = $('#cmbCourseAdd').val();
				var dtblCourseClassSection = $('#dtblCourseClassSection').DataTable();
				
				dtblCourseClassSection.ajax.url(url+"/school/students/api/GET_ASSIGNED_SECTION_DETAILS?course="+selected_course_code+'&class='+selected_class_code).load();
				var selected_section_name = $("#cmbSection option:selected").text();
				var selected_class_name = $("#classname option:selected").text();
				var selected_course_name = $("#coursename option:selected").text();
				$('#modalTitleClassSectionDetails').text(' Assigned Section List for Course-'+selected_course_name+', Class-'+selected_class_name);
				$('#divModalClassSectionDetailsinfo').modal('show');
			}
		}
    	
    	function showSectionRolls()
		{
			if($('#cmbSectionAdd').val() == '')
			{
				toastr.info('A student must have a section for roll no allotment. Please select section');
				$('#cmbSectionAdd').focus();
			}
			else
			{
				var selected_section_code = $('#cmbSectionAdd').val();
				var selected_class_code = $('#cmbClassAdd').val();
				var selected_course_code = $('#cmbCourseAdd').val();
				var dtblStudentSectionRoll = $('#dtblStudentSectionRoll').DataTable();
				dtblStudentSectionRoll.ajax.url(url+"/school/students/api/GET_ASSIGNED_STUDENT_SECTION_ROLLNO?course="+selected_course_code+'&class='+selected_class_code+'&section='+selected_section_code).load();
				
				var selected_section_name = $("#cmbSectionAdd option:selected").text();
				var selected_class_name = $("#cmbClassAdd option:selected").text();
				var selected_course_name = $("#cmbCourseAdd option:selected").text();
				$('#modatTitleStudentSectionRoll').text(' Assigned Student-Roll No List for Course-'+selected_course_name+', Class-'+selected_class_name+', Section-'+selected_section_name);
				$('#divModalStudentSectionRollinfo').modal('show');
			}
		}
		
    	function save_contact_data()
    	{
    		if($('#admissionnumber').val() == '' )
			{
			    toastr.info('Please save profile first.');
			    $('#admissionnumber').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
    		else if($('#address').val() == '' )
			{
			    toastr.info('Please enter address.');
			    $('#address').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#txtPermanentAddress').val() == '' )
			{
			    toastr.info('Please enter permanent address.');
			    $('#txtPermanentAddress').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#cmbState').val() == '' )
			{
			    toastr.info('Please select state.');
			    $('#state').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#cmbCity').val() == '' )
			{
			    toastr.info('Please select city.');
			    $('#cmbCity').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else
			{
				var formData = new FormData();			
				formData.append('admissionnumber', $('#admissionnumber').val());
				formData.append('alternatemobileno', $('#alternatemobileno').val());
				formData.append('email_id', $('#emailid').val());
				formData.append('address', $('#address').val());
				formData.append('permanent_address', $('#txtPermanentAddress').val());
				formData.append('state', $('#cmbState').val());
				formData.append('city', $('#cmbCity').val());
				formData.append('_token', csrf_token);
				$.ajax({
					url:url+"/school/students/api/ADD_CONTACT",
					type:"post",
					data:formData,
					cache: false,
				    contentType: false,
				    processData: false,
					success:function(result){  
						var dtbl = $("#dtblStudentAssign").DataTable();
		 			dtbl.ajax.reload(null, false);
						 toastr.success('Data saved successfully');
					},
					error:function(responsedata)
					{
						toastr.error('Some error occured');
					}
				});	
			}	
		}
    	
    	function save_student_data()
    	{
    		if($('#admissionnumber').val().trim() == '' )
			{
			    toastr.info('Please enter admissionnumber.');
			    $('#admissionnumber').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
				
			}
			else if($('#studentname').val().trim() == '' )
			{
			    toastr.info('Please enter Studentname.');
			    $('#studentname').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#gender').val().trim() == '' )
			{
			    toastr.info('Please Select Gender');
			    $('#gender').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#cmbBloodgroup').val().trim() == '' )
			{
			    toastr.info('Please Select Bloodgroup');
			    $('#cmbBloodgroup').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#txtBirthDate').val().trim() == '' )
			{
			    toastr.info('Please enter BirthDate.');
			    $('#txtBirthDate').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
    		else if($('#fathersname').val().trim() == '' )
			{
			    toastr.info('Please enter fathersname.');
			    $('#fathersname').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#mothersname').val().trim() == '' )
			{
			    toastr.info('Please enter mothersname.');
			    $('#mothersname').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#regdmobileno').val().trim() == '' )
			{
			    toastr.info('Please enter register mobile number.');
			    $('#regdmobileno').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if(!(/^\d{10}$/.test($('#regdmobileno').val())))
			{
				toastr.info("Invalid register mobile number; must be ten digits")
			    $('#regdmobileno').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#cmbSectionAdd').val().trim() != '' && $('#txtRollNo').val().trim() == '')
			{
				toastr.info('Please enter a roll no to the student.');
				$('#txtRollNo').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#cmbSectionAdd').val().trim() == '' && $('#txtRollNo').val().trim()!= '')
			{
				toastr.info('A student cannot have a roll no without section assigned. Please Select a Section');
				$('#cmbSection').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#cmbCourseAdd').val().trim() == '' )
			{
			    toastr.info('Please Select Course.');
			    $('#cmbCourseAdd').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#cmbClassAdd').val().trim() == '')
			{
				toastr.info('Please Select Class.');
				$('#cmbClassAdd').focus();
			}
			else if($('#cmbCategory1').val().trim() == '' )
			{
			    toastr.info('Please Select Category1.');
			    $('#cmbCategory1').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#cmbCategory2').val().trim() == '' )
			{
			    toastr.info('Please Select Category2.');
			    $('#cmbCategory2').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#cmbAdmissionType').val().trim() == '' )
			{
			    toastr.info('Please Select AdmissionType.');
			    $('#cmbAdmissionType').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#admissiondate').val().trim() == '' )
			{
			    toastr.info('Please Select Admissiondate.');
			    $('#admissiondate').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
			else if($('#enrollmentdate').val().trim() == '' )
			{
			    toastr.info('Please Select Enrollmentdate.');
			    $('#enrollmentdate').focus();
				$("#btnOpenFeeModal").removeAttr('disabled');
			}
    		else
    		{
    			if($('#cmbSectionAdd').val().trim() == '')
    			{
					$('#cmbSectionAdd').val('NULL');
				}
				if($('#txtRollNo').val().trim() == '')
    			{
					$('#txtRollNo').val('NULL');
				}
	    		var formData = new FormData();
				
				formData.append('admissionnumber', $('#admissionnumber').val());
				formData.append('studentname', $('#studentname').val());
				formData.append('gender', $('#gender').val());
				formData.append('cmbBloodgroup', $('#cmbBloodgroup').val());
				formData.append('txtBirthDate', $('#txtBirthDate').val());
				formData.append('fathersname', $('#fathersname').val());
				formData.append('mothersname', $('#mothersname').val());
				formData.append('regdmobileno', $('#regdmobileno').val());
				formData.append('course_code', $('#cmbCourseAdd').val());
				formData.append('class_code', $('#cmbClassAdd').val());
				formData.append('section_code', $('#cmbSectionAdd').val());
				formData.append('roll_no', $('#txtRollNo').val());
				formData.append('category1', $('#cmbCategory1').val());
				formData.append('category2', $('#cmbCategory2').val());
				formData.append('admissionType', $('#cmbAdmissionType').val());
				formData.append('admissiondate', $('#admissiondate').val());
				formData.append('enrollmentdate', $('#enrollmentdate').val());
				formData.append('hidenrollmentdate', $('#hidenrollmentdate').val());//hidden enrollmentdate
				formData.append('registrationno', $('#txtRegistrationno').val());
				formData.append('house', $('#cmbHouseName').val());
				formData.append('serviceCategory', $('#cmbServiceCategory').val());
				
				formData.append('alternatemobileno', $('#alternatemobileno').val());
				formData.append('email_id', $('#emailid').val());
				formData.append('address', $('#address').val());
				formData.append('permanent_address', $('#txtPermanentAddress').val());
				formData.append('state', $('#cmbState').val());
				formData.append('city', $('#cmbCity').val());
				
				formData.append('_token', csrf_token);
				
				$.ajax({
					url:url+"/school/students/api/ADD_STUDENT",
					type:"post",
					data:formData,
					cache: false,
				    contentType: false,
				    processData: false,
					success:function(result){
						$('#licontact').attr('class', '');
						$('#lidocument').attr('class', '');
						$('#liachievements').attr('class', '');
						$('#lihealth').attr('class', '');
						$('#liselfawareness').attr('class', '');
						$('#lisiblings').attr('class', '');
						$('#lipreschooldetails').attr('class', '');
						$('#limisc').attr('class', '');
						$("#misc").html('<i class="fa fa-cog fa-spin"></i> Loading...');  
						
						var dtbl = $("#dtblStudentAssign").DataTable();
			 			dtbl.ajax.reload(null, false);
			 			
			 			toastr.success('Data Saved Successfully');

						 $('#licontact').show();
						$('#lidocument').show();
						$('#lidisciplinary').show();
						$('#lihealth').show();
						$('#liselfawareness').show();
						$('#lisiblings').show();
						$('#lipreschooldetails').show();
						$('#limisc').show();
						$('#liachivements').show();
						$('#lisibling').show();
					},
					error:function(responsedata)
					{
						toastr.error('Some error occured');
					}
				});
			 
			}
    	}
    	
    	function upload_document(document_type)
		{
			if($("#file"+document_type).val() == '')
			{
				toastr.error("Please select a file.");
				return;
			}
			
			var formData = new FormData(document.getElementById("frmDocumentUpload"));
				formData.append('admissionnumber', $('#admissionnumber').val());
				formData.append('_token', csrf_token);
			$.ajax({
		        //url: "db_studentmanage.php?type=UPLOAD_DOCUMENT&student_code="+$('#hidStudentCode').val(),
		        url: url+"/school/students/api/UPLOAD_DOCUMENT",
		        type: 'POST',
		        enctype: 'multipart/form-data',
		        data: formData,
		        cache: false,
		        contentType: false,
		        processData: false,
		        success: function () {
	                toastr.success("Successfully Uploaded");
	            }
		    });
		}
		
		function readURL(width,height,id) 
		{
		    if (document.getElementById("file"+id).files && document.getElementById("file"+id).files[0]) {
		        var reader = new FileReader();
		           
		        reader.onload = function (e) {
		            $('#img'+id).attr('src', e.target.result);
					$('#img'+id).attr('height',height);
					$('#img'+id).attr('width',width);
		        }
		        
		        reader.readAsDataURL(document.getElementById("file"+id).files[0]);
		    }
		}
	
		function showImage(id,width,height,size)
		{
			var file = document.getElementById("file"+id).files[0];
			var sFileName = file.name;
		    var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
		    var iFileSize = file.size;
		    if (sFileExtension == "jpg" || sFileExtension == "jpeg" || sFileExtension == "png" )
			{ 
				if(iFileSize <= size*1024)
				{
				  	document.getElementById("divMessage"+id).innerHTML="";
				  	readURL(width,height,id);
				}
				else
				{
					document.getElementById("divMessage"+id).innerHTML="Error : File size exceeds "+size+" KB";
					$("#file"+id).val("");
					$('#img'+id).attr('src','');
					$('#img'+id).attr('height','0');
					$('#img'+id).attr('width','0');
				}
		    }
			else
			{
				document.getElementById("divMessage"+id).innerHTML="Error : Invalid File Format";
				$('#fileGrade').val("");
				$('#img'+id).attr('src','');
				$('#img'+id).attr('height','0');
				$('#img'+id).attr('width','0');
			}
		}
		
		function save_previous_school_details_data()
		{
			var formData = new FormData();
			
			formData.append('admissionnumber', $('#admissionnumber').val());		
			formData.append('txtschoolname', $('#txtschoolname').val());
			formData.append('txttcno', $('#txttcno').val());
			formData.append('issuedate', $('#issuedate').val());
			formData.append('txtpassedexamclass', $('#txtpassedexamclass').val());
			formData.append('txtmarksperc', $('#txtmarksperc').val());
			//formData.append('optradio', $('#optradio').val());
			formData.append('optradio', $("input[name='optradio']:checked").val());
			formData.append('txtmodeofinst', $('#txtmodeofinst').val());
			formData.append('txtexambody', $('#txtexambody').val());
			formData.append('txtrollno', $('#txtrollno').val());
			formData.append('txtregno', $('#txtregno').val());
			formData.append('_token', csrf_token);
			
			$.ajax({
				url:url+"/school/students/api/SAVE_PREVIOUS_SCHOOL_DETAILS",
				type:"post",
				data:formData,
				cache: false,
			    contentType: false,
			    processData: false,
				success:function(result){  
					
					
				},
				error:function(responsedata)
				{
					
				}
			});
		}
		
		function showInfo(event)
    	{
    		var oTable = $('#dtblStudentAssign').dataTable();			
			$(oTable.fnSettings().aoData).each(function (){
				$(this.nTr).removeClass('success');
			});
			
			var row;
			if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
			$(row).addClass('success');
			
			var admissionnumber= oTable.fnGetData(row)[2];
			//alert(admissionnumber);
			$('#modalAddStudentDetails1').modal('show');
			$('#divProfileInfo1').html('');
			$('#divProfileInfo2').html('');
			$('#divContactInfo1').html('');
			$('#divContactInfo2').html('');
			$('#divStudentInfo1').html('');
			$.ajax({
				url:url+"/school/students/api/GET_PROFILE_INFO",
				mType:"get",
				async: false,
				data:{ admission_no:admissionnumber},
				success:function(response){  
				
					var res1 = JSON.parse(response);
					
					var table_data = '<table class="table no-border" border="0" cellpadding="0" cellspacing="0">\
										<thead>\
											<tr>';
					var table_data1 = '<table class="table no-border" border="0" cellpadding="0" cellspacing="0">\
										<thead>\
											<tr>';	
					var table_data2 = '<table class="table no-border" border="0" cellpadding="0" cellspacing="0">\
										<thead>\
											<tr>';
					var table_data3 = '<table class="table no-border" border="0" cellpadding="0" cellspacing="0">\
										<thead>\
											<tr>';	
					var table_data0='<table style="width:50%;align:center;" border="0">\
										<tbody>\
										<thead>\
											<tr>';																							
					$.each(res1.aaData,function(i,data){
						table_data0 += '<td style="font-size:20px;font-weight: bold" colspan="2"></td>\
										<td>'+data.student_name+'</td></tr>';
						table_data0 += '<tr><td style="text-align: left;font-size:15px;font-weight: bold;>Admission No:</td>\
									<td>'+data.admission_no+'</td>';
						table_data0 += '<tr><td  style="width:50%;">Class :</td>\
									<td  style="width:50%;">'+data.class_name+'</td>';
						table_data0 +='<tr><td  style="width:50%;">Mobile :</td>\
									<td  style="width:50%;">'+data.register_mobile_no+'</td>';	
									
						table_data += '<td style="width:50%;">Admission No</td>\
										<td style="width:50%;">'+data.admission_no+'</td></tr>';
						table_data += '<tr><td style="width:50%;">Course</td>\
									<td  style="width:50%;">'+data.course_name+'</td>';
						table_data += '<tr><td  style="width:50%;">Gender</td>\
									<td  style="width:50%;">'+data.gender+'</td>';
						table_data +='<tr><td  style="width:50%;">Father Name</td>\
									<td  style="width:50%;">'+data.fathers_name+'</td>';
						table_data +='<tr><td  style="width:50%;">Category 1</td>\
									<td  style="width:50%;">'+data.category_1+'</td>';	
						table_data1 +='<td style="width:50%;">Name</td>\
										<td style="width:50%;">'+data.student_name+'</td></tr>';	
						table_data1 += '<tr><td style="width:50%;">Class / Section / Roll</td>\
										<td  style="width:50%;">'+data.class_name+' / '+data.section_name+' / '+data.roll_no+'</td>';						
						table_data1 +='<tr><td style="width:50%;">D.O.B</td>\
										<td style="width:50%;">'+data.birth_date+'</td>';
						table_data1 +='<tr><td style="width:50%;">Date of Admission</td>\
										<td style="width:50%;">'+data.admission_date+'</td>';
						table_data2 += '<td style="width:50%;">Mother Name</td>\
										<td style="width:50%;">'+data.mothers_name+'</td></tr>';
						table_data2 += '<tr><td style="width:50%;">Alt Contact No</td>\
									<td  style="width:50%;">'+data.alternate_contact_no+'</td>';
						table_data2 += '<tr><td  style="width:50%;">Present Address</td>\
									<td  style="width:50%;">'+data.address+'</td>';
						table_data2 +='<tr><td  style="width:50%;">Permanent Address</td>\
									<td  style="width:50%;">'+data.permanent_address+'</td>';
						table_data3 += '<td style="width:50%;">Email</td>\
										<td style="width:50%;">'+data.email_id+'</td></tr>';
						table_data3 += '<tr><td style="width:50%;">State</td>\
									<td  style="width:50%;">'+data.state_name+'</td>';
						table_data3 += '<tr><td  style="width:50%;">District</td>\
									<td  style="width:50%;">'+data.state_name+'</td>';
						table_data3 +='<tr><td  style="width:50%;">City</td>\
									<td  style="width:50%;">'+data.city_name+'</td>';																	
					});	
					table_data0 += '</tr>\
								</thead>\
							</table>';
					table_data += '</tr>\
								</thead>\
							</table>';
					table_data1 += '</tr>\
								</thead>\
							</table>';	
					table_data2 += '</tr>\
								</thead>\
							</table>';
					table_data3 += '</tr>\
								</thead>\
							</table>';	
							
							
												
					$('#divStudentInfo1').html(table_data0);			
					$('#divProfileInfo1').html(table_data);			
					$('#divProfileInfo2').html(table_data1);			
					$('#divContactInfo1').html(table_data2);			
					$('#divContactInfo2').html(table_data3);			
				},  
				error:function(){
					toastr.error("We are unable to Process.Please contact Support");
				}
			});								
    	}
    	
    	function showCetificate(event)
    	{
    		var oTable = $('#dtblStudentAssign').dataTable();			
			$(oTable.fnSettings().aoData).each(function (){
				$(this.nTr).removeClass('success');
			});
			
			var row;
			if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
			$(row).addClass('success');
			
			var admissionnumber= oTable.fnGetData(row)[2];
			var student_code= oTable.fnGetData(row)[10];
			
    		window.open(url+"/school/exam/STU_CERTIFICATE?studentcode="+student_code,"page","left=0,top=0,width=1300,height=650,menubar=0,toolbar=0,scrollbars=1").focus();
      			
    	}
    	
    	function StudentExit(event)
    	{
    		var oTable = $('#dtblStudentAssign').dataTable();			
			$(oTable.fnSettings().aoData).each(function (){
				$(this.nTr).removeClass('success');
			});
			
			var row;
			if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
			$(row).addClass('success');
			
			//console.log(oTable.fnGetData(row));
			
			var admission_no= oTable.fnGetData(row)[2];
			var student_code= oTable.fnGetData(row)[10];
			var course_code= oTable.fnGetData(row)[11];
			var class_code= oTable.fnGetData(row)[12];
			var is_exit= oTable.fnGetData(row)[14];
			var course_name = oTable.fnGetData(row)[4];
			var class_name = oTable.fnGetData(row)[5];
			
			var heading = 'Student Control For - ('+admission_no+') - '+course_name+' - '+class_name;
		
			$('#lbl_student_cotrol_heading').html(heading);
			
			$('#admissionno').val(admission_no);
			$('#name').val(course_name);
			$('#classn').val(class_name);
			$('#studentCodeunique').val(student_code);//GETTING VALUE FOR HIDDEN COLUMN
			$('#classCodeunique').val(class_code);//GETTING VALUE FOR HIDDEN COLUMN
			$('#courseCodeunique').val(course_code);//GETTING VALUE FOR HIDDEN COLUMN
			$('#admissionNounique').val(admission_no);//GETTING VALUE FOR HIDDEN COLUMN
			
			
			var exitDetailstable = $('#exitDetails').dataTable({
			"sAjaxSource": url+"/school/students/api/EXIT_DETAILS?student_code="+student_code,
			"bPaginate": true,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bSort": false,
	        "bInfo": true,
	        "bAutoWidth":false,
	        "bDestroy": true,   
	        "sDom":"<'row'<'col-xs-4'i><'col-xs-1'><'col-xs-7'f>r>t<'row'<'col-xs-6' <'row' <'col-xs-4 ' >>><'col-xs-6' p>>",
			"aoColumns": [    
	                       { "sName": "sl_no","sWidth": "15%" },
						   { "sName": "exit_type","sWidth": "25%"},
						   { "sName": "date","sWidth": "20%"},
						   { "sName": "remark","sWidth": "40%"}
	              	     ],
	        'columnDefs': 
	        [
				{
			    	"targets": [0,2],
			      	"className": "text-center"
			 	}
			],        
		});
			
			get_status_Details(is_exit);
			$('#exitModal').modal('show');
			$('#remark').val("");
			
			
    	}
    	
    	function get_status_Details(status)
		{
			$.ajax({
				url:url+"/school/students/api/GET_STATUS_DETAILS_AJAX",	
				mType:"POST",
				data:{status:status},
				success:function(response){ 
					var options = "";
					var defaultoption="<option selected value=''>Select Status</option>";

					$.each(response,function(i,data)
					{
						options = options + "<option value="+data.code+">"+data.code_desc+"</option>";
					});
					
					$('#type1').html("");
					$('#type1').append(defaultoption);
					$('#type1').append(options);
					
				},
				error:function(){
					alert("We are unable to Process.Please contact Support");
				}
			});
		}
		
		function exitStudent_submit()
		{
			
			var exit_type = $("#type1").val();
			
			if(exit_type == '')
			{
				toastr.info("Please select the exit type");
			}
			else
			{
					var institutedata=
					{
						admissionno:$('#admissionno').val(),
						editstudentcode:$('#studentCodeunique').val(),
						type1:$('#type1').val(),
						date:$('#date').val(),
						exittype:$("#type1 option:selected").text(),
						remark:$('#remark').val(),
						
					};
						
					$.ajax({
						//url:"db/manage_student_db.php",
						url:url+"/school/students/STUDENTEXIT",	
						mType:"post",
						data:institutedata,
						success:function(response)
						{ 
							toastr.success('success');
							var table = $("#dtblStudents").DataTable();
			 					table.ajax.reload(null,false);
								$('#exitModal').modal('hide');
								var dtblStudentAssign = $("#dtblStudentAssign").DataTable();
						 		dtblStudentAssign.ajax.reload();
						 		Student_Status_Details();
								toastr.success('Student processed Successfully');
								
								var today = new Date();
								var dd = today.getDate();
								var mm = today.getMonth()+1; //January is 0!
								var yyyy = today.getFullYear();
								if(dd<10)
								{
									dd='0'+dd;
								} 
								if(mm<10)
								{
									mm='0'+mm;
								} 
								var today = dd+'-'+mm+'-'+yyyy;
								$("#date").val(today);
								$('#remark').val("");
						},
						error:function()
						{
							toastr.error('Unable to process please contact support');
						}
					});	
				
			}	
		}
      
      	function Show_MISC_Details(student_code)
		{
			$.ajax({
				url:url+"/school/students/api/GET_MISC_DETAILS",
				mType:"get",
				async: false,
				data:{student_code:student_code},
				success:function(response)
				{ 
					
					$("#ShowMiscFields").html(response);
					//$("#ShowMiscFields").html(response+'<button type="button" class="btn btn-primary" onclick="save_misc_data()">Save</button>');

				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			});
			
		}
      	
      	function UploadStudent()
      	{
			window.open(url+"/school/student/document/upload","page","left=0,top=0,width=1300,height=650,menubar=0,toolbar=0,scrollbars=1").focus();
		}

		function save_misc_data(){
			//alert("HII");
			var formdata = document.getElementById("misc_form");
			console.log(formdata);

			$.ajax({
				url:url+"/school/students/api/SAVE_MISC_DETAILS",
				type: 'POST',
				data:formdata,
				success:function(response)
				{ 
					
					$("#ShowMiscFields").html(response);
					//$("#ShowMiscFields").html(response+'<button type="button" class="btn btn-primary" onclick="save_misc_data()">Save</button>');

				},
				error:function()
				{
					toastr.error('Unable to process please contact support');
				}
			});



		}
      	
	</script>
      
   </body>
</html>