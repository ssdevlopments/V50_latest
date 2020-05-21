<?php

function getGrade($total_mark,$weightage)
{
	$grade = '';
	if($weightage > 0)
	{
		if($weightage < 100)
		{
			$total_mark = round(($total_mark / $weightage) * 100);
		}
	}
	else
	{
		$total_mark = 0.0;
	}
	if($total_mark >= 91 && $total_mark <= 100)
	{
		$grade = 'A1';
	}
	else if($total_mark >= 81 && $total_mark < 90.99)
	{
		$grade = 'A2';
	}
	else if($total_mark >= 71 && $total_mark < 80.99)
	{
		$grade = 'B1';
	}
	else if($total_mark >= 61 && $total_mark < 70.99)
	{
		$grade = 'B2';
	}
	else if($total_mark >= 51 && $total_mark < 60.99)
	{
		$grade = 'C1';
	}
	else if($total_mark >= 41 && $total_mark < 50.99)
	{
		$grade = 'C2';
	}
	else if($total_mark >= 33 && $total_mark < 40.99)
	{
		$grade = 'D';
	}
	else if($total_mark >= 0.0 && $total_mark < 33)
	{
		$grade = 'E';
	}

	return $grade;
}

$session_code = Session::get('session_code');
$institute_code = Session::get('institute_code');

$session = explode("_", $session_code);
$session = $session[0];

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

