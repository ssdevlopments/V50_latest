<?php

$session_code = Session::get('session_code');
$institute_code = Session::get('institute_code');

$session_code_arr = explode('_',$session_code);
$session = $session_code_arr[0];

$course_code = $_REQUEST['course_code'];
$class_code = $_REQUEST['class_code'];
$section_code = $_REQUEST['section_code'];

$reportcard_code = $_REQUEST['reportcard_code'];
$admno = $_REQUEST['admno'];
$student_count = $_REQUEST['count'];
$sl_no = $_REQUEST['sl_no'];
$record_status = $_REQUEST['record_status'];
$attendance = $_REQUEST['attendance'];
$per_page = $_REQUEST['per_page'];
$header_option = $_REQUEST['header_option'];
$email_id_embed = 'apsdecgmail.com';


//Get Exam Code from Report card exam mapping
$exam_dispaly_name = array();
$exam_dispaly_name = DB::table(DB::raw('k12.reportcard_exam_mapping'))
->select(DB::raw("SPLIT_PART(exam_code,'_',1) AS exam_name,
        CASE WHEN exam_display_name IS NULL
        THEN SPLIT_PART(exam_code,'_',1)
        WHEN exam_display_name = ''
        THEN SPLIT_PART(exam_code,'_',1)
        ELSE exam_display_name END AS exam_display_name,
        exam_code,reportcard_code"))
->where(DB::raw('reportcard_code'), $reportcard_code)
->where(DB::raw('record_status'), '1')
->get()->toArray();

// echo('<pre>');
// print_r($exam_dispaly_name);die();

//Institute Details
$institute_result = DB::table(DB::raw('institute.institute_master'))
    ->select(DB::raw("institute_name,contact_number,
    location,logo_url,affiliation_no"))
->where(DB::raw('institute_code'), $institute_code)
->get()->toArray();

$institute_name = $institute_result[0]->institute_name;
$contact_number = $institute_result[0]->contact_number;
$institute_address = $institute_result[0]->location ;
$logo = $institute_result[0]->logo_url;
$affiliation_no = $institute_result[0]->affiliation_no;

// Students details
$query = DB::table(DB::raw('k12.ac_student_master as A'))
	->join(DB::raw('k12.ac_course_master CO'), function ($join) use($session_code) {
		$join->on(DB::raw('A.course_code'), '=', DB::raw('CO.course_code'))
			->where(DB::raw('CO.session_code'), '=', $session_code);
	})
	->join(DB::raw('k12.ac_class_master CL'), function ($join) use($session_code) {
		$join->on(DB::raw('A.class_code'), '=', DB::raw('CL.class_code'))
			->where(DB::raw('CL.session_code'), '=', $session_code);
	})
	->leftJoin(DB::raw('k12.ac_section_master AS SEC'), DB::raw('A.section_code'), '=', DB::raw('SEC.section_code'))
	->select(DB::raw("A.admission_no,student_name,CO.course_name,CL.class_name,SEC.section_name,A.roll_no,
			fathers_name,register_mobile_no,A.gender,A.student_code,A.course_code,A.class_code,A.section_code,A.is_exit,
			TO_CHAR(A.birth_date::date,'DD-MM-YYYY') AS birth_date,
			TO_CHAR(A.admission_date::date,'DD-MM-YYYY') AS admission_date,
			roll_no,mothers_name,
			TO_CHAR(A.enrollment_date::date,'DD-MM-YYYY') AS enrollment_date,
			alternate_contact_no,A.blood_group,A.category_1,A.category_2,A.admission_type,
			A.cbse_regd_no,A.house_code,A.service_category, A.email_id,
			A.address,A.permanent_address,A.city_code,A.state_code
			")
	)
->where(DB::raw('A.institute_code'), $institute_code)
->where(DB::raw('A.session_code'), $session_code);

if($course_code != "")
{
	$query->where(DB::raw('CO.course_code'),$course_code);
}
if($class_code != "")
{
	$query->where(DB::raw('CL.class_code'),$class_code);
}
if($section_code != "")
{
	$query->where(DB::raw('SEC.section_code'),$section_code);
}
if($admno != "")
{

	$admnoArr = explode(",", $admno);
	$query->whereIn(DB::raw('A.admission_no'), $admnoArr);
}
$start_no = ($sl_no * $per_page) - ($per_page - 1);
$end_no = $sl_no * $per_page;
if($end_no > $student_count)
{
	$end_no = $student_count;
}

$limit = ($end_no - $start_no) + 1;
$offset = $start_no - 1;

if($admno == "")
{
	$query->offset($offset);
	$query->limit($limit);
}

$studentDetails = $query->get()->toArray();

//Get subject Details
$subject_details_array = array();
$subjectgroup_array = array();
$group_mapped_to_subject = array();
$exam_subject_group_examsub_mapped = array();


$subject_details_result = DB::table(DB::raw('k12.reportcard_exam_mapping as A'))
->join(DB::raw('k12.exam_master H'), function ($join) use($session_code) {
    $join->on(DB::raw('A.exam_code'), '=', DB::raw('H.exam_code'))
        ->where(DB::raw('H.session_code'), '=', $session_code);
})
->join(DB::raw('k12.exam_subject_mapping AS B'), function ($join) use($session_code) {
    $join->on(DB::raw('H.exam_code'), '=', DB::raw('B.exam_code'))
        ->where(DB::raw('B.session_code'), '=', $session_code);
})
->Join(DB::raw('k12.exam_subject_master AS C'), DB::raw('B.exam_subject_code'), '=', DB::raw('C.exam_subject_code'))

->Join(DB::raw('k12.ac_subject_master AS D'), DB::raw('C.subject_code'), '=', DB::raw('D.subject_code'))

->Join(DB::raw('k12.ac_course_class_subject AS E'), DB::raw('E.subject_code'), '=', DB::raw('D.subject_code'))
->leftJoin(DB::raw('k12.group_value_master AS G'), DB::raw('G.group_value_code'), '=', DB::raw('E.group_value'))

 ->select(DB::raw("B.exam_subject_code, C.exam_sub_name, C.exam_sub_group,
                G.group_value_name,subject_name,C.subject_code, H.exam_type_code,B.sl_no")
)
->where(DB::raw('A.institute_code'), $institute_code)
->where(DB::raw('A.session_code'), $session_code)
->where(DB::raw('E.class_code'), $class_code)
->where(DB::raw('A.record_status'), '1')
->where(DB::raw('A.reportcard_code'), $reportcard_code)
//->where(DB::raw('group_value_name'), 'ACTIVITY')

->where(DB::raw('E.record_status'), '1')
->orderBy('sl_no')
->get()->toArray();


foreach($subject_details_result  AS $subject_details)
{

    $exam_subject_code = $subject_details->exam_subject_code;
    $exam_sub_name = $subject_details->exam_sub_name;
    $exam_sub_group = $subject_details->exam_sub_group;
    $group_value_name = $subject_details->group_value_name;
    $subject_name = $subject_details->subject_name;
    $subject_code = $subject_details->subject_code;
    $exam_type_code = $subject_details->exam_type_code;
    $sl_no = $subject_details->sl_no;

    $subject_details_array[$subject_name][$exam_sub_group] = $exam_sub_name;
    $group_mapped_to_subject[$subject_name] = [$group_value_name];


    if($subject_name == 'CO-CURRICULAR ACTIVITIES' )
    {
        $exam_subject_group_examsub_mapped[$subject_name][$exam_sub_group][] =  $exam_sub_name.'@!!'.$exam_subject_code;
    }
    else
    {
         $exam_subject_group_examsub_mapped[$subject_name][$exam_sub_group][] = $exam_sub_name.'@!!'.$exam_subject_code;
    }

    $subjectgroup_array[] = $subject_name;
    // if($row['group_value_name'] != $row['subject_name'])
    // {
    //     $subjectgroup_array[] = $row['subject_name'];
    // }
    // else
    // {
    //     $subjectgroup_array[] = $row['group_value_name'];
    // }

}
$subjectgroup_array = array_unique($subjectgroup_array);

// GET THE MARKS FROM REPORT CARD MARK DETAILS STUDENT WISE
// $query = "SELECT A.student_code,exam_code,exam_sub_code,mark_grade
// FROM `reportcard_mark_grade_entry`  A
// INNER JOIN ac_student_master B ON A.student_code = B.student_code
// -- AND B.record_status = 1
//  AND B.class_code = '$class'
//  AND B.course_code  = '$course'
// WHERE  A.institute_code = '$institute_code'
//  AND A.session_code = '$session_code'
// AND exam_code = '$reportcard'";


// mark details
// Mark
$query = DB::table(DB::raw('k12.exam_subject_mapping esm'))
	->leftJoin(DB::raw('k12.exam_mark_entry eme'), function ($join) use($session_code) {
		$join->on(DB::raw('eme.exam_code'), '=', DB::raw('esm.exam_code'))
			->where(DB::raw('eme.exam_subject_code'), '=', DB::raw('esm.exam_subject_code'))
			->where(DB::raw('eme.session_code'), '=', $session_code);
	})
	->join(DB::raw('k12.ac_student_master stu'), function ($join) use($course_code, $class_code, $session_code) {
			$join->on(DB::raw('stu.student_code'), '=', DB::raw('eme.student_code'))
				->where(DB::raw('stu.session_code'), '=', $session_code)
				->where(DB::raw('stu.class_code'), '=', $class_code)
				->where(DB::raw('stu.course_code'), '=', $course_code)
				->where(DB::raw('stu.record_status'), 1);
	})
	->join(DB::raw('k12.reportcard_exam_mapping rem'), function ($join) use($reportcard_code, $session_code) {
			$join->on(DB::raw('rem.exam_code'), '=', DB::raw('esm.exam_code'))
				->where(DB::raw('rem.reportcard_code'), '=', $reportcard_code)
				->where(DB::raw('rem.session_code'), '=', $session_code);
	})
	->select(DB::raw("eme.exam_code,eme.exam_subject_code,eme.student_code,esm.max_mark,
					eme.mark_secured,eme.grade_secured,esm.entry_type"))
	->where(DB::raw('esm.institute_code'), $institute_code)
	->where(DB::raw('esm.session_code'), $session_code)
	->where(DB::raw('esm.record_status'), 1);

if($admno != "")
{
	$admnoArr = explode(",", $admno);
	$query->whereIn(DB::raw('stu.admission_no'), $admnoArr);
}
else
{
	$query->where(DB::raw('stu.section_code'), $section_code);
}
$results = $query->get()->toArray();
//$results = $query->toSql();



$student_mark_array = array();
foreach ($results as $row)
{
	$exam_code = $row->exam_code;
	$exam_subject_code = $row->exam_subject_code;
    $student_code = $row->student_code;
    $entry_type = $row->entry_type;
    $key = $exam_code.'@'.$exam_subject_code.'@'.$student_code;
    if( $entry_type == 'GRADE')
    {
        $student_mark_array[$key] = $row->grade_secured;
    }
    else
    {
        $student_mark_array[$key] = $row->mark_secured;
    }

}

// Absent Reason
$absent_reason = array();
$results = DB::table(DB::raw('admin.code_desc'))
	->select(DB::raw("code,code_desc"))
	->where(DB::raw('category'), 'K12_ABSENT_REASON_MARK_ENTRY')
	->get()->toArray();
foreach($results as $row)
{
	$absent_reason[$row->code] = $row->code_desc;
}

// Reportcard Signature Info
$results = DB::table(DB::raw('k12.reportcard_master'))
	->select(DB::raw("date_of_issue,signature_list"))
	->where(DB::raw('reportcard_code'), $reportcard_code)
	->get()->toArray();

$date_of_issue = $results[0]->date_of_issue;
$signature_list = explode(",",$results[0]->signature_list);

if($date_of_issue != '')
{
	$date_of_issue = date("d/m/Y", strtotime($date_of_issue));
}

// signature Names
$signature_names = array();
$results = DB::table(DB::raw('k12.reportcard_signature_master'))
	->select(DB::raw("signature_name"))
	->where(DB::raw('institute_code'), $institute_code)
	->where(DB::raw('session_code'), $session_code)
	->whereIn(DB::raw('signature_code'), $signature_list)
	->get()->toArray();
foreach($results as $row)
{
	$signature_names[] = $row->signature_name;
}

$odd_subject_array = array();
	$even_subject_array = array();
	$subject_count = 1;
	foreach($subjectgroup_array AS $subject)
	{
		if($subject_count %2  == 0)
		{
			$even_subject_array[] = $subject;
		}
		else
		{
			$odd_subject_array[] = $subject;
		}
		$subject_count++;
	}

	$loop_array = array();
	if(sizeof($even_subject_array) == sizeof($odd_subject_array))
	{
		$loop_array = $even_subject_array;
	}
	else if(sizeof($even_subject_array) > sizeof($odd_subject_array))
	{

		$loop_array = $even_subject_array;
	}
	else
	{
		$loop_array = $odd_subject_array;
	}


//echo('<pre>');
//print_r($mark_data);
//print_r($even_subject_array);

//print_r($examMarkGrade);
//print_r($subjectgroup_array);
//print_r($exam_subject_group_examsub_mapped);


//die();
$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();

$orientation = 'L';
$margin_left = 1;
$margin_right = 1;
$margin_top = 1;
$margin_bottom = 1;
$mpdf = new \Mpdf\Mpdf([
	'mode' => 'utf-8',
	'format' => 'A4'.($orientation == 'P' ? '-P' : ''),
	'orientation' => $orientation,
	'margin_left' => $margin_left,
	'margin_right' => $margin_right,
	'margin_top' => $margin_top,
	'margin_bottom' => $margin_bottom,
	'margin_header' => 0,
	'margin_footer' => 0,
	'default_font' => 'arial'
	]);

$mpdf->SetWatermarkImage('https://www.apsdigicamp.com/images/logo/APSDEC.jpg');
$mpdf->showWatermarkImage = true;

$style = '<link rel="stylesheet" media="print" href="'.url('/').'/resources/views/school/cards/APS/reportcard_styles.css" />';

$pageCount = 0;
foreach($studentDetails as $row)
{


   // for headers
	$admission_no = $row->admission_no;
	$studentName = $row->student_name;
	$course_name = $row->course_name;
	$class_name = $row->class_name;
	$section_name = $row->section_name;
	$fathers_name = $row->fathers_name;
	$mothers_name = $row->mothers_name;
	$student_code = $row->student_code;
	$roll_no = $row->roll_no;
	$dob = $row->birth_date;
	$blood_group = $row->blood_group;
	$address = $row->address;
	$register_mobile_no = $row->register_mobile_no;
	$attendance_list = ''; //explode('~', $row['attendance']);
	$attendance_half = ''; //$attendance_list[0];
	$attendance_ann = ''; //$attendance_list[1];
	//$total_class_list = explode('~', $row['total_class']);
	$total_class_half = ''; //$total_class_list[0];
	$total_class_ann = ''; //$total_class_list[1];
	$remark = ''; //$row['general_remark'];
	$exam_result = '';

	$term1_attendance = '';
	$term2_attendance = '';

	$attendance = '';
	$total_class = '';
	$stu_result = '';

    $email_id_embed = 'apsdecgmail.com';

    $section1 = '1'; // Header
    $html = '';

    if($section1 == '1')
	{
		$html = $style.'<div  style="border: 3px solid #0c3d03;padding:3px; border-radius:10px;">
				<div  style="border: 1.5px solid #0c3d03; border-radius:10px;">
					<table style="border-radius:10px;  width:100%">
						<tbody>
							<tr>
								<td style="width: 15%">
									<img src="'.url('/').'/public/school/images/cbse_logo.jpg" height="80"/>
								</td>
								<td>
									<p class="institute_heading1">'.$institute_name.'</p>
									<p class="institute_heading_sub1">'.$institute_address.'</p>
									<p class="institute_heading_sub1">'.$email_id_embed.' Tel No. -: '.$contact_number.'</p>
								</td>
								<td style="width: 15%;">
									<img  src="https://www.apsdigicamp.com/images/logo/APSDEC.jpg" height="70" />
								</td>
							</tr>
						</tbody>
					</table>
			        <table cellspacing="0" style="width:100%;border: 1px solid #0c3d03;">
				        <tr class="subject_heading1">
							<td class="subject_heading2" style="color: #800000;font-style: italic;">
								REPORT CARD  - SESSION  ( '.$session.' )
							</td>
						</tr>
			        </table>

					<table cellspacing="0" cellpadding="1" style="border: 1px solid #0c3d03; padding:5px;width:100%;text-align:left;font-size:11px;" class="table_data">
						<tr style="height: 17.333334px">
							<td class="student1">
								<p style="text-align:left;" >
									<span><b>NAME : </b></span>
									<span>'.$studentName.'</span>
								</p>
							</td>
							<td class="student2">
								<p>
									<span><b>D. O. B. :</b> </span>
									<span>'.date("d-m-Y", strtotime($dob)).'</span>
								</p>
							</td>
							<td class="student3">
								<p>
									<span><b>ADM. NO. :</b> </span>
									<span>'.$admission_no.'</span>
								</p>
							</td>
						</tr>

						<tr style="height: 17.333334px">
							<td class="student1">
								<p>
									<span><b>FATHER\'S NAME  :</b> </span>
									<span>'.$fathers_name.'</span>
								</p>
							</td>
							<td class="student2">
								<p>
									<span><b>CLASS - SECTION :</b> </span>
									<span>'.$class_name.' - '.$section_name.'</span>
								</p>
							</td>
							<td class="student3">
								<p>
									<span><b>ROLL NO. :</b> </span>
									<span>'.$roll_no.'</span>
								</p>
							</td>
						</tr>

						<tr style="height: 17.333334px">
							<td class="student1">
								<p>
									<span><b>MOTHER\'S NAME :</b> </span>
									<span>'.$mothers_name.'</span>
								</p>
							</td>
							<td class="student2">
								<p>
									<span><b>PHONE NO. : </b> </span>
									<span>'.$register_mobile_no.'</span>
								</p>
							</td>
							<td class="student3">

							</td>
						</tr>
						<tr style="height: 17.333334px">
							<td class="student1" colspan="3">
								<p>
									<span><b>ADDRESS :</b> </span>
									<span>'.$address.'</span>
								</p>
							</td>
						</tr>
					</table>';
    }

    $html .='<div class="content" >';
    $subject_index_count = 0;
    if($subject_index_count == 0 || $subject_index_count == 1 )
    {
        $max_component = 8;

    }
    else if( $subject_index_count == 2 )
    {
        $max_component = 10;
    }
    else if($subject_index_count == 3)
    {
        $max_component = 18;
    }

     //echo('<pre>');
     //print_r($odd_subject_array);die();
    // print_r($subject_index_count);
    // print_r($exam_subject_group_examsub_mapped);
     //die();

    foreach($loop_array AS $subject)
		{
			if($odd_subject_array[$subject_index_count] == '' || $even_subject_array[$subject_index_count] == '' )
			{
				/*$html .= '<tr>';
					$html .= '<td style="text-align:center;width:100%;text-align: center; width: 100%; background-color: antiquewhite;" colspan="2">'.$subject_index_count.$loop_array[$subject_index_count].'</td>';
				$html .= '</tr>';*/

			}
			else
			{
				//echo('<pre>');
                //print_r($exam_dispaly_name['exam_display_name']);die();

              $html .=' <div>


					      	 <div  style=" float: left; width: 49%;height:250px;border:1px solid black;font-size:12.5px; " >
						      	  <div  style=" float: left;text-align:center;width: 99%;border:1px solid black;background-color: antiquewhite;">
						      		'.$group_mapped_to_subject[$odd_subject_array[$subject_index_count]][0] .'
						      	 </div>
						      	   <div  style=" float: left;text-align:center;width: 67%;border:1px solid black;background-color: antiquewhite;">
						      		'.$odd_subject_array[$subject_index_count].'
						      	 </div>
						      	 <div  style=" float: left;width: 31.5%;border:1px solid black;background-color: antiquewhite;text-align:center;">
						      		'.$exam_dispaly_name[0]->exam_display_name.'
						      	 </div>
					      	 ';
                            /* for($componet=1;$componet<=5;$componet++)
					      	 {
							 	$html.=' <div  style=" float: left;width: 67.6%;vertical-align: top;	border-right:1px solid black;">
					      					Writing skills XX
								      	 </div>
								      	 <div  style=" float: left;width: 31.5%;vertical-align: top; border-right:1px solid black;">
								      		EVI2
								      	 </div>
								      	 ';

							 }*/
							/* echo('<pre>');
							 print_r($exam_subject_group_examsub_mapped[$odd_subject_array[$subject_index_count]]);

							 die();*/
							 $componet = 1;
                             $co_loop_count = 0;



							 foreach($exam_subject_group_examsub_mapped[$odd_subject_array[$subject_index_count]] AS $exam_subject_group)
							 {


							 	if($odd_subject_array[$subject_index_count] == 'CO-CURRICULAR ACTIVITIES')
							 	//if($subject_index_count == 3)
							 	{
                                    $key_array = array_keys($exam_subject_group_examsub_mapped[$odd_subject_array[$subject_index_count]]);
                                    $key_count = 0;

							 		if($co_loop_count < 1)
							 		{
								 		foreach($exam_subject_group_examsub_mapped[$odd_subject_array[$subject_index_count]] AS $sub_subject_name_array)
								 		{

												$html.=' <div  style=" float: left;width: 67.6%;vertical-align: top;height:30px;	border-right:1px solid black;">
							      					&nbsp;&nbsp;<b>'.$key_array[$key_count].'</b>
										      	 </div>
										      	 <div  style=" float: left;width: 31.5%;vertical-align: top;height:30px; border-right:0px solid black;">

										      	 </div>';
										    	$componet++;
												foreach($sub_subject_name_array  AS $exam_subject_name)
									 			{

											      	$exam_subject_display_array = array();
											      	$exam_subject_display_array = explode('@!!',$exam_subject_name);


                                                     // $key = $student_code.'@!!'.$exam_subject_display_array[1];
                                                     $exam_code = $exam_dispaly_name[0]->exam_code;
                                                     $key = $exam_code.'@'.$exam_subject_display_array[1].'@'.$student_code;
											      	//'.$exam_subject_display_array[1].'
											      	/*..*/
													$html.=' <div  style=" float: left;width: 67.6%;vertical-align: top;height:30px;	border-right:1px solid black;">
								      					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$exam_subject_display_array[0].'
											      	 </div>
											      	 <div  style=" float: left;width: 31.5%;vertical-align: top; border-right:0px solid black;height:30px;text-align:center;">'

											      		.$student_mark_array[$key].
											      	'</div>';
										 			$componet++;
												}

												$key_count++;

										}
										$co_loop_count++;
									}



								}
								else
								{
                                    foreach($exam_subject_group AS $exam_subject_name)
                                    {
                                        $exam_subject_display_array = array();
                                       $exam_subject_display_array = explode('@!!',$exam_subject_name);
                                      // $key = $student_code.'@!!'.$exam_subject_display_array[1];
                                      $exam_code = $exam_dispaly_name[0]->exam_code;
                                      $key = $exam_code.'@'.$exam_subject_display_array[1].'@'.$student_code;

                                      $html.=' <div  style=" float: left;width: 67.6%;vertical-align: top;height:30px;	border-right:1px solid black;">
                                                 &nbsp;&nbsp;'.$exam_subject_display_array[0].'
                                              </div>
                                              <div  style=" float: left;width: 31.5%;vertical-align: top;height:30px; border-right:0px solid black;text-align:center;">
                                                 '.$student_mark_array[$key].'
                                              </div>
                                              ';
                                        $componet++;
                                   }
								}


							 }
					      	if($subject_index_count == 0 || $subject_index_count == 1 )
							{
								$max_component = 7;
							}
							else if( $subject_index_count == 2 )
							{
								$max_component = 7;
							}
							else if($subject_index_count == 3)
							{
								$max_component = 17;
							}
							$white_spcae_div = '<div  style=" float: left;width: 67.6%;vertical-align: top;height:30px;border-right:1px solid black;color:white;">
					      					Writing skills 1
								      	 </div>
								      	 <div  style=" float: left;width: 31.5%;vertical-align: top;height:30px; border-right:0px solid black;color:white;">
								      		SEVI
								      	 </div>';
							$white_pace_repoeat_count = $max_component - $componet;
							$white_pace_repoeat_count += 1;
							if($white_pace_repoeat_count > 0)
							{
								$html.= str_repeat($white_spcae_div,$white_pace_repoeat_count);
							}

					     $html .='</div>
					      <div class="green"  style=" float: left; width: 49%;background-color: yellow1;height:250px;border:1px solid black;margin-left:10px;font-size:12.5px;" >
					         <div  style=" float: left;text-align:center;width: 99%;border:1px solid black;background-color: antiquewhite;">
					      		'.$group_mapped_to_subject[$even_subject_array[$subject_index_count]][0] .'
					      	 </div>
					        <div  style=" float: left;text-align:center;width: 67%;border:1px solid black;background-color: antiquewhite;">
					      		'.$even_subject_array[$subject_index_count].'
					      	 </div>
					      	 <div  style=" float: left;width: 31.5%;border:1px solid black;background-color: antiquewhite;text-align:center;">
					      		'.$exam_dispaly_name[0]->exam_display_name.'
					      	 </div>
					      	 ';

					      	/* for($componet=1;$componet<=5;$componet++)
					      	 {
							 	$html.=' <div  style=" float: left;width: 67.6%;vertical-align: top;	border-right:1px solid black;">
					      					Writing skills YY
								      	 </div>
								      	 <div  style=" float: left;width: 31.5%;vertical-align: top; border-right:1px solid black;">
								      		EVI
								      	 </div> ';

							 }*/

							 $componet = 1;
							 $co_loop_count = 0;
							 foreach($exam_subject_group_examsub_mapped[$even_subject_array[$subject_index_count]] AS $exam_subject_group)
							 {
							 	if($even_subject_array[$subject_index_count] == 'CO-CURRICULAR ACTIVITIES')
							 	{

                                    $key_array = array_keys($exam_subject_group_examsub_mapped[$even_subject_array[$subject_index_count]]);
                                    $key_count = 0;

                                    if($co_loop_count < 1)
							 		{
								 		foreach($exam_subject_group_examsub_mapped[$even_subject_array[$subject_index_count]] AS $sub_subject_name_array)
								 		{

												$html.=' <div  style=" float: left;width: 67.6%;vertical-align: top;height:30px;border-right:1px solid black;">
							      					&nbsp;&nbsp;<b>'.$key_array[$key_count].'</b>
										      	 </div>
										      	 <div  style=" float: left;width: 31.5%;vertical-align: top;height:30px; border-right:0px solid black;">

										      	 </div>';
										    	$componet++;
												foreach($sub_subject_name_array  AS $exam_subject_name)
									 			{

											      	$exam_subject_display_array = array();
													$exam_subject_display_array = explode('@!!',$exam_subject_name);

													$key = $student_code.'@!!'.$exam_subject_display_array[1];


													$html.=' <div  style=" float: left;width: 67.6%;vertical-align: top;height:30px;	border-right:1px solid black;text-align:left;">
								      					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$exam_subject_display_array[0].'
											      	 </div>
											      	 <div  style=" float: left;width: 31.5%;vertical-align: top;height:30px; border-right:0px solid black;text-align:center;">
											      		'.$student_mark_array[$key].'
											      	 </div>';
										 			$componet++;
												}

												$key_count++;

										}
										$co_loop_count++;
									}


								}
								else
								{
                                    foreach($exam_subject_group AS $exam_subject_name)
								 	{
								 		$exam_subject_display_array = array();
										$exam_subject_display_array = explode('@!!',$exam_subject_name);
										//$key = $student_code.'@!!'.$exam_subject_display_array[1];
                                        $exam_code = $exam_dispaly_name[0]->exam_code;
                                        $key = $exam_code.'@'.$exam_subject_display_array[1].'@'.$student_code;

										$html.=' <div  style=" float: left;width: 67.6%;vertical-align: top;height:30px;	border-right:1px solid black;">
						      					&nbsp;&nbsp;'.$exam_subject_display_array[0].'
									      	 </div>
									      	 <div  style=" float: left;width: 31.5%;vertical-align: top;height:30px; border-right:0px solid black;text-align:center;">
									      		'.$student_mark_array[$key].'
									      	 </div>
									      	 ';
								 		$componet++;
									}

								}


							 }
					      	 if($subject_index_count == 0 || $subject_index_count == 1 )
							{
								$max_component = 7;
							}
							else if( $subject_index_count == 2 )
							{
								$max_component = 7;
							}
							else if($subject_index_count == 3)
							{
								$max_component = 17;
							}
					      	// echo(10-$componet);die();
					      	$white_spcae_div = '<div  style=" float: left;width: 67.6%;vertical-align: top;height:30px;border-right:1px solid black;color:white;">
					      					Writing skills 1
								      	 </div>
								      	 <div  style=" float: left;width: 31.5%;vertical-align: top;height:30px; border-right:0px solid black;color:white;">
								      		SEVI
								      	 </div>';
							$white_pace_repoeat_count = $max_component - $componet;
							$white_pace_repoeat_count += 1;
							if($white_pace_repoeat_count > 0)
							{
								$html.= str_repeat($white_spcae_div,$white_pace_repoeat_count);
							}



					     /* 	for($x = 0;$x<=($max_component-$componet);$x++)
					      	{
								$html.=' <div  style=" float: left;width: 67.6%;vertical-align: top;height:30px;border-right:1px solid black;color:white;">
					      					'.$subject_index_count.$max_component.'DDWriting skills 1
								      	 </div>
								      	 <div  style=" float: left;width:31.5%;vertical-align: top;height:30px;border-right:0px solid black;color:white;">
								      		GREVIDD
								      	 </div>';
							}*/
				$html.='</div>';
				$html.='</div>';
			}
			if($subject_index_count == 2)
			{
				//echo('HII');die();

				$html.= '</div>';
				$html.= '</div>';
				$html.= '</div>';


			}
			$subject_index_count++;
		}
		$html.='<div class="green"  style=" float: left; width: 100%;background-color: yellow1;height:100px;border:1px solidfont-size:12.5px;" >
					        <div  style=" float: left;text-align:center;width: 49.5%;border:1px solid black;background-color: antiquewhite;">
					      		HEALTH STATUS
					      	 </div>
					      	 <div  style=" float: left;width: 49.7%;border:1px solid black;background-color: antiquewhite;text-align:center;">
					      		'.$exam_dispaly_name[0]->exam_display_name.'
                               </div> ';
        $html.=' <div  style=" float: left;width: 49.5%;vertical-align: top;border-right:1px solid black;">
            &nbsp;&nbsp;Height(Cm)
        </div>
        <div  style=" float: left;width:49.7%;vertical-align: top; border-right:1px solid black;text-align:center;">
            Height(Cm)
        </div> ';
        $html.=' <div  style=" float: left;width: 49.5%;vertical-align: top;	border-right:1px solid black;">
					&nbsp;&nbsp;Weight(Kg)
				</div>
				<div  style=" float: left;width:49.7%;vertical-align: top; border-right:1px solid black;text-align:center;">
                    Weight(Kg)
				 </div>
				';
        $html.='<div class="green"  style=" float: left; width: 100%;background-color: antiquewhite;height:10px;border:1px solidfont-size:12.5px;" >
                <div  style=" float: left;text-align:left;width: 100%;font-size:10px;border:1px solid black;background-color: antiquewhite;">
                      &nbsp;&nbsp;<b>ATTENDANCE</b>
                   </div>
               </div>';
        $html.=' <div  style=" float: left;width: 20.3%;font-size:13px;vertical-align: top;border-right:1px solid black;text-align:center;background-color: antiquewhite;border-bottom:1px solid black;">
                EV1
            </div>
            <div  style=" float: left;width:79.2%;height:10px;font-size:13px; vertical-align: top; border-right:1px solid black;text-align:left;background-color: antiquewhite1;border-bottom:1px solid black;">
                    ATTENDANCE
             </div>';
             $html.='<div class="green"  style=" float: left; width: 100%;font-size:10px;background-color: antiquewhite;height:10px;border:1px solidfont-size:12.5px;" >
             <div  style=" float: left;text-align:left;width: 100%;border:1px solid black;background-color: antiquewhite;">
                   &nbsp;&nbsp;<b>SPECIFIC PARTICIPATION</b>
                </div>
            </div>';
        $html.=' <div  style=" float: left;width: 20.3%;font-size:10px;vertical-align: top;border-right:1px solid black;text-align:center;background-color: antiquewhite;border-bottom:1px solid black;font-size:13px;">
             EV1
         </div>
         <div  style=" float: left;width:79.2%;height:10px;font-size:10px; vertical-align: top; border-right:1px solid black;text-align:left;background-color: antiquewhite1;border-bottom:1px solid black;font-size:13px;">
                specific_participation
          </div>';
          $html.='<div class="green"  style=" float: left; width: 100%;background-color: antiquewhite;height:10px;border:1px solidfont-size:12.5px;" >
          <div  style=" float: left;text-align:left;width: 100%;font-size:10px;border:1px solid black;background-color: antiquewhite;">
                &nbsp;&nbsp;<b>GENERAL REMARK</b>
             </div>
         </div>';
        $html.='<div class="green"  style=" float: left; width: 100%;font-size:10px;background-color: antiquewhite;height:10px;border:1px solid" >
          <div  style=" float: left;text-align:left;width: 100%;border:0px solid black;background-color: white;font-size:13px;">
                 general_remark
             </div>
         </div>';

         $html.='<div class="green"  style=" float: left; width: 100%;background-color: antiquewhite;height:10px;border:1px solidfont-size:12.5px;" >
         <div  style=" float: left;text-align:left;width: 100%;font-size:10px;border:1px solid black;background-color: antiquewhite;">
               &nbsp;&nbsp;<b>RESULT</b>
            </div>
        </div>';
     $html.='<div class="green"  style=" float: left; width: 100%;background-color: antiquewhite;height:10px;border:1px solidfont-size:12.5px;" >
         <div  style=" float: left;text-align:left;width: 100%;border:1px solid black;background-color: white;">
                result
            </div>
        </div>';

        if($institute_code == 'APSGOL')
        {
           $sign_waterMark = '&nbsp;&nbsp;&nbsp;&nbsp;';
       }
       else
       {
           $sign_waterMark = 'S I G N A T U R E S';
       }
       $html.='<table cellspacing="0" style="margin-left:0.5pt;width: 100%; border-collapse: collapse;border: 1px solid #0c3d03;  " class="table_data" border="0">
                       <tr>
                           <td colspan="3" class="student2" style="width: 20%;font-size:9px; text-align:left;">
                               <p>
                                   <span class="student2" style="font-size:9pt;"><b>&nbsp;&nbsp;&nbsp;Issue Date :</b> </span>
                                   <span style="color:#800000;font-family:Arial;font-size:9pt;text-transform:none;font-weight:bold;font-style:normal;font-variant:normal;">'.$date_of_issue.'</span>
                               </p>
                           </td>
                       </tr>
                       <br>
                       <tr>
                           <td colspan="3" class="signature1" style="padding-left:10%">
                               <p  class="signature2"><span  class="signature3">'.$sign_waterMark.'</span></p>
                           </td>
                       </tr>
                           <tr>';
                    $signature_names = array();
                   if(count($signature_names) > 0)
                   {
                        $width = 100/(count($signature_names));
                        for($j=0; $j < count($signature_names); $j++)
                        {
                            $html .=	'<td class="student2" style="width:'.$width.'%;text-align:center;vertical-align:top;">
                                                <p style="text-align: right;">
                                                    <span style="font-size:12px;"><b>'.$signature_names[$j].'</b></span>
                                                </p>
                                            </td>';
                        }

                   }

                   $html .=	'</tr>
                             </table>'	;




    $html.= '</div>';
    $html.= '</div>';
    //echo($html);die();
    $mpdf->WriteHTML($html);
    $pageCount++;
    if($pageCount < count($studentDetails))
    {
    	$mpdf->AddPage();
    }

    $student_code = '';

}
$mpdf->Output("test.pdf","I");exit;


?>
