<?php


	public function stu_certificatePage(Request $request)
	{
		
		return view('school.studentsCertificate');
	}
	
	public function stu_certificatePdfPage(Request $request)
	{
		$studentcode = $request->studentcode;
		$templatetype = $request->templatetype;
		
			
		return view('school.studentsCertificatePdf',compact('studentcode','templatetype'));
	}
	
	public function tc_number_auto_required(Request $request)	
	
	{
		/*$query = "SELECT values,prefix
				FROM k12.setting_master 
				WHERE 
					setting_code = 'TC_NUMBER_AUTO_REQUIRED' 
					AND session_code = '$session_code'";*/
		
		$results = DB::table('k12.setting_master')->select('values','prefix')
				->where('setting_code', 'TC_NUMBER_AUTO_REQUIRED')
				->where('session_code', $this->session_code)
				->get();
		return response()->json($results);	
	}
	
	public function select_specific_template(Request $request)
	{
		$institute_code = $this->institute_code;
		$session_code = $this->session_code;
		
		
		
		$subject_list_arr = array();
		$sessionyr = explode("_",$session_code);
	    $session_yr = $sessionyr[0];
	  
		
		$template_type = trim($request->cmbCertificate);
		$certificate_slno = trim($request->certificate_slno);
		$studentcode = trim($request->stucode);
		
		  $stucode = substr($studentcode ,0 ,6);
		  $today = date("d-m-Y");
		  $classArray =  array("LKG" => 'LKG',"UKG" => 'UKG',"I" => 'One',"II" => 'Two',"III" => 'Three',"IV" => 'Four',
						"V" => 'Five',"VI" => 'Six',"VII" => 'Seven',"VIII" => 'Eight',"IX" => 'Nine',"X" => 'Ten',
						"XI" => 'Eleven',"XII" => 'Twelve');
		
		$from_date = '';
		$to_date = '';
		
		//find out if certificate for this student code already exist
		/*$fQuery = "SELECT template_text FROM k12.ac_student_certificate
					WHERE student_code = '$studentcode'
					AND template_type = '$template_type'";*/
		$results = DB::table('k12.ac_student_certificate')->select('template_text')
				->where('student_code', $studentcode)
				->where('template_type', $template_type)
				->get()->toArray();
				
							
		
		//print_r($results);die();
	
		if(sizeof($results) > 0)
		{
			foreach($results as $newdata) //$result is variable that store raw data 
			{
				
				$output = $newdata->template_text;
			}
			
			echo($output);	
		}
		else
		{
		
		   $results = DB::table('admin.doctemplate_to_institute')->select('template')
				->where('entity_type', 'STD')
				->where('template_code', $template_type)
				->where('institute_code', $this->institute_code)
				->get()->toArray();		
				
			foreach($results as $newdata) //$result is variable that store raw data 
			{
				
				$output = $newdata->template;
			}
			
			// GET SESSION_FROM AND SESSION_TO
		/*	$query = "select date_from,date_to from k12.ac_session_master
					WHERE  
					institute_code = '$institute_code'
					and record_status = 1 
					and session_code = '$session_code'";*/
		
			$results  = DB::table('k12.ac_session_master')->select('date_from','date_to')
				->where('institute_code', $this->institute_code)
				->where('session_code', $this->session_code)
				->where('record_status', '1')
				->get()->toArray();	
				
			foreach($results as $newdata) //$result is variable that store raw data 
			{
				$date_from = $newdata->date_from;
				$from_date = date('F Y', strtotime($date_from));
				
				$date_to = $newdata->date_to;
				$to_date = date('F Y', strtotime($date_to));
				
			}			
			
			//query for student detail
			/*$stu_result = DB::select("SELECT DISTINCT A.student_name, A.fathers_name, A.mothers_name, A.admission_no,
			A.course_code, A.class_code,A.section_code,A.roll_no,
			B.institute_name,CONCAT(A.address,' ',A.register_mobile_no) AS concat, A.cbse_regd_no AS affiliation_no, 
			A.school_regd_no AS school_no,B.logo_url, 
			A.institute_code, C.class_name, F.section_name, TO_CHAR(A.birth_date,'DD-MM-YYYY') AS birth_date,A.gender
			, TO_CHAR(A.admission_date,'DD-MM-YYYY')  AS admission_date,
			B.location AS school_address,A.address  
			FROM k12.ac_student_master A
				LEFT JOIN institute.institute_master B ON A.institute_code = B.institute_code 
				LEFT JOIN k12.ac_class_master C ON A.class_code = C.class_code 
				LEFT JOIN k12.ac_section_master F ON F.section_code = A.section_code 
			WHERE  
				A.institute_code = ? 
				AND A.session_code = ? 
				AND A.student_code = ?",[$institute_code,$session_code,$studentcode]);
			echo('<pre>');
			print_r($stu_result);
			die();*/
			
			$stu_result = DB::table('k12.ac_student_master AS A')
				 ->select('A.student_name','A.fathers_name',
				 
				 'A.mothers_name','A.admission_no',
				 'A.course_code','A.class_code',
				 'A.register_mobile_no AS concat',
				 'A.section_code',
				 'section_name',
				 'A.roll_no',
				'A.admission_date',
				'birth_date',
				'B.institute_name',
				'A.cbse_regd_no AS affiliation_no',
				'A.school_regd_no AS school_no',
				'B.logo_url',
				'A.institute_code',
				'C.class_name',
				'A.gender',
				'B.location AS school_address',
				'A.address')
				 ->leftjoin('institute.institute_master AS B',function ($join){
            		$join->on('A.institute_code','=','B.institute_code');
		        	})
		         ->leftjoin('k12.ac_class_master AS C',function ($join){
            		$join->on('A.class_code','=','C.class_code');
		        	})
		        ->leftjoin('k12.ac_section_master AS F',function ($join){
            		$join->on('F.section_code','=','A.section_code');
		        	})
		        ->where('A.institute_code', $institute_code)
				->where('A.session_code', $session_code)
				->where('A.student_code',$studentcode)
				->get()->toArray(); 
				
			/*echo('<pre>');
			print_r($stu_result);
			die();	*/
					
			foreach ($stu_result AS $result)
			{
				
				$course_code = $result->course_code;
				$class_code = $result->class_code;
				$section_code = $result->section_code;
				$output1 = $result->student_name;
				$address =  $result->address;
				$output2 =  $result->fathers_name;
				$output3 =  $result->mothers_name;
				$output4 =  $result->institute_name;
				$output5 =  $result->concat;
				$output6 =  $result->affiliation_no;
				$output7 =  $result->logo_url;
				$output10 = $result->class_name;
				$output11 = $result->section_name;
				$output12 = $result->admission_no;
				$roll_no = $result->roll_no;
				$output13 = $result->school_no;
				$output16 = $result->gender;
				$birth_date = $result->birth_date;
				$birth_date = date("Y-m-d", strtotime($birth_date));
				
				$admission_date = $result->admission_date;
				$admission_date = date("Y-m-d", strtotime($admission_date));
				
				$school_address = $result->school_address;
			}	
			
			$birth_date_arr = explode('-',$birth_date);
			
			/*$fResult = DB::select( "SELECT  COUNT(*)AS totalclass 
								FROM 
									k12.ac_student_attendance_master
								WHERE course_code = '$course_code' 
									AND class_code = '$class_code' 
									AND section_code = '$section_code'
									 AND institute_code = '$institute_code'
									 AND session_code = '$session_code'
									 AND record_status = 1 GROUP BY attendance_date,period_code");*/
			
			
			$output27 = 0;
			$fResult = DB::table('k12.ac_student_attendance_master')
				 ->select( 'attendance_id')
				->where('course_code', $class_code)
				->where('section_code', $section_code)
				->where('institute_code',$institute_code)
				->where('session_code',$session_code)
				->where('record_status','1')
				->GroupBy('attendance_id','attendance_date','period_code')->get()->count();
			$output27 = $fResult;	
				
			//------------ FIND TOTAL PRESENT OF THE STUDENT ---------------//
			/*$fResult =  DB::select("SELECT COUNT(*) AS totalpresent 
							FROM k12.ac_student_attendance_master A
							INNER JOIN k12.ac_student_attendance_detail B ON A.attendance_id = B.attendance_id AND
									 B.attendance_status = 'P' AND B.student_code = '$studentcode'
							WHERE A.institute_code = '$institute_code'
							AND A.session_code = '$session_code'
							AND A.record_status = 1
							AND A.course_code = '$course_code' 
							AND A.class_code = '$class_code' 
							AND A.section_code = '$section_code'");*/
				
			//echo($studentcode);die();			
			
		
			$fResult = DB::table('k12.ac_student_attendance_master AS A')
				 ->select( 'A.attendance_id')
				  ->leftjoin('k12.ac_student_attendance_detail AS B',function ($join)use($studentcode) {
				 
            		$join->on('A.attendance_id','=','B.attendance_id')
            		  ->where('B.attendance_status','P')
            		  ->where('B.student_code',$studentcode);
		        	})
		       	->where('A.course_code', $class_code)
				->where('A.section_code', $section_code)
				->where('A.institute_code',$institute_code)
				->where('A.session_code',$session_code)
				->where('A.record_status','1')
				->get()->count();			
			
			$output28 = 0;
			$output28 = $fResult;
			
			
			//fll up the template
			$output = str_replace('[school_name]',$output4,$output);
			$output = str_replace('[school_address]',$school_address,$output);
			$output = str_replace('[affiliation_no]',$output6,$output);
			$output = str_replace('[logo_url]',$output7,$output);
			$output = str_replace('[student_name]',$output1,$output);
			$output = str_replace('[address]',$address,$output);
			$output = str_replace('[mother_name]',$output3,$output);
			$output = str_replace('[father_name]',$output2,$output);
			$output = str_replace('[class]',$output10,$output);
			$output = str_replace('[section]',$output11,$output);
			$output = str_replace('[session_yr]',$session_yr,$output);
			$output = str_replace('[session]',$session_yr,$output);
			$output = str_replace('[reg_no]',$output12,$output);
			
			$output = str_replace('[admission_no]',$output12,$output);
			$output = str_replace('[roll_no]',$roll_no,$output);
			$output = str_replace('[date_of_issue]',$today,$output);
			$output = str_replace('[school_no]',$output13,$output);
			$output = str_replace('[school_logo]',$institute_code.'.png',$output);
			$output = str_replace('[birth_date]',$birth_date,$output);
			$output = str_replace('[admission_date]',$admission_date,$output);
			$output = str_replace('[now]', date('d-m-Y'),$output);
			$output = str_replace('[NOW]', date('d-m-Y'),$output);
			$output = str_replace('[certificate_slno]', $certificate_slno,$output);
			$output = str_replace('[session_from]', $from_date,$output);
			$output = str_replace('[session_to]', $to_date,$output);
			if(array_key_exists($output10, $classArray))
				$output = str_replace('[class_inwords]', $classArray[$output10],$output);
			$output = str_replace('[total_workingdays]', $output27,$output);
			$output = str_replace('[pupil_present]', $output28,$output);
			if($output16 == 'M')
			{
				$output = str_replace('[title]','Master',$output);
				$output = str_replace('[d/o/s/o]','Son of',$output);
				$output = str_replace('[His/Her]','His',$output);
				$output = str_replace('[his/her]','his',$output);
				$output = str_replace('[He/She]','He',$output);
				$output = str_replace('[he/she]','he',$output);
			}
			else
			{
				$output = str_replace('[title]','Miss',$output);
				$output = str_replace('[d/o/s/o]','Daughter of',$output);
				$output = str_replace('[His/Her]','Her',$output);
				$output = str_replace('[his/her]','her',$output);
				$output = str_replace('[He/She]','She',$output);
				$output = str_replace('[he/she]','she',$output);
			}
			$current_year = date('Y');
			$output = str_replace('[cur_year]', $current_year,$output);
			if(count($birth_date_arr) == 3)
			{
				//$birth_date = convertBirthdateToText($birth_date);
				//$birth_date = convertBirthdateToTextNew($birth_date);
				$birth_date = $birth_date;
				$output = str_replace('[birth_date_inwords]',$birth_date,$output);
				//$output = str_replace('[birth_date_inwords]',$birth_date_arr[0].'/'.$birth_date_arr[1].'/'.$birth_date_arr[2],$output);
			}
			else
			{
				$output = str_replace('[birth_date_inwords]','',$output);
			}
			
			
			//query for previous class
			$fResult =  DB::table('k12.ac_student_master AS A')
				 ->select( 'C.class_name')
				  ->leftjoin('k12.ac_session_master AS B',function ($join){
				 
            		$join->on('A.session_code','=','B.session_code')
            		  ->where('B.session_status','PREVIOUS');
            		})
            	->leftjoin('k12.ac_class_master AS C',function ($join){
				 
            		$join->on('A.class_code','=','C.class_code');
            		})	
            		            		
            	->where('A.record_status', 1)
				->where('A.student_code', $studentcode)
				->get()->toArray(); 		
			
			
			$output30 = '';
			foreach($fResult AS $res)
			{
				$output30 = $res->class_name;
			}
			$output = str_replace('[previous_class]', $output30,$output);
			
			if($output30 == '' )
			{
				$output = str_replace('[exam_type]',' ',$output);
			}
			else if($output30 == 'X' || $output30 == 'XII')
			{
				$output = str_replace('[exam_type]','Board Exam',$output);
			}
			else
			{
				$output = str_replace('[exam_type]','School Exam',$output);
			}
			
			//last exam passed or failed
			if($output30 != '' & ($output10 == $output30))
			{
				$output = str_replace('[passed_failed]','Failed',$output);
			}
			elseif($output30 != '' & ($output10 != $output30))
			{
				$output = str_replace('[passed_failed]','Passed',$output);
			}
			else
			{
				$output = str_replace('[passed_failed]','',$output);
			}
			
			// Get optional is exist or not
			$fResult =  DB::table('k12.ac_course_class_optional AS A')
			 ->select( 'no_of_optional')
			 ->where('course_code', $course_code)
			->where('class_code', $class_code)
			->where('session_code', $session_code)
			->get()->toArray(); 		
		
			$no_of_optional = 0;
			foreach($fResult AS $res)
			{
				$no_of_optional = $res->no_of_optional;
			}
			//query for subject studied
			if($no_of_optional > 0)
			{
				/*$query = "SELECT string_agg(SUB.subject_name,',') AS subject_studied 
							FROM k12.ac_student_master S
								INNER JOIN k12.ac_student_subjects SSUB ON SSUB.student_code = S.student_code 
								AND SSUB.session_code='$session_code' AND SSUB.student_code = '$studentcode'  
								INNER JOIN k12.ac_subject_master SUB ON SUB.subject_code = SSUB.subject_code 
								AND SSUB.session_code='$session_code' 
								INNER JOIn k12.ac_course_class_subject ccs ON ccs.subject_code = SUB.subject_code 
								AND SSUB.session_code='$session_code' 
									AND ccs.course_code = S.course_code AND ccs.class_code = S.class_code
								AND S.session_code = '$session_code' 
								AND S.student_code = '$studentcode' 
							AND UPPER(ccs.group_value)='MAIN'";*/
							
							
				$fResult =  DB::table('k12.ac_student_master AS S')
				 ->select( 'string_agg(SUB.subject_name,',') AS subject_studied')
				  ->join('k12.ac_student_subjects AS SSUB',function ($join)use($session_code,$studentcode){
				 
            		$join->on('SSUB.student_code','=','S.student_code')
            		  ->where('SSUB.session_code',$session_code)
            		  ->where('SSUB.student_code',$studentcode) ;
            		})
            		
            	->join('k12.ac_subject_master AS SUB',function ($join){
				 
            		$join->on('SUB.subject_code','=','SSUB.subject_code')
            			 ->where('SSUB.session_code',$session_code);
            		})	
            	->join('k12.ac_course_class_subject AS ccs',function ($join){
				 
            		$join->on('ccs.subject_code','=','SUB.subject_code')
            			 ->where('SSUB.session_code',$session_code)
            			 ->where('ccs.course_code','S.course_code')
            			 ->where('ccs.class_code','S.class_code');
            		})		
            		            		
            	->where('S.student_code', $studentcode)
				->where('S.session_code', $session_code)
				//->where('ccs.group_value', 'MAIN')
				->where(DB::raw('upper(ccs.group_value)'),'=',  'MAIN')
				->get()->toArray(); 				
							
			}
			else
			{
			
					
				$fResult =  DB::table('k12.ac_course_class_subject AS ccs')
				 ->select( 'subject_name AS subject_studied')
				  ->join('k12.ac_student_master AS stu',function ($join)use($studentcode){
				 		$join->on('ccs.class_code','=','stu.class_code')
            		  		->where('stu.student_code','=',$studentcode);
            		})
            		
            	->join('k12.ac_subject_master AS s',function ($join){
				 
            		$join->on('s.subject_code','=','ccs.subject_code');
            	})	
            	//->where('ccs.group_value','MAIN')
            	->where(DB::raw('upper(ccs.group_value)'),'=',  'MAIN')
				->get()->toArray();
			}	
			
			$subject_list_arr = array();
			$subject_list = '';
			$output = str_replace('[subject_studies]',$subject_list,$output);
			echo($output);					 
										 
			
		}	
	}
	
	public function save_specific_template(Request $request)
	{
		
		$template_type = trim($request->cmbCertificate);
		$studentcode = trim($request->hidStudentCode);
		$template_text = trim($request->hidCertificate);
		$certificate_sl_no = trim($request->txtslno);
		$tc_lastno = trim($request->tc_lastno);
		
		echo($template_text);die();
		
		/*$query = "SELECT * FROM k12.ac_student_certificate A 
				WHERE  A.student_code = '$studentcode' 
					AND A.template_type = '$template_type' 
					AND  A.institute_code = '$institute_code' 
					AND A.session_code = '$session_code';";
					*/
		$results  = DB::table('k12.ac_student_certificate AS A')->select('id')
				->where('A.student_code', $studentcode)
				->where('A.template_type', $template_type)
				->where('A.institute_code', $this->institute_code)
				->where('A.session_code', $this->session_code)
				->where('A.record_status', '1')
				->get()->toArray();	
		$output = array();		
		if(sizeof($results) == 0)
		{
			$data = array("student_code"=>$studentcode,"template_type"=>$template_type,
					"template_text"=>$template_text,
					"institute_code"=>$this->institute_code,
					"session_code"=>$this->session_code,
					"date_of_issue"=>"now()",
					"certificate_sl_no"=>$certificate_sl_no,
					"created_by"=>Session::get('user_code'),
					"created_on"=>"NOW()",
					"record_status"=>"1");
				
				$result = DB::table('k12.ac_student_certificate')->insert($data);
				
				if($result)
				{
					$output['dbStatus'] = 'SUCCESS';
            		$output['dbMessage'] = 'Operation complted Sucessfully.';
				}
				else
				{
					$output['dbStatus'] = 'FAILURE3';
            		$output['dbMessage'] = 'OOPS! Someting Went Wrong.';
				}	
			if($tc_lastno > 0 && $template_type == 'K12_TC')
			{
				$tc_lastno = $tc_lastno;
				
				$data = array("tc_lastno"=>$tc_lastno);
				$result = DB::table('k12.setting_master')
					->where('setting_code','=','TC_NUMBER_AUTO_REQUIRED')
					->where('session_code','=',$this->session_code)
					->update($data);
			}		
					
		}
		else
		{
			
			
			/*$query = "UPDATE k12.ac_student_certificate SET
								template_text = '$template_text' , 
								certificate_sl_no = '$certificate_sl_no',
								updated_by = '$logged_user',
								updated_on = 'NOW()'
								WHERE student_code = '$studentcode' 
									AND template_type = '$template_type' 
									AND institute_code = '$institute_code' ";*/
				$data = array("template_text"=>$template_text,
						"certificate_sl_no"=>$certificate_sl_no,
						"updated_by"=>Session::get('user_code'),
						"updated_on"=>'NOW()',
					);					
				$result = DB::table('k12.ac_student_certificate')
					->where('student_code','=',$studentcode)
					->where('template_type','=',$template_type)
					->where('institute_code','=',$this->institute_code)
					->update($data);
					
				if (!$result)
				{
				    $output['dbStatus'] = 'FAILURE';
					$output['dbMessage'] = $con->Error();
				}
				else
				{	
					$output['dbStatus'] = 'SUCCESS';
					$output['dbMessage'] = 'Data Updated Successfully...';		
				}								
									
		}
		
		
		echo('<pre>');
		print_r($output);
		die();					
		
	}

?>