//Get subject Details
$results = DB::table(DB::raw('k12.reportcard_exam_mapping as rem'))
		->join(DB::raw('k12.exam_master em'), function ($join) use($session_code) {
			$join->on(DB::raw('em.exam_code'), '=', DB::raw('rem.exam_code'))
				->where(DB::raw('em.session_code'), '=', $session_code);
		})
		->join(DB::raw('k12.exam_type_master t'), function ($join) use($session_code) {
			$join->on(DB::raw('t.exam_type_code'), '=', DB::raw('em.exam_type_code'))
				->where(DB::raw('t.session_code'), '=', $session_code);
		})
		->join(DB::raw('k12.exam_subject_mapping esp'), function ($join) use($session_code) {
			$join->on(DB::raw('esp.exam_code'), '=', DB::raw('em.exam_code'))
				->where(DB::raw('esp.session_code'), '=', $session_code);
		})
		->join(DB::raw('k12.exam_subject_master esm'), function ($join) use($session_code) {
			$join->on(DB::raw('esm.exam_subject_code'), '=', DB::raw('esp.exam_subject_code'))
				->where(DB::raw('esm.session_code'), '=', $session_code);
		})
		->join(DB::raw('k12.ac_subject_master s'), function ($join) use($session_code) {
			$join->on(DB::raw('s.subject_code'), '=', DB::raw('esm.subject_code'))
				->where(DB::raw('s.session_code'), '=', $session_code);
		})
		->join(DB::raw('k12.ac_course_class_subject accs'), function ($join) use($course_code, $class_code, $session_code) {
			$join->on(DB::raw('accs.subject_code'), '=', DB::raw('s.subject_code'))
				->where(DB::raw('accs.class_code'), '=', $class_code)
				->where(DB::raw('accs.course_code'), '=', $course_code)
				->where(DB::raw('accs.session_code'), '=', $session_code);
		})
		->select(DB::raw("DISTINCT esp.sl_no,esm.exam_subject_code,esm.exam_sub_name,esm.exam_sub_group,
					s.subject_name,s.subject_code,
					split_part(accs.group_value,'_',1) as group_value"))
		->where(DB::raw('rem.institute_code'), $institute_code)
		->where(DB::raw('rem.session_code'), $session_code)
		->where(DB::raw('rem.reportcard_code'), $reportcard_code)
		->where(DB::raw("split_part(t.exam_type_code, '_', 1)"),"=", DB::raw("'SCH'"))
		->orderBy('esp.sl_no')
		->get()->toArray();
		//->toSql();

$examSubjectDetailsAdd = array();
$examSubjectDetails = array();

foreach($results as $row)
{
	if($row->group_value == 'CADDI' || $row->group_value == 'OADDI')
	{
		$examSubjectDetailsAdd[] = $row;
	}
	else
	{
		$exam_subject_code = $row->exam_subject_code;
		$examSubjectDetails[$exam_subject_code] = $row;
	}
}

$examSubjectDetails_test = array();
foreach ($examSubjectDetails as $key => $row)
{
    $examSubjectDetails_test[] = $row;
}

array_multisort($examSubjectDetails_test, SORT_ASC, $examSubjectDetails);

$examSubjectDetails = $examSubjectDetails_test;

/*echo '<pre>';
print_r($examSubjectDetails);
exit;*/

// Reportcard Details
$rc = $reportcard_code;
$rc = $rc.','.str_replace('REPBK','REPCD', $rc);
$rc = explode(",", $rc);

$results = DB::table(DB::raw('k12.reportcard_exam_mapping as REM'))
	->join(DB::raw('k12.exam_master E'), function ($join) use($session_code) {
		$join->on(DB::raw('E.exam_code'), '=', DB::raw('REM.exam_code'))
			->where(DB::raw('E.session_code'), '=', $session_code);
	})
	->join(DB::raw('k12.exam_type_master t'), function ($join) use($session_code) {
			$join->on(DB::raw('t.exam_type_code'), '=', DB::raw('E.exam_type_code'))
				->where(DB::raw('t.session_code'), '=', $session_code);
		})
	->select(DB::raw("REM.exam_code,E.exam_name,REM.weightage,E.exam_type_code,
					split_part(REM.reportcard_code,'_',1) as reportcard_code"))
	->where(DB::raw('REM.institute_code'), $institute_code)
	->where(DB::raw('REM.session_code'), $session_code)
	->whereIn(DB::raw('REM.reportcard_code'), $rc)
	->where(DB::raw("split_part(t.exam_type_code, '_', 1)"),"=", DB::raw("'SCH'"))
	->orderBy('sequence_no')
	->get()->toArray();

$exam_codes_arr1 = array();
$term1_rc_exammapped_array = array();
$term2_rc_exammapped_array = array();
$term2_rc_exammapped_array_all = array();

$term1_mapped_weightage_sum = 0;

foreach($results as $row)
{
	if($row->reportcard_code == 'REPCD')
	{
		$term1_rc_exammapped_array[] = $row->exam_code;
		$term1_mapped_weightage_sum += $row->weightage;
	}
	else
	{
		$exam_codes_arr1[] = $row;
	}
	$term2_rc_exammapped_array_all[] = $row;
}

$term2_mapped_weightage_sum = 0;
foreach($term2_rc_exammapped_array_all as $row)
{
	if(!in_array($row->exam_code, $term1_rc_exammapped_array))
	{
		$term2_rc_exammapped_array[] = $row;
		$term2_mapped_weightage_sum += $row->weightage;
	}
}

/*echo '<pre>';
print_r($term1_rc_exammapped_array);
print_r($term2_rc_exammapped_array);
exit;*/

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

/*echo '<pre>';
print_r($results);
exit;*/

$examMarkGrade = array();
foreach ($results as $row)
{
	$exam_code = $row->exam_code;
	$exam_subject_code = $row->exam_subject_code;
	$student_code = $row->student_code;
	$key = $exam_code.'@'.$exam_subject_code.'@'.$student_code;
	$examMarkGrade[$key] = $row;
}

//Get Coscholastic subject Details
$examCoschSubjectDetails = DB::table(DB::raw('k12.reportcard_exam_mapping as rem'))
		->join(DB::raw('k12.exam_master em'), function ($join) use($session_code) {
			$join->on(DB::raw('em.exam_code'), '=', DB::raw('rem.exam_code'))
				->where(DB::raw('em.session_code'), '=', $session_code);
		})
		->join(DB::raw('k12.exam_type_master t'), function ($join) use($session_code) {
			$join->on(DB::raw('t.exam_type_code'), '=', DB::raw('em.exam_type_code'))
				->where(DB::raw('t.session_code'), '=', $session_code);
		})
		->join(DB::raw('k12.exam_subject_mapping esp'), function ($join) use($session_code) {
			$join->on(DB::raw('esp.exam_code'), '=', DB::raw('em.exam_code'))
				->where(DB::raw('esp.session_code'), '=', $session_code);
		})
		->join(DB::raw('k12.exam_subject_master esm'), function ($join) use($session_code) {
			$join->on(DB::raw('esm.exam_subject_code'), '=', DB::raw('esp.exam_subject_code'))
				->where(DB::raw('esm.session_code'), '=', $session_code);
		})
		->join(DB::raw('k12.ac_subject_master s'), function ($join) use($session_code) {
			$join->on(DB::raw('s.subject_code'), '=', DB::raw('esm.subject_code'))
				->where(DB::raw('s.session_code'), '=', $session_code);
		})
		->join(DB::raw('k12.ac_course_class_subject accs'), function ($join) use($course_code, $class_code, $session_code) {
			$join->on(DB::raw('accs.subject_code'), '=', DB::raw('s.subject_code'))
				->where(DB::raw('accs.class_code'), '=', $class_code)
				->where(DB::raw('accs.course_code'), '=', $course_code)
				->where(DB::raw('accs.session_code'), '=', $session_code);
		})
		->select(DB::raw("DISTINCT esp.sl_no,esm.exam_subject_code,esm.exam_sub_name,esm.exam_sub_group,
			s.subject_name,s.subject_code,em.exam_name,em.exam_code,esp.entry_type,esp.max_mark,esp.allowed_grade"))
		->where(DB::raw('rem.institute_code'), $institute_code)
		->where(DB::raw('rem.session_code'), $session_code)
		->where(DB::raw('rem.reportcard_code'), $reportcard_code)
		->where(DB::raw("split_part(t.exam_type_code, '_', 1)"),"=", DB::raw("'COSCH'"))
		->orderBy('esp.sl_no')
		->get()->toArray();

/*echo '<pre>';
print_r($examCoschSubjectDetails);
exit;*/

// institute details
$results = DB::table(DB::raw('institute.institute_master as A'))
	->select(DB::raw("*"))
	->where(DB::raw('A.institute_code'), $institute_code)->get()->toArray();
$institute_name = $results[0]->institute_name;
$contact_number = $results[0]->contact_number;
$institute_address = $results[0]->location;
$logo_url = $results[0]->logo_url;
$affiliation_no = $results[0]->affiliation_no;
$email_id_embed = '';

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

/*echo '<pre>';
print_r($absent_reason);
exit;*/

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

/*echo '<pre>';
print_r($studentDetails);
exit;*/


$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
/*echo '<pre>';
print_r($defaultConfig);
exit;*/
$orientation = 'L';
$margin_left = 1;
$margin_right = 1;
$margin_top = 1;
$margin_bottom = 1;
$mpdf = new \Mpdf\Mpdf([
	'mode' => 'utf-8',
	'format' => 'A4'.($orientation == 'L' ? '-L' : ''),
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

$style = '<link rel="stylesheet" media="print" href="'.url('/').'/resources/views/school/cards/APS/reportcard_styles_v.css" />';

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

	$section2 = '2'; // Exam Header
	if($section2 == '2')
	{
		//$colspan = sizeof($exam_codes_arr1)+2;// 2 is added for mark obtained and grade

		//term 1 colspan
		$colspan_term1 = sizeof($term1_rc_exammapped_array)+2;// 2 is added for mark obtained and grade

		//term 2 colspan
		$colspan_term2 = sizeof($term2_rc_exammapped_array)+2;// 2 is added for mark obtained and grade

		if(sizeof($exam_codes_arr1) > 0 )
		{
			$dynamic_widith = 100/sizeof($exam_codes_arr1);
			$dynamic_widith = 50/sizeof($exam_codes_arr1);
		}
		else
		{
			$dynamic_widith = '11'; //default
		}

		$count = 1;
		$html .= '<table cellspacing="0" style="width:100%;border: 1px solid #0c3d03;">
				        <tr style="height: 10px;" class="subject_heading1">
							<td class="subject_heading2" style="color: #800000;font-style: italic;">
								SCHOLASTIC AREAS
							</td>
						</tr>
			        </table>
					<table cellspacing="0" cellpadding="5" style="width: 100%; border: 1px solid #0c3d03; border-collapse: collapse;">
						<tr style="border: 1px solid #0c3d03;">
							<td class="data_top1" rowspan="2" style="width:18%;">
								<p class="data_top2">
									<span class="data_top3" style="font-size:18px;width:50%;word-break: break-all !important;">SUBJECTS</span>
								</p>
							</td>
							<td class="data_top1" colspan="'.$colspan_term1.'" style="width:'.$dynamic_widith.'%">
								<p class="data_top2">
									<b>TERM 1</b>
								</p>
							</td>
							<td class="data_top1" colspan="'.$colspan_term2.'" style="width:'.$dynamic_widith.'%">
								<p class="data_top2">
									<b>TERM 2</b>
								</p>
							</td>
						</tr>
						<tr>';
		$gross_weightage_display = 0;
		$mapped_exam_count = 1;
		$term1_weightage_sum = 0;
		$term2_weightage_sum = 0;
		foreach($exam_codes_arr1 AS $examheaderdispaly)
		{
			$html.= '<td  class="data_top1" style="width:11%">
						<p class="data_top2">
							<span class="data_top3" style="width:'.$dynamic_widith.'%;font-size:15px !important;">'.$examheaderdispaly->exam_name.'<br> ( '.$examheaderdispaly->weightage.' )</span>
						</p>
					</td>';

			$term1_weightage_sum += $examheaderdispaly->weightage;
			$gross_weightage_display += $examheaderdispaly->weightage;
			$term2_weightage_sum += $examheaderdispaly->weightage;

			if(sizeof($term1_rc_exammapped_array) == $mapped_exam_count)
			{
				$term2_weightage_sum = 0;
				$html .= '<td  class="data_top1" style="width:11%">
						<p class="data_top2">
							<span class="data_top3" style="width:11%;font-size:18px !important;">Marks <br>Obtained <br>('.$term1_weightage_sum.')</span>
						</p>
					</td>
					<td  class="data_top1" style="width:9%">
						<p class="data_top2">
							<span class="data_top3" style="width:9%;font-size:18px !important;">Grade</span>
						</p>
					</td>
				';
			}
			$mapped_exam_count++;
		}

		$html .= '<td class="data_top1" style="width:11%">
						<p class="data_top2">
							<span class="data_top3">Marks <br>Obtained <br>('.$term2_weightage_sum.')</span>
						</p>
					</td>
					<td class="data_top1" style="width:9%;font-size:18px !important;">
						<p class="data_top2">
							<span class="data_top3" style="width:9%;font-size:18px !important;">Grade</span>
						</p>
					</td>
				</tr>';
	}

	$section3 = '3'; // Subjects Rows
	if($section3 == '3')
	{
		$no_of_subject = sizeof($examSubjectDetails);

		$term1_sum_obtained_weightage = 0;
		$term2_sum_obtained_weightage = 0;




		$grades = array();
		$secured_mark_total = 0.00;
		$max_mark_total = 0;
		$overall_grade = '';
		$rank = '';
		$percentage = 0.00;

		$exam_subect_names = array();
		$exam_subject_exam_wise_marks = array();
		$exam_subject_total_mark_obtained = array();
		$exam_subject_grade = array();
		$coscholastic_exam_subject_names = array();
		$coscholastic_exam_subject_wise_marks = array();
		$reportcard_issue_date = $date_of_issue;

		foreach($examSubjectDetails as $row1)
		{


			$mapped_exam_count = 1;

			$is_MLNAD = 'NO';

			$exam_sub_code = $row1->exam_subject_code;
			$exam_sub_code_arr = explode("_",$exam_sub_code);
			$mark_grade_total1 = 0;
			$weightage_total1 = 0;
			$total_grade = '';
			if($row1->group_value == 'MAIN' || $row1->group_value == 'MTERM')
			{
				$grades[] = $row1->exam_sub_name;
			}
			else
			{
				$grades[] = $row1->exam_sub_name."&nbsp;<span style='color:red; font-weight:bold; font-size:15px; '>*</span>";
			}

			$subject_wise_term1_weightage_sum = 0;
			$subjectWise_term1_max_weightage_apered = 0;

			$subject_wise_term2_weightage_sum = 0;
			$subjectWise_term2_max_weightage_apered = 0;

			$grade_status = TRUE;
			$grade_entry_status = FALSE;

			foreach($exam_codes_arr1 as $row2)
			{
				$exam_code = $row2->exam_code;
				$exam_type_code = $row2->exam_type_code;
				$weightage = $row2->weightage;

				$count++;

				$mark = 0;
				$total_mark = '';
				$secured_mark = '';
				$entry_type = '';

				$key = $exam_code.'@'.$exam_sub_code.'@'.$student_code;
				/*echo '<pre>';
				print_r($examMarkGrade);
				exit;*/
				if (array_key_exists($key,$examMarkGrade))
				{
					$entry_type = $examMarkGrade[$key]->entry_type;
					$total_mark = $examMarkGrade[$key]->max_mark;

					if($entry_type == 'MARK')
					{
						$secured_mark = $examMarkGrade[$key]->mark_secured;

						$mark = ((float)$secured_mark / (float)$total_mark)*(int)$weightage;
						$mark = number_format($mark, 1, '.', '');

						if(is_numeric($mark))
						{
							if($mark >= 0)
							{
								$subject_wise_term1_weightage_sum += $mark;
								$subject_wise_term2_weightage_sum += $mark;

								$subjectWise_term1_max_weightage_apered+= $weightage;
								$subjectWise_term2_max_weightage_apered+=$weightage;
							}
						}

						if((float)$secured_mark >= 0)
						{
							$weightage_total1 = $weightage_total1 + (int)$weightage;

							$mark_grade_total1 = $mark_grade_total1 + $mark;
							if((float)$secured_mark == 0)
							{
								$grades[] = '0.0';
								$grade_status = FALSE;
							}
							else
							{
								$grades[] = $mark;
							}
						}
						else
						{

							if(array_key_exists($secured_mark,$absent_reason))
							{
								if( $absent_reason[$secured_mark] == 'ABS')
								{
									$subjectWise_term1_max_weightage_apered+= $weightage;
									$subjectWise_term2_max_weightage_apered+=$weightage;
									$weightage_total1 = $weightage_total1 + (int)$weightage;
								}

								$a_Reason = $absent_reason[$secured_mark];
								if($a_Reason == 'ML' ||  $a_Reason == 'OD' ||  $a_Reason == 'NAD')
								{
									$is_MLNAD = 'YES';
								}
								$grades[] = $absent_reason[$secured_mark];
							}
						}
					}
					else
					{
						$secured_mark = $examMarkGrade[$key]->grade_secured;
						if(array_key_exists($secured_mark,$absent_reason))
						{
							$grades[] = $absent_reason[$secured_mark];
						}
						else
						{
							$grades[] = $secured_mark;
						}

						$grade_entry_status = TRUE;
					}
				}

				if(sizeof($term1_rc_exammapped_array) == $mapped_exam_count)
				{
					$subject_wise_term2_weightage_sum = 0;
					$subjectWise_term2_max_weightage_apered = 0;

					if($row1->group_value == 'MAIN' || $row1->group_value == 'MTERM')
					{
						if($subjectWise_term1_max_weightage_apered > 0)
						{
							$recalculated_mark = ($subject_wise_term1_weightage_sum/$subjectWise_term1_max_weightage_apered)*$term1_mapped_weightage_sum;
							$recalculated_mark = number_format($recalculated_mark, 1, '.', '');
						}
						else
						{
							$recalculated_mark = '';
						}

						$grades[] = $recalculated_mark;

						if($recalculated_mark > 0)
						{
							$term1_sum_obtained_weightage += $recalculated_mark;
							$grades[] = getGrade($subject_wise_term1_weightage_sum,$subjectWise_term1_max_weightage_apered);
						}
						else
						{
							$grades[] = '';
						}
					}
					else
					{
						$term_1_recalculated_mark = 0;
						if(is_numeric($subjectWise_term1_max_weightage_apered) && $subjectWise_term1_max_weightage_apered > 0)
						{
							$term_1_recalculated_mark = (number_format($subject_wise_term1_weightage_sum, 1, '.', '')/$subjectWise_term1_max_weightage_apered)*$term1_mapped_weightage_sum;
							$term_1_recalculated_mark = number_format($term_1_recalculated_mark, 1, '.', '');

							$grades[] = $term_1_recalculated_mark;
							$grades[] = getGrade($term_1_recalculated_mark,$term1_mapped_weightage_sum);
						}
						else
						{
							$grades[] = '';
							$grades[] = '';
						}
					}
				}

				$mapped_exam_count++;


			} // Exam end

			if($row1->group_value == 'MAIN' || $row1->group_value == 'MTERM')
			{
				if($subjectWise_term2_max_weightage_apered > 0)
				{
					$term_2_recalculated_mark = ($subject_wise_term2_weightage_sum/$subjectWise_term2_max_weightage_apered)*$term2_mapped_weightage_sum;
					$term_2_recalculated_mark = number_format($term_2_recalculated_mark, 1, '.', '');
				}
				else
				{
					$term_2_recalculated_mark = 0.0;
				}
				$grades[] = $term_2_recalculated_mark;
				$term2_sum_obtained_weightage += $term_2_recalculated_mark;
				$grades[] = getGrade($term_2_recalculated_mark,$term2_mapped_weightage_sum);;
			}
			else
			{
				if($subjectWise_term2_max_weightage_apered > 0)
				{
					$term_2_recalculated_mark = (number_format($subject_wise_term2_weightage_sum, 1, '.', '')/$subjectWise_term2_max_weightage_apered)*$term2_mapped_weightage_sum;

					$term_2_recalculated_mark = number_format($term_2_recalculated_mark, 1, '.', '');
					$term_2_recalculated_mark = number_format($term_2_recalculated_mark, 1, '.', '');

					$grades[] = $term_2_recalculated_mark;
					$grades[] = getGrade($term_2_recalculated_mark,$term2_mapped_weightage_sum);
				}
				else
				{
					$grades[] = '';
					$grades[] ='';
				}

			}

			if($row1->group_value == 'MAIN' )
			{
				if($is_MLNAD == 'YES')
				{
					$secured_mark_total += $recalculated_mark;
				}
				else
				{
					$secured_mark_total += $mark_grade_total1;
				}
				$max_mark_total += 100;
			}
			else if( $row1->group_value == 'MTERM' )
			{
				if($is_MLNAD == 'YES')
				{
					$secured_mark_total += $recalculated_mark;
				}
				else
				{
					$secured_mark_total += $mark_grade_total1;
				}
				$max_mark_total += 100;
			}

		} // Subjects end

	}// Section 3 end

	/*echo '<pre>';
	print_r($grades);
	exit;*/
	$section4 = '4';
	if($section4 == '4')
	{
		// 2 for term 1 ,2 for term 2 (marks obtained and grade) and 1 for subject coulmn
		$col_count = sizeof($exam_codes_arr1)+5;
		if($grades)
		{
			for($i=0;$i<count($grades);$i=$i+$col_count)
			{

				$lastcol_index = $i+$col_count;
				$html .= '<tr style="height: 25.9333344px">';
				$f = 0;
				for($j=$lastcol_index-$col_count; $j < $lastcol_index; $j++)
				{
					$style = '';
					$styled = '';
					if($f == 0)
					{
						$style = ' style="text-align:left;"';
						$styled = ' style="font-size:14px;word-break: break-all !important;"';
					}
					else
					{
						//$styled = ' style="font-size:17px !important;"';
					}

					$dispaly_secured_mark = $grades[$j];

					$html .= '<td class="data_top1_white" '.$style.'>
					<p class="data_top2"><span class="data_top3" '.$styled.'>'.$dispaly_secured_mark.'</span></p>
					</td>';

					$f++;
				}
				$html .= '</tr>';
			}
		}
		$html .='</table>';
	}

	if($max_mark_total > 0)
	{
		$secured_mark_total =number_format($secured_mark_total,2,'.','');
		$percentage = ($secured_mark_total / $max_mark_total)*100;
		$percentage = number_format($percentage, 1, '.', '');
		$overall_grade = getGrade($percentage,100);
		$student_grand_total = $secured_mark_total.' / '.$max_mark_total;
		$student_overall_grade = $overall_grade;
		$student_percentage = $percentage;
		//$student_rank = $stu_secured_rank[$section_code][$secured_mark_total];
		$grand_total = $secured_mark_total.' / '.$max_mark_total;
		$percentage_secured = $percentage;

		//TERM 1
		$term1_secured_total = number_format($term1_sum_obtained_weightage,2,'.','');
		$term1_percentage = ($term1_sum_obtained_weightage / $max_mark_total)*100;
		$term1_percentage = number_format($term1_percentage, 1, '.', '');
		$term1_overalgrade = getGrade($term1_percentage,100);	;
		$term1_grand_total = $term1_secured_total.' / '.$max_mark_total;
		$term1_percentage_secured = $term1_percentage."%";

		//TERM 2
		$term2_secured_total = number_format($term2_sum_obtained_weightage,2,'.','');
		$term2_percentage = ($term2_sum_obtained_weightage / $max_mark_total)*100;
		$term2_percentage = number_format($term2_percentage, 1, '.', '');
		$term2_overalgrade = getGrade($term2_percentage,100);	;
		$term2_grand_total = $term2_secured_total.' / '.$max_mark_total;
		$term2_percentage_secured = $term2_percentage;

	}
	else
	{
		$secured_mark_total = '';
		$max_mark_total = '';
		$percentage = '';
		$overall_grade = '';
		$student_grand_total = '';
		$student_overall_grade = '';
		$student_percentage = '';
		$student_rank = '';
		$grand_total = '';
		$percentage_secured = '';

		// TERM 1
		$term1_secured_total = '';
		$term1_percentage = '';
		$term1_percentage = '';
		$term1_overalgrade ='';
		$term1_grand_total = '';
		$term1_percentage_secured = '';

		// TERM 2
		$term2_secured_total ='';
		$term2_percentage ='';
		$term2_percentage = '';
		$term2_overalgrade = '';
		$term2_grand_total = '';
		$term2_percentage_secured = '';
	}

	$section5 = '5'; // Grand Total Section
	if($section5 == '5')
	{
		$html .= '<table cellspacing="0" style="width:100%;border: 1px solid #0c3d03;margin:10px 0px 10px 0px;">
			<tr>
				<td  class="data_top1" colspan="2">
					<p class="data_top2"><b>TERM 1</b></p>
				</td>
				<td  class="data_top1" colspan="2">
					<p class="data_top2"><b>TERM 2</b></p>
				</td>
			</tr>
			<tr>
				<td class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:20px !important;" >GRAND TOTAL - '.$term1_grand_total.'</span></p>
				</td>
				<td  class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:20px !important;">PERCENTAGE - '.$term1_percentage_secured.'</span></p>
				</td>
				<td class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:20px !important;" >GRAND TOTAL - '.$term2_grand_total.'</span></p>
				</td>
				<td  class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:20px !important;">PERCENTAGE - '.$term2_percentage_secured.'%</span></p>
				</td>
			</tr>
			<tr>
				<td class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:20px !important;" >OVERALL GRADE - '.$term1_overalgrade.'</span></p>
				</td>
				<td  class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:20px !important;">ATTENDANCE - '.$term1_attendance .'</span></p>
				</td>
				<td class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:20px !important;" >OVERALL GRADE - '.$term2_overalgrade.'</span></p>
				</td>
				<td  class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:20px !important;">ATTENDANCE - '.$term2_attendance .'</span></p>
				</td>
			</tr>

			<!-- <tr>
				<td class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:20px !important;" >GRAND TOTAL - '.$grand_total.'</span></p>
				</td>
				<td  class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:20px !important;">PERCENTAGE - '.$percentage.'</span></p>
				</td>
			</tr>
			<tr>
				<td  class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:11px !important;">OVERALL GRADE - '.$overall_grade.'</span></p>
				</td>
				<td  class="data_item1" style="width:50%;text-align:left;padding:1% 0% 0% 1%;">
					<p  class="data_item2"><span class="data_item4" style="font-size:11px !important;">ATTENDANCE - '.$attendance.' / '.$total_class.'</span></p>
				</td>
			</tr> -->

		</table>';
	}

	$section5 = '6'; // Co-Scholastic
	if($section5 == '6')
	{
		$html .='<table cellspacing="0" cellpadding="1" style="width: 100%; border-collapse: collapse; border: 1px solid #0c3d03;" class="table_data">
		<tr style="height: 17px; border: 1px solid #0c3d03;">
			<td  class="data_top1"  style="width: 50%;">
				<p class="subject_heading2">
					<span class="data_top3" style="color: #800000;font-style: italic;">CO-SCHOLASTIC AREAS</span>
				</p>
			</td>
			<td  class="data_top1"  style="width: 25%;">
				<p class="data_top2">
					<span class="data_top3" >TERM - 1</span>
				</p>
			</td>
			<td  class="data_top1"  style="width: 25%;">
				<p class="data_top2">
					<span class="data_top3">TERM - 2</span>
				</p>
			</td>
		</tr>';
		$cosc1_mark = '';
		$cosc2_mark = '';
		foreach($examCoschSubjectDetails as $row1)
		{
			//Get Grade
			$exam_code = $row1->exam_code;
			$exam_subject_code = $row1->exam_subject_code;

			$key = $exam_code.'@'.$exam_subject_code.'@'.$student_code;
			if (array_key_exists($key,$examMarkGrade))
			{
				$cosc1_mark = $examMarkGrade[$key]->grade_secured;
			}

			$key = str_replace('COSC1','COSC2',$exam_code).'@'.$exam_subject_code.'@'.$student_code;
			if (array_key_exists($key,$examMarkGrade))
			{
				$cosc2_mark = $examMarkGrade[$key]->grade_secured;
			}

			$html.='<tr style="height: 17px">
						<td class="data_head_parent1" style="text-align:left;padding-left: 0.5%;">
							<p  class="data_item2"><span class="data_item4" style="font-size:11px !important;">'.$row1->exam_sub_name.'</span></p>
						</td>
						<td class="data_head_parent1" style="text-align:center;padding-left: 1%;">
							<p  class="data_item2"><span class="data_item4" style="font-size:11px text-align:center; !important;">'.$cosc1_mark.'</span></p>
						</td><td class="data_head_parent1" style="text-align:center;padding-left: 1%;">
							<p  class="data_item2"><span class="data_item4" style="font-size:11px  text-align:center; !important;">'.$cosc2_mark.'</span></p>
						</td>
					<tr>';
		}
		$html.='</table>';

		$html .='<div><table cellspacing="0" border= "1" cellpadding="5" style="width: 100%; border-collapse: collapse; border: 1px solid #0c3d03;text-align:left;" class="table_data">';
		$html .='	<tr>
						<td style="width: 74.5% !important;">
						<!-- <p  class="data_item2"><span class="data_item4"  style="font-size:12px !important;"><p style="color:#800000">CLASS TEACHER\'S REMARK</p>'.$remark.'</span></p> -->
						<span class="data_item4"  style="font-size:12px !important;"><span style="color:#800000">CLASS TEACHER\'S REMARK : </span>'.$remark.'</span>
						</td>
						<td style="width: 24.5% !important;">
							<!-- <p class="data_head_parent2"><span class="data_head_parent3">'.$remark.'</span></p> -->
							<!-- <p  class="data_item2"><span class="data_item4"  style="font-size:12px;"><p style="color:#800000">RESULT :</p>'.$stu_result.'</span></p> -->
							<span class="data_item4"  style="font-size:12px !important;"><span style="color:#800000">RESULT: </span>'.$stu_result.'</span>
						</td>
					</tr>';
		$html .='	</table>';

		if($institute_code == 'APSNOI')
		{
			$signature_image = '&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		else
		{
			$signature_image = 'S I G N A T U R E S';
		}
		$html .='<table cellspacing="0" style="margin-left:0.3pt;width: 100%; border-collapse: collapse;width:100%;" >
						<tr>
							<td class="student2" colspan="4" style="font-size:12px; text-align:left;">
								<span  class="data_item4"  style="font-size:11px !important;"><b>&nbsp;ISSUE DATE:</b> </span>
								<span class="data_item4"  style="font-size:11px !important;">'.$date_of_issue.'</span>
							</td>
						</tr>
						<tr>
							<!-- <td colspan="10" class="signature1" colspan="4" style="padding-left:10%;">
								<p  class="signature2" ><span  class="signature3" style="font-size:30px;"><img src="../images/sig.png"></img></span></p>
							</td> -->
							<td  class="signature1" colspan="4" style="padding-left:10%;">
								<p  class="signature2" ><span  class="signature3" style="font-size:24px;">'.$signature_image.'</span></p>
							</td>
						</tr>
						<tr>';
		if(count($signature_names) > 0)
			$width = 100/(count($signature_names));
		for($j=0; $j < count($signature_names); $j++)
		{
			$html.='<td class="student2" style="width:'.$width.'%;text-align:center;vertical-align:top;">
						<p style="text-align: right;">
							<span class="data_item4"  style="font-size:11px !important;">'.$signature_names[$j].'</span>
						</p>
					</td>';
		}
		$html .='</tr>
					</table>

					<span class="data_item4"  style="font-size:11px !important;">
						Note : <span color = "red" >*</span> Additional subjects , not included in calculation of final percentage.
					</span>
				</div>';
	}
	$html.= '</div></div>';

	//echo $html;exit;

	$mpdf->WriteHTML($html);
	//$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

	$pageCount++;
    if($pageCount < count($studentDetails))
    {
    	$mpdf->AddPage();
    }

    $student_code = '';


} // End student loop

//$mpdf->WriteHTML($html);

$mpdf->Output("test.pdf","I");exit;
