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

//Institute Details
$institute_result = DB::table(DB::raw('institute.institute_master'))
    ->select(DB::raw("institute_name,contact_number,
    location,logo_url,affiliation_no"))
->where(DB::raw('institute_code'), $institute_code)
->get()->toArray();

$institute_name = $institute_result[0]->institute_name;
$contact_number = $institute_result[0]->contact_number;
$address = $institute_result[0]->location ;
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
echo('<pre>');
print_r($subjectgroup_array);
print_r($exam_subject_group_examsub_mapped);
die();

die();
?>
