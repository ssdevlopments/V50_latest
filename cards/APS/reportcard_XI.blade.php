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

    $examMarkGrade = array();
    foreach ($results as $row)
    {
        $exam_code = $row->exam_code;
        $exam_subject_code = $row->exam_subject_code;
        $student_code = $row->student_code;
        $key = $exam_code.'@'.$exam_subject_code.'@'.$student_code;
        $examMarkGrade[$key] = $row;
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

//GET THE exam mapped with report card
// $query = "select A.exam_code,B.exam_name from k12.reportcard_exam_mapping A
//     INNER JOIN k12.exam_master B On A.exam_code = B.exam_code

//     where A.reportcard_code = 'HALFY_SCI_XI_2019-20_APSDEC'
//     AND A.exam_code != 'COSC1_SCI_XI_2019-20_APSDEC'
//     ORDER BY A.sequence_no";

$query = DB::table(DB::raw('k12.reportcard_exam_mapping as A'))
    ->join(DB::raw('k12.exam_master B'), function ($join) use($session_code) {
        $join->on(DB::raw('A.exam_code'), '=', DB::raw('B.exam_code'))
            ->where(DB::raw('B.session_code'), '=', $session_code);
    })
    ->select(DB::raw("A.exam_code,B.exam_name ")
    )
    ->where(DB::raw('A.reportcard_code'), 'HALFY_SCI_XI_2019-20_APSDEC')
    //->where(DB::raw('A.exam_code'), 'COSC1_SCI_XI_2019-20_APSDEC')
    ->where(DB::raw('A.institute_code'), $institute_code)
    ->where(DB::raw('A.session_code'), $session_code);

//echo('<pre>');    
$mapped_exam = $query->get()->toArray();

$sch_exam_mapped_array = array();
$co_sch_mapped_array = array();

foreach($mapped_exam AS $exams)
{

    $exam_code = $exams->exam_code;
    if($exam_code != 'COSC1_SCI_XI_2019-20_APSDEC')
    {
        $sch_exam_mapped_array[] = $exams;

    }
    else
    {
        $co_sch_mapped_array = $exams;
    }
    
}

//EXAM SUBJECT MAPPING QUERY TH PR
 

 $query = DB::table(DB::raw('k12.exam_subject_master as A'))
    ->join(DB::raw('k12.exam_subject_mapping B'), function ($join) use($session_code) {
        $join->on(DB::raw('A.exam_subject_code'), '=', DB::raw('B.exam_subject_code'))
            ->where(DB::raw('B.session_code'), '=', $session_code);
    })
    ->join(DB::raw('k12.reportcard_exam_mapping C'), function ($join) use($session_code) {
        $join->on(DB::raw('B.exam_code'), '=', DB::raw('C.exam_code'))
             ->where(DB::raw('C.reportcard_code'), '=', 'HALFY_SCI_XI_2019-20_APSDEC')
            ->where(DB::raw('C.session_code'), '=', $session_code);
    })
    ->join(DB::raw('k12.exam_master D'), function ($join) use($session_code) {
        $join->on(DB::raw('B.exam_code'), '=', DB::raw('D.exam_code'))
            ->where(DB::raw('D.session_code'), '=', $session_code);
    })
    ->join(DB::raw('k12.exam_type_master t'), function ($join) use($session_code) {
        $join->on(DB::raw('t.exam_type_code'), '=', DB::raw('D.exam_type_code'))
            ->where(DB::raw('t.session_code'), '=', $session_code);
    })
    
    ->select(DB::raw("B.exam_code,exam_name,A.exam_subject_code,subject_code,exam_sub_group,exam_sub_name ")
    )
    ->where(DB::raw("split_part(t.exam_type_code, '_', 1)"),"=", DB::raw("'SCH'"))
    ->where(DB::raw('A.course_code'), 'SCI_2019-20_APSDEC')
    ->where(DB::raw('A.class_code'), 'XI_2019-20_APSDEC')
    ->where(DB::raw('A.institute_code'), $institute_code)
    ->where(DB::raw('A.session_code'), $session_code);
 $mapped_exam_thpr = $query->get()->toArray(); 
 
$exam_mapped_array = array();
$exam_mapped_THPR_array = array();
$subjectWise_THPR_array = array();
foreach($mapped_exam_thpr AS $mapped_data)
{

    //  echo('<pre>');
    //  print_r($mapped_data);
    //  die();
    $exam_name = $mapped_data->exam_name;
    $exam_code = $mapped_data->exam_code;
    $exam_sub_name =$mapped_data->exam_sub_name;
    $subject_code = $mapped_data->subject_code;
    $exam_subject_code = $mapped_data->exam_subject_code;


    $exam_mapped_array[$exam_code] = $exam_name;

    if($exam_sub_name == 'THEORY' || $exam_sub_name == 'PRACTICAL' )
    {
        $exam_mapped_THPR_array[$exam_code][$exam_sub_name] = $exam_sub_name;

        $key = $exam_code.'@'.$subject_code.'@'.$exam_sub_name;
        $value = $exam_code.'@'.$exam_subject_code;
        $subjectWise_THPR_array[$key][] = $value;

    }
   
}

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

 $examMarkGrade = array();
 foreach ($results as $row)
 {
     $exam_code = $row->exam_code;
     $exam_subject_code = $row->exam_subject_code;
     $student_code = $row->student_code;
     $key = $exam_code.'@'.$exam_subject_code.'@'.$student_code;
     $examMarkGrade[$key] = $row;
 }




 //echo('<pre>');
 //print_r($subjectWise_THPR_array);
 //print_r($exam_mapped_THPR_array);
 //die();
// print_r($exam_mapped_array);
//print_r($exam_mapped_THPR_array);
 //die();

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






$style = '<link rel="stylesheet" media="print" href="'.url('/').'/resources/views/school/cards/APS/reportcard_styles_v.css" />';

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
	//'format' => 'A4'.($orientation == 'P' ? '-P' : ''),
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

     $student_subject = DB::SELECT("select student_code,A.subject_code,B.subject_name,A.optional_no
                from k12.ac_student_subjects A
                inner join k12.ac_subject_master B 
                ON A.subject_code = B.subject_code
                
                where A.student_code = '1805662019-20_APSDEC'
                AND A.subject_Code IN ('041_2019-20_APSDEC','048_2019-20_APSDEC','045_2019-20_APSDEC','043_2019-20_APSDEC',
                                    '101_2019-20_APSDEC','042_2019-20_APSDEC')");

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
    //header section
    $section = 2;
    if($section == 2)
    {


        $html .= '<table cellspacing="0" style="width:100%;border: 1px solid #0c3d03;">
                        <tr class="subject_heading1">
                            <td class="subject_heading2" style="color: #800000;font-style: italic;">
                                SCHOLASTIC AREAS
                            </td>
                        </tr>
                    </table>';
                    $html .= '<table cellspacing="0" style="width:100%;border: 1px solid #0c3d03;">
                        <tr class="subject_heading1">
                            <td class="data_top1" rowspan="2" >
                                <p class="data_top2">
                                    <span class="data_top3" >SUBJECT</span>
                                </p>
                            </td>';
                            //foreach($exam_mapped_array As $exam_mapped)
                            foreach ($exam_mapped_array as $key => $value)
                            {
                               // $exam_mapped_THPR_array
                                //    echo('<pre>');
                                 //    print_r($exam_mapped_THPR_array[$key]);
                               
                               $colspan = 1; 
                               if(array_key_exists('PRACTICAL',$exam_mapped_THPR_array[$key]))
                               {
                                   $colspan = 3;
                               }
                               $html.='<td class="data_top1" colspan="'.$colspan.'">
                                    <p class="data_top2">
                                        <span class="data_top3">'.$value.'</span>
                                    </p>
                                </td>';

                            }
                            
                            $html.='<td class="data_top1" rowspan="2">
                            <p class="data_top2">
                                <span class="data_top3" >GRAND </br>TOTAL</span>
                             </p>
                         </td>';
                            $html.='<td class="data_top1" rowspan="2">
                                <p class="data_top2">
                                    <span class="data_top3">THEORY </br>TOTAL</span>
                                </p>
                            </td>'; 
                            $html.='<td class="data_top1" rowspan="2">
                                <p class="data_top2">
                                    <span class="data_top3">%
                                    OF GRAND
                                    TOTAL</span>
                                </p>
                            </td>';  

                           $html .= ' </tr>';
                     $html .= ' <tr>';
                     foreach($exam_mapped_THPR_array AS $exam_type_arr)
                    // foreach ($exam_mapped_array as $key => $value)
                     {
                        $array_lemgth = sizeof($exam_type_arr);
                       // echo($array_lemgth);die();

                         foreach($exam_type_arr AS $exam_type)
                         {
                            //$html.='<td >'.$exam_type.'</td>';

                            if($exam_type == "THEORY" || $exam_type == "PRACTICAL"  )
                            {
                                $html.='<td class="data_top1">
                                            <p class="data_top2">
                                                <span class="data_top3">'.$exam_type.'</span>
                                             </p>
                                         </td>';

                             }
                         }
                         
                         if($array_lemgth == 2)
                         {
                            $html.='<td class="data_top1">
                            <p class="data_top2">
                                <span class="data_top3">TOTAL</span>
                             </p>
                         </td>';
                         } 
                     } 
                        
                     $html .= ' </tr>';
                     
                     foreach($student_subject AS $subject)
                     {
                        //   echo('<pre>');
                        //   print_r($subject);
                        //   die();
                        $subject_name = $subject->subject_name;
                        $subject_code = $subject->subject_code;

                        $html.="<tr border='1'>";
                        $html .='<td class="data_top11" >
										<p class="data_top2">
											<span class="data_top3">'.$subject_name.'</span>
										</p>
                                    </td>';
                                    
                                    //foreach($exam_mapped_THPR_array AS $exam_type_arr)
                                     foreach ($exam_mapped_THPR_array as $key => $value)
                                     {

                                       // echo($key);die();
                                        $temp_exam_code =  $key;
                                        $array_lemgth = sizeof($exam_mapped_THPR_array[$key]);
                                       //echo($array_lemgth);die();
                                        foreach($exam_type_arr AS $exam_type)
                                         {
                                            //$html.='<td >'.$exam_type.'</td>';
                
                                            if($exam_type == "THEORY" || $exam_type == "PRACTICAL"  )
                                            {
                                                //echo('<pre>');
                                                $temp_key = $temp_exam_code.'@'.$subject_code.'@'.$exam_type;

                                                
                                                if(array_key_exists($temp_key,$subjectWise_THPR_array))
                                                {
                                                     $value = $subjectWise_THPR_array[$temp_key][0];

                                                }
                                                else{
                                                    $value = 'xx';
                                                }

                                                // print_r($subjectWise_THPR_array[$temp_exam_code.'@'.$subject_code.'@'.$exam_type]);
                                                // die();

                                                if( $value != 'xx')
                                                {

                                                    $key = $value.'@'.$student_code;

                                                    
                                                    if(array_key_exists($key,$examMarkGrade))
                                                    {

                                                       
                                                        $temp_marks_obtained = $examMarkGrade[$key]->mark_secured;
                                                   
                                                    }
                                                    else
                                                    {
                                                        $temp_marks_obtained = 'NE';
                                                   
                                                    }
                                                    $html.='<td class="data_top1">
                                                            <p class="data_top2">
                                                                <span class="data_top3">'.$temp_marks_obtained.'</span>
                                                             </p>
                                                         </td>';

                                                }
                                                
                
                                             }
                                         }
                                         
                                         if($array_lemgth == 2)
                                         {
                                            $html.='<td class="data_top1">
                                            <p class="data_top2">
                                                <span class="data_top3">TOTAL</span>
                                             </p>
                                         </td>';
                                         } 
                                     }             
                         $html.='</tr>';

                     }
                     
                    $html .= '</table>';
    }
    $section = 6;
    if($section == '6')
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
					<span class="data_top3" >TERM - 1x</span>
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

			$html.='<tr style="height: 17px">
						<td class="data_head_parent1" style="text-align:left;padding-left: 0.5%;">
							<p  class="data_item2"><span class="data_item4" style="font-size:11px !important;">'.$row1->exam_sub_name.'</span></p>
						</td>
						<td class="data_head_parent1" style="text-align:center;padding-left: 1%;">
							<p  class="data_item2"><span class="data_item4" style="font-size:11px text-align:center; !important;">'.$cosc1_mark.'</span></p>
						</td>
					<tr>';
		}
		$html.='</table>';

		$html .='<div><table cellspacing="0" border= "1" cellpadding="5" style="width: 100%; border-collapse: collapse; border: 1px solid #0c3d03;text-align:left;" class="table_data">';
		$html .='	<tr>
						<td style="width: 74.5% !important;">
						    <span class="data_item4"  style="font-size:12px !important;"><span style="color:#800000">CLASS TEACHER\'S REMARK : </span>'.$remark.'</span>
						</td>
						<td style="width: 24.5% !important;">
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
    
    
    $html .='</div></div>';
    
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
