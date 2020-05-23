<?php


	//$student_code = $request->input('studentcode');
	$student_code = $_REQUEST['studentcode'];
	
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Student Certificate</title>
		<style media="all" type="text/css">
	  		.alignRight { text-align: right; }
	  		.row {
			    margin-left: 0;
			    margin-right: 0;
			}
			.btn{
				border-radius:0px;
			}
		</style>
	</head>

<body>
	<div class="row" style="background-color:#aaaaff; ">
			<br>
			<div class="col-lg-4" >
				<label id="lbl_student_detail" class="col-sm-12 control-label"></label>
				<label class="col-sm-12 control-label"></label>
    		</div>
    		
	    </div>
	    <div class="form-group">
	        
	        <br>
	        
	        <form class="form-horizontal" id="frmCertificate" name="frmCertificate">
	        	<input type="hidden" class="form-control tooltips" name="_token" id="_token" value="{{ csrf_token() }}"/>
				<input type="hidden" class="form-control tooltips" id="hidStudentCode" name="hidStudentCode" />
				<input type="hidden" class="form-control tooltips" id="hidCertificate" name="hidCertificate"/>
				<div class="row">
					<div class="col-lg-12">
						<label for="" class="col-sm-2 control-label">Certificate Type :</label>
						<div class="col-sm-4">
							<select class="form-control" id="cmbCertificate" name="cmbCertificate" onchange="selectTemplate();" style="border-radius: 0px;"></select>
						</div>
						<label for="" class="col-sm-1 control-label" id="lbl_tc_no" style="color:#fff;">TC Serial No:</label>
						<div class="col-sm-2">
							<input class="form-control" id="txtslno" name="txtslno" style="border-radius: 0px; display: none;"  >
							<input type="hidden" id="tc_lastno" name="tc_lastno"  >
						</div>
						
						<!--<label for="" class="col-sm-1 control-label">TC Issue Date :</label>
						<div class="col-sm-2">
							<input class="form-control" id="tc_issue_date" style="border-radius: 0px;">
						</div>-->
						
	        		</div>
	        	</div>
	        	<div class="row" style="margin-top: 10px;">
					<div class="col-lg-12">
						<label for="" class="col-sm-2 control-label">Certificate Template :</label>
						<div class="col-sm-10">
							<textarea id="txtCertificate" class="form-control" name="txtCertificate"></textarea>
						</div>
	        		</div>
	        	</div>
	        	
	        		<hr>
	        	<div class="col-lg-12 ">
	        		<div class="col-lg-6">
	        		</div>
					<div class="col-lg-6 text-right">
						<input type="hidden" id="hid_tc_auto" >
						<button type="button" class="btn btn-success" name="saveTemplate" id="saveTemplate" disabled="" onclick="save_template()"   >Save</button>
						<button type="button" class="btn btn-primary" name="printTemplate" id="printTemplate" onclick="certificatePdf()" disabled="">Print</button>
						<button type="button" class="btn btn-danger" name="rebtnTemplate" id="rebtnTemplate" onclick="regenerateTemplate()" disabled="">Regenerate</button>
	        		</div>
	        	</div>
	        </form>
	        </div>
	</body>	
</html>

