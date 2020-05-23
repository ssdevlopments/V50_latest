<?php
	
	//echo('<pre>');
	//print_r($_REQUEST);
	
	/*$fQuery = "SELECT template_text FROM k12.ac_student_certificate
					WHERE student_code = '$studentcode'
					AND template_type = '$template_type'";
					*/
					
	$output = '';			
	$result = DB::table('k12.ac_student_certificate')
			        ->select('template_text')
			        ->where('student_code',$studentcode)
			        ->where('template_type',$templatetype)
			        ->where('record_status','1')
			        ->get()->toArray();		
	
	foreach($result AS $data)
	{
		$output = $data->template_text;
	}
	
	$mpdf = new \Mpdf\Mpdf();
	//$mpdf->WriteHTML(\View::make('email.email-invoice')->with('data', $response)->render());
	$mpdf->WriteHTML($output);
	$mpdf->Output("test.pdf","I"); // I = Show in Browser, D = Download		        
	/*echo('<pre>');
	print_r($output);
	die();		*/        		
?>