<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <input type="hidden" id="csrf_token" value="{{ csrf_token() }}" />
 <script>
 	$('#cmbCertificate').focus();
	 var url = '<?php echo url('/');?>';
	 var student_code = '<?php echo $student_code;?>';
	 
	
	 var csrf_token = $('#csrf_token').val();		
	 
	 $("#lbl_student_detail").html(student_code);
	 $("#hidStudentCode").val(student_code);
	 	
		// Get certificate template	
		$.ajax({
			url:url+"/school/master-page/api/SELECT_CERTIFICATE_TYPE",
			mType:"get",
			success:function(response){  
				
				
					var options = "";
					var defaultoption="<option selected value=''>Select</option>";

					$.each(response,function(i,data)
					{
						options = options + "<option value="+data.code+">"+data.code_desc+"</option>";
					});

					$('#cmbCertificate').html("");
					$('#cmbCertificate').append(defaultoption);
					$('#cmbCertificate').append(options);
			
			},
			error:function(){
				toastr.error("We are unable to Process.Please contact Support");
			}
		});
		
		//GET THE TC VALUE AND PREFIX
		$.ajax({
		url:url+"/school/students/TC_NUMBER_AUTO_REQUIRED",
		mType:"get",
		success:function(response){  
			$.each(response,function(i,data)
			{
				$('#txtslno').val(data.values);
				$('#tc_lastno').val(data.values);
				$('#hid_tc_auto').val(data.values);
			});
		
		},
		error:function(){
			toastr.error("We are unable to Process.Please contact Support");
		}
	});
		
		
 	CKEDITOR.replace("txtCertificate",{
			startupFocus : false
	});
	
	function selectTemplate()
	{
		$('#saveTemplate').attr('disabled', true);
		$('#printTemplate').attr('disabled', true);
		$('#rebtnTemplate').attr('disabled', true);
		
		$('#lbl_tc_no').css('color','#fff');
		$('#txtslno').css('display','none');
		
		//alert($('#cmbCertificate').val());
		
		if($('#cmbCertificate').val() != "")
		{
			$('#saveTemplate').removeAttr('disabled');
			$('#printTemplate').removeAttr('disabled');
			$('#rebtnTemplate').removeAttr('disabled');
			
			var certificate_type = $('#cmbCertificate').val();
			
			var arr_certificate_type = certificate_type.split("_");
			arr_certificate_type = arr_certificate_type[1];
			
			var is_tc = arr_certificate_type.substr(0, 2).toLowerCase();
			if(is_tc == 'tc')
			{
				$('#lbl_tc_no').css('color','#000');
				$('#txtslno').css('display','block');
				
			}
			
			
			// Get last TC No
			var is_tc_auto_gen = $('#hid_tc_auto').val();
			var template_type = {
					cmbCertificate: $('#cmbCertificate').val(),
					stucode: student_code,
					certificate_slno: $('#txtslno').val(),
					
				}
			$.ajax({
				url:url+"/school/students/SELECT_SPECIFIC_TEMPLATE",
				mType:"get",
				data:template_type,
				success:function(response){
					CKEDITOR.instances['txtCertificate'].setData(response); 
					
				},
				error:function(){
					toastr.error("We are unable to Process.Please contact Support");
				}
			});
			document.getElementById('hidCertificate').value = CKEDITOR.instances['txtCertificate'].getData();
				
				
		}
		
		
	}
	
	function regenerateTemplate()
	{
		//for regenerate  template from template_master
		if($('#cmbCertificate').val() == '')
		{
			toastr.error("Unable to Process.Please select a Template Type");
			$('#cmbCertificate').focus();
		}
		else
		{
			var template_type = {
					cmbCertificate: $('#cmbCertificate').val(),
					stucode: student_code,
					certificate_slno: $('#txtslno').val(),
					
				}
			$.ajax({
				url:url+"/school/students/SELECT_SPECIFIC_TEMPLATE",
				mType:"get",
				data:template_type,
				success:function(response){
					CKEDITOR.instances['txtCertificate'].setData(response); 
					
				},
				error:function(){
					toastr.error("We are unable to Process.Please contact Support");
				}
			});

		}
		
	}
		
	function save_template()
	{
		document.getElementById('hidCertificate').value = CKEDITOR.instances['txtCertificate'].getData();
		var formData ='';
		var oper;
		var formData = new FormData(document.getElementById("frmCertificate"));
		
		
		oper = 'SAVE_SPECIFIC_TEMPLATE';
		$.ajax({
			url:url+"/school/students/SAVE_SPECIFIC_TEMPLATE",
			type:"post",
			data:formData,
			cache: false,
		    contentType: false,
			processData: false,
			success:function(response)
			{ 
				var result = jQuery.parseJSON(response);
	        	if(result.dbStatus == 'SUCCESS')
	            { 
					//selectTemplate();
					
					toastr.success('Data Successfully Saved');
					document.getElementById('saveTemplate').disabled = false;
				}
				else
				{
					toastr.error(result.dbMessage);
				}		
			},
			error:function()
			{
				toastr.error('We are unable to process please contact support');	
			}
		});
		$("#hidCertificate").val('');
		
	}
	function certificatePdf()
	{
		if($('#cmbCertificate').val() == '')
		{
			toastr.error("Unable to Process.Please select a template type");
			$('#cmbCertificate').focus();
		}
		else
		{
			//window.open("stu_certificate_pdf.php?studentcode="+$('#hidStudentCode').val()+"&templatetype="+$('#cmbCertificate').val(),"winreportcard","left=0,top=0,width=1000,height=750,menubar=0,toolbar=0,scrollbars=1");
			window.open(url+"/school/students/STU_CERTIFICATE_PDF?studentcode="+$('#hidStudentCode').val()+"&templatetype="+$('#cmbCertificate').val(),"page","left=0,top=0,width=1300,height=650,menubar=0,toolbar=0,scrollbars=1").focus();
      		
		}
		
	}
						
 </script>	
