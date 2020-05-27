<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Yadakhov\InsertOnDuplicateKey;

use Illuminate\Http\Request;
use DB;
use Session;


class StudentsController extends Controller
{
	public $institute_code;
	public $session_code;
	
	public function __construct()
	{
		$this->middleware(function ($request, $next) {
			
			// fetch session and use it in entire class with constructor
			$this->institute_code = Session::get('institute_code');
			$this->session_code = Session::get('session_code');
			$this->user_code = Session::get('user_code');
			
			return $next($request);
		});
    }
	
	/* 	@ Students Listing page
		@ Route : /students
		@ Page  : students.blade
		@ METHOD : GET*/
	public function getStudentsPage()
	{
		return view('school.students');
	}
	
	/* 	@ Students Listing page
		@ Route : /students/api/SELECT_ALL_STUDENTS
		@ Page  : students.blade
		@ METHOD : GET*/
	public function selectAllStudents(Request $request)
	{
		$course_code = $request->input('courseFilter');
		$class_code = $request->input('classFilter');
		$section_code = $request->input('sectionFilter');
		$admissionNoFilter = $request->input('admissionNoFilter');
		$statusFilter = $request->input('statusFilter');
		$activeInactive = $request->input('activeInactive');
		
		$limit = $request->input('iDisplayLength');
		$start = $request->input('iDisplayStart');
		$search = strtoupper($request->input('sSearch'));
		
		$output = array("aaData" => array());
		
		$query = DB::table(DB::raw('k12.ac_student_master as A'))
			
			->join(DB::raw('k12.ac_course_master CO'), function ($join) {
				$join->on(DB::raw('CO.course_code'), '=', DB::raw('A.course_code'))
				->where(DB::raw('CO.session_code'), '=', $this->session_code);
			})
			->join(DB::raw('k12.ac_class_master CL'), function ($join) {
				$join->on(DB::raw('CL.class_code'), '=', DB::raw('A.class_code'))
				->where(DB::raw('CL.session_code'), '=', $this->session_code);
			})
			->leftjoin(DB::raw('k12.ac_section_master SEC'), function ($join) {
				$join->on(DB::raw('SEC.section_code'), '=', DB::raw('A.section_code'))
				->where(DB::raw('SEC.session_code'), '=', $this->session_code);
			})
			->leftjoin(DB::raw('admin.user_master U'), function ($join) {
				$join->on(DB::raw('U.user_code'), '=', DB::raw('A.stu_usercode'))
				->where(DB::raw('U.institute_code'), '=', $this->institute_code);
			})
			->select(DB::raw("U.profile_image_url,A.admission_no,student_name,CO.course_name,CL.class_name,
					CASE WHEN (SEC.section_name = '' or SEC.section_name IS NULL) and (A.roll_no IS NULL or A.roll_no = '') then '' 
					  WHEN (SEC.section_name = '' or SEC.section_name IS NULL) and (A.roll_no IS NOT NULL or A.roll_no <> '') then A.roll_no  
					  WHEN (SEC.section_name <> '' or SEC.section_name IS NOT NULL) and (A.roll_no IS NULL or A.roll_no = '') then SEC.section_name  
					ELSE concat(SEC.section_name, ' (',A.roll_no,')') END as section_name,
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
		
		->where(DB::raw('A.institute_code'), $this->institute_code)
		->where(DB::raw('A.session_code'), $this->session_code);

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
		if($admissionNoFilter != "")
		{
			$query->where(DB::raw('A.admission_no'),$admissionNoFilter);
		}		
		if($statusFilter != "")
		{
			$query->where(DB::raw('A.is_exit'),$statusFilter);
		}
		if($search != "")
		{
			$query->where('course_name', 'ILIKE', '%'.$search.'%');
			$query->orWhere('class_name', 'ILIKE', '%'.$search.'%');
			$query->orWhere('student_name', 'ILIKE', '%'.$search.'%');
			$query->orWhere('admission_no', 'ILIKE', '%'.$search.'%');
			$query->orWhere('fathers_name', 'ILIKE', '%'.$search.'%');
			$query->orWhere('section_name', 'ILIKE', '%'.$search.'%');
		}
		
		// Total
    	$results_total = $query->get()->toArray();
		$totals = count($results_total);
		
		$query->offset($start);
        $query->limit($limit);
        $query->orderBy(DB::raw('CO.sl_no, CL.sl_no'));
		$results = $query->get()->toArray();
		
		$slno = $start + 1;
		$index = 0;
		foreach($results as $row)
		{
			$obj = array($index, $slno);
			$extended = array_merge((array)$obj, (array)$row);
			
			$output['aaData'][] = array_values($extended);
			$slno ++;
			$index++;
		}
		
		$output['recordsTotal'] = $totals;
		$output['recordsFiltered'] = $totals;
		
		return response()->json($output);
	}
	
	
	public function stu_certificatePage()
	{
		return view('school.studentsCertificate');
	}
	
	public function UPLOAD_DOCUMENT(Request $request)
	{
		$documentsReq = array();
		$documentsDesc = array();
		$institute_code = 'OAVHIN';
		$session_code = '2019-20_OAVHIN';
		$admissionnumber = trim($request->admissionnumber);	
		$student_code = $admissionnumber.'_'.$this->session_code;
		$hdStudentCode = $student_code;
		
		$student_id = explode("_",substr($hdStudentCode,0,(strlen($hdStudentCode)-14)));
		$user_code = $student_id[0]."_".$institute_code;
		
		$uploaddir = $session_code."/".$user_code;
		//$uploaddir = $document_upload_url."/".$session_code."/".$user_code;
		$retrievedir = $session_code."/".$user_code;
		//$retrievedir = $base_adm_url."/".$session_code."/".$user_code;
		if(!is_dir($uploaddir))
			mkdir($uploaddir,0777,true);
		$now = date("Y-m-d H:i:s");
		$i=0;
		
		$entity_type_code = 'STUDENT';
		$result = DB::table(DB::raw('k12.ac_institute_document_setup as ac_institute_document_setup'))
			->join(DB::raw('k12.document_type_master AS CL'), DB::raw('ac_institute_document_setup.document_type_code'), '=', DB::raw('CL.document_type_code'))
			->select(DB::raw("ac_institute_document_setup.document_type_code,document_type,document_type_description,
							document_size_description,document_size_in_kb,
							document_preview_height,document_preview_width"))
    		->where('entity_type_code',$entity_type_code)
	        ->where('ac_institute_document_setup.record_status','1')
	        ->where('ac_institute_document_setup.institute_code',$institute_code)
	        ->orderBy('ac_institute_document_setup.sl_no')
    		//->limit(5)
    		->get()->toArray(); 
	    $myArray = json_decode(json_encode($result), true); 
		foreach($myArray as $key=>$row)
		{
			$documentsReq[] = $row;
			$documentsDesc[] = $row['document_type'];
		}	
		foreach($documentsReq as $row)
	  	{
	  		if(!empty($_FILES['fileDocument']['tmp_name'][$i]))
	  		{
	  			$document_type_code = $row['document_type_code'];
				$doc_name= explode(".",$_FILES['fileDocument']["name"][$i]);
				$ext_doc = strtolower(end($doc_name));
				
				if(substr_count($_FILES['fileDocument']['name'][$i],".")==1 
				&& (mime_content_type($_FILES['fileDocument']['tmp_name'][$i]) == 'application/pdf' 
				|| mime_content_type($_FILES['fileDocument']['tmp_name'][$i]) == 'application/msword' 
				|| mime_content_type($_FILES['fileDocument']['tmp_name'][$i]) == 'image/jpeg' 
				|| mime_content_type($_FILES['fileDocument']['tmp_name'][$i]) == 'image/png' 
				) 
				&& substr_count($_FILES['fileDocument']['name'][$i],"%0")==0 )
				{
					$is_valid_document = true;
		        }else{
		            $is_valid_document = false;
		        }
				
				if($is_valid_document == false)
				{
					echo 'Upload: Failed to move uploaded file.';
					exit;
				}
				 
				if( $ext_doc != 'png' && $ext_doc != 'jpeg' && $ext_doc != 'jpg')
				{
					echo 'Upload Error: Invalid file format.';exit;
				}
				
				$docImageFileName = $document_type_code.'.'.$ext_doc;
				$docImagePath = $retrievedir."/".$docImageFileName;
				$docimagetemp = $_FILES['fileDocument']['tmp_name'][$i];
				move_uploaded_file($docimagetemp,$uploaddir."/".$docImageFileName);
				$doc_id = $user_code.'_'.$document_type_code;
				
				$data = array("user_code"=>$user_code,
					"doc_id"=>$doc_id,
					"document_type"=>$document_type_code,
					"document_submit_status"=>'Submitted',
					"document_submit_date"=>$now,
					"submit_mode"=>$now
                 );
        		$result = DB::table('k12.ac_user_documents')->insert($data);
				
				if($document_type_code == "PHO")
				{
					$data = array("image_file_name"=>$docImagePath);
					$result = DB::table('admin.user_master')
								->where('user_code',$user_code)
								->update($data);
					/*$uquery2 = "UPDATE user_master SET 
						image_file_name = '$docImagePath'
						WHERE user_code = '$user_code'";
					mysqli_query($con, $uquery2);*/
				}
				
			}
			$i++;
		}
		echo "Success";
			
	}
	
	
	//check roll no duplicate
	public function CHKDUPLICATE(Request $request)
	{
		$admissionnumber = $request->input('admissionnumber');
		$result = DB::table('k12.ac_student_master')
			        ->select('admission_no')
			        ->where('admission_no',$admissionnumber)
			        ->where('session_code',$this->session_code)
			        ->where('record_status','1')
			        ->get()->toArray();
			        
		$myArray = json_decode(json_encode($result), true);
		if(count($myArray)>0)
		{
			foreach($myArray as $key=>$row)
			{
				$admno = $row['admission_no'];
			}	
		}
		else
		{
			$admno = '0';
		}
		echo $admno;
		
	}
	
	public function GET_PROFILE_INFO(Request $request)
	{
		$admission_no = $request->input('admission_no');
		$result = DB::table(DB::raw('k12.ac_student_master as A'))
			
			->join(DB::raw('admin.state_master AS st'), DB::raw('A.state_code'), '=', DB::raw('st.state_code'))
			->join(DB::raw('k12.city_master AS cm'), DB::raw('A.city_code'), '=', DB::raw('cm.city_code'))
			->join(DB::raw('k12.ac_course_master AS CO'), DB::raw('A.course_code'), '=', DB::raw('CO.course_code'))
			->join(DB::raw('k12.ac_class_master AS CL'), DB::raw('A.class_code'), '=', DB::raw('CL.class_code'))
			->leftJoin(DB::raw('k12.ac_section_master AS SEC'), DB::raw('A.section_code'), '=', DB::raw('SEC.section_code'))
			
			->select(DB::raw("A.admission_no,student_name,CO.course_name,CL.class_name,
			CASE WHEN (SEC.section_name = '' or SEC.section_name IS NULL) and (A.roll_no IS NULL or A.roll_no = '') then '' 
					  WHEN (SEC.section_name = '' or SEC.section_name IS NULL) and (A.roll_no IS NOT NULL or A.roll_no <> '') then A.roll_no  
					  WHEN (SEC.section_name <> '' or SEC.section_name IS NOT NULL) and (A.roll_no IS NULL or A.roll_no = '') then SEC.section_name  
					ELSE concat(SEC.section_name, ' (',A.roll_no,')') END as section_name,
					fathers_name,register_mobile_no,A.gender,A.student_code,A.course_code,A.class_code,A.section_code,A.is_exit,
					TO_CHAR(A.birth_date::date,'DD-MM-YYYY') AS birth_date,
					TO_CHAR(A.admission_date::date,'DD-MM-YYYY') AS admission_date,
					roll_no,mothers_name,
					TO_CHAR(A.enrollment_date::date,'DD-MM-YYYY') AS enrollment_date,
					alternate_contact_no,A.blood_group,A.category_1,A.category_2,A.admission_type,
					A.cbse_regd_no,A.house_code,A.service_category, A.email_id,
					A.address,A.permanent_address,st.state_name,cm.city_name
					")
			)
			
			
    		->where(DB::raw('A.institute_code'), $this->institute_code)
    		->where(DB::raw('A.session_code'), $this->session_code)
    		->where(DB::raw('A.admission_no'),$admission_no)
    		->get()->toArray();	        
		$myArray = json_decode(json_encode($result), true);
		$output = array("aaData" => array());
		foreach($myArray as $key=>$row)
		{
			$output['aaData'][] = $row;
		}
		echo json_encode($output);	        
	}			    
	
	public function GET_LAST_ADMISSION_NO(Request $request)
	{
		$result = DB::table('k12.ac_student_master')
			        ->select('admission_no')
			        ->where('institute_code',$this->institute_code)
			        ->where('session_code',$this->session_code)
			        ->orderBy('student_id', 'desc')
			        ->limit(1)
			        ->get()->toArray();
		$myArray = json_decode(json_encode($result), true);
		$output = array("aaData" => array());
		foreach($myArray as $key=>$row)
		{
			$output['aaData'][] = $row;
		}
		echo json_encode($output);	        
	}			    
	
	public function SELECT_SIBLINGS(Request $request)
	{
		$admissionnumber = $request->input('admissionnumber');
		$result = DB::table('k12.ac_student_master')
			        ->select('admission_no','student_code','student_name')
			        ->where('institute_code',$this->institute_code)
			        ->where('session_code',$this->session_code)
			        ->where(DB::raw('admission_no'),'<>',$admissionnumber)
			        ->get()->toArray();
		return response()->json($result);
	}
	
	public function INSERT_SIBLING(Request $request)
	{
		$student_code = $request->input('student_code');
		$sibling_student_admno_arr = $request->input('sibling_student_code');
		unset($sibling_student_admno_arr[0]);
		$arrlength = count($sibling_student_admno_arr);
		
		$output = array("aaData" => array());
		
		for($x = 1; $x <= $arrlength; $x++) 
		{
			$sibling_student_code=''; 
			$arr_sibling_student_codes = array();
			$str_sibling_student_codes = '';
			$sibling_student_admno=$sibling_student_admno_arr[$x];
			
		    $result = DB::table('k12.ac_student_master')
			        ->select('student_code')
			        ->where('institute_code',$this->institute_code)
			        ->where('session_code',$this->session_code)
			        ->where('admission_no',$sibling_student_admno)
			        ->where('record_status','1')
			        ->get()->toArray();
			$myArray = json_decode(json_encode($result), true);
			    
			if($result)
			{
				foreach($myArray as $key=>$row)
				{
				
					$sibling_student_code = $row['student_code'];
				}
			}
			
			$result = DB::table('k12.student_siblings')
			        ->select('id','sibling_student_codes')
			        ->where('institute_code',$this->institute_code)
			        ->where('session_code',$this->session_code)
			        ->where('sibling_student_codes', 'ILIKE', '%'.$student_code.'%')
			        ->where('sibling_student_codes', 'ILIKE', '%'.$sibling_student_code.'%')
			        ->where('record_status','1')
			        ->get()->toArray();
			$myArray = json_decode(json_encode($result), true);  
			      
			if($result)
			{
				
				foreach($myArray as $key=>$row)
				{
					if($row['sibling_student_codes'] != '') //APPEND
					{
						$id = $row['id'];
						$str_sibling_student_codes = $row['sibling_student_codes'];
						$arr_sibling_student_codes = explode(",",$str_sibling_student_codes);
						if(!in_array($sibling_student_code,$arr_sibling_student_codes))
						{
							array_push($arr_sibling_student_codes, $sibling_student_code);
							$str_sibling_student_codes = implode(",",$arr_sibling_student_codes);
							$data = array("sibling_student_codes"=>$str_sibling_student_codes,
									"updated_by"=>'',
									"updated_on"=>'NOW()'
					                 );
					        $result = DB::table('k12.student_siblings')
								       ->where('institute_code',$this->institute_code)
						        	   ->where('session_code',$this->session_code)
								       ->where('id',$id)
								->update($data);
							if($result)
							{
								$dbStatus = 'SUCCESS';
								$dbMessage = 'Saved Successfully';
							}	
							else 
							{
								$dbStatus = 'FAILURE';
								$dbMessage = 'Error while saving';
							}
						}
						else if(!in_array($student_code,$arr_sibling_student_codes))
						{
							array_push($arr_sibling_student_codes, $student_code);
							$str_sibling_student_codes = implode(",",$arr_sibling_student_codes);
							$data = array("sibling_student_codes"=>$str_sibling_student_codes,
									"updated_by"=>'',
									"updated_on"=>'NOW()'
					                 );
					        $result = DB::table('k12.student_siblings')
								       ->where('institute_code',$this->institute_code)
						        	   ->where('session_code',$this->session_code)
								       ->where('id',$id)
								->update($data);
							if($result)
							{
								
								$dbStatus = 'SUCCESS';
								$dbMessage = 'Saved Successfully';
							}	
							else 
							{
								$dbStatus = 'FAILURE';
								$dbMessage = 'Error while saving';
							}
						}
						else
						{
							$dbStatus = 'SUCCESS';
							$dbMessage = 'Saved Successfully';	
						}
					}
				}
				
			} 
			else
			{
				$arr_sibling_student_codes[0] = $student_code;
				$arr_sibling_student_codes[1] = $sibling_student_code;
				$str_sibling_student_codes = implode(",",$arr_sibling_student_codes);
				$data = array("sibling_student_codes"=>$str_sibling_student_codes,
							"created_by"=>'',"institute_code"=>$this->institute_code,
							"session_code"=>$this->session_code,
							"created_on"=>'NOW()'
			                 );
		        $result = DB::table('k12.student_siblings')->insert($data); 	   
				
				if($result)
				{
					
					$dbStatus = 'SUCCESS';
					$dbMessage = 'Saved Successfully';
				}	
				else 
				{
					$dbStatus = 'FAILURE';
					$dbMessage = 'Error while saving';
				}
			}
		}
		$output = array("dbStatus"=>$dbStatus,"dbMessage"=>$dbMessage);
		echo json_encode($output);
	}
	
	public function GET_SIBLINGS(Request $request)
	{
		$student_code = $request->input('student_code');
		$output = array("aaData" => array());
		$result = DB::table('k12.student_siblings')
			        ->select('id','sibling_student_codes')
			        ->where('institute_code',$this->institute_code)
			        ->where('session_code',$this->session_code)
			        ->where('sibling_student_codes', 'ILIKE', '%'.$student_code.'%')
			        ->where('record_status','1')
			        ->get()->toArray();
		$myArray = json_decode(json_encode($result), true); 
		$slno =  1;
		$index = 1;
		$totals=0;
		foreach($myArray as $key=>$aRow)
		{
			$student_siblingsid = $aRow['id'];
			
			if($aRow['sibling_student_codes'] != '')
			{
				$str_sibling_student_codes = $aRow['sibling_student_codes'];
				$arr_sibling_student_codes = explode(",",$str_sibling_student_codes);
				
				if(count($arr_sibling_student_codes) > 0)
					$str_sibling_student_codes = $arr_sibling_student_codes;
				else
					$str_sibling_student_codes = '';
					
				$result = DB::table(DB::raw('k12.ac_student_master as f'))
						->join(DB::raw('k12.ac_course_master AS C'), function ($join) {
							$join->on(DB::raw('f.course_code'), '=', DB::raw('C.course_code'))
							->where(DB::raw('f.institute_code'), '=', DB::raw('C.institute_code'))
							->where(DB::raw('f.session_code'), '=', DB::raw('C.session_code'));
							})
						->join(DB::raw('k12.ac_class_master AS D'), function ($join) {
							$join->on(DB::raw('f.class_code'), '=', DB::raw('D.class_code'))
							->where(DB::raw('C.institute_code'), '=', DB::raw('D.institute_code'))
							->where(DB::raw('C.session_code'), '=', DB::raw('D.session_code'));
							})
						->leftJoin(DB::raw('k12.ac_section_master AS G'), function ($join) {
							$join->on(DB::raw('f.section_code'), '=', DB::raw('G.section_code'))
							->where(DB::raw('f.institute_code'), '=', DB::raw('G.institute_code'))
							->where(DB::raw('f.session_code'), '=', DB::raw('G.session_code'));
							})		
						->select(DB::raw("student_name,
										C.course_name, D.class_name, G.section_name,
										CASE WHEN f.gender = 'M' THEN 'Brother' ELSE 'Sister' END AS relation,student_code"))
						
			    		->where(DB::raw('f.institute_code'), $this->institute_code)
			    		->where(DB::raw('f.session_code'), $this->session_code)
			    		->whereIn('f.student_code',$str_sibling_student_codes)
			    		->get()->toArray();	
			    foreach($result as $row)
				{
					if($row->student_code != $student_code)
					{
						$obj = array($index, $slno);
						$extended = array_merge((array)$obj, (array)$row);
						$output['aaData'][] = array_values($extended);
						$slno ++;
						$index++;
					}
					
				}
				$totals = $totals+count($result);		
				
			}
		}
		
		$output['recordsTotal'] = $totals;
		$output['recordsFiltered'] = $totals;
		
		return response()->json($output);
	}
	
	public function DELETE_SIBLING(Request $request)
	{
		$sibling_student = array();
		$output = array("aaData" => array());
		$student_code = $request->input('student_code');
		$institute_code=$this->institute_code;
		$session_code=$this->session_code;
		$result = DB::table('k12.student_siblings')
			        ->select('id','sibling_student_codes')
			        ->where('institute_code',$institute_code)
			        ->where('session_code',$session_code)
			        ->where('sibling_student_codes', 'ILIKE', '%'.$student_code.'%')
			        ->get()->toArray();
		foreach($result as $aRow)
		{
			$sibling_student_code = $aRow->sibling_student_codes;
			$id = $aRow->id;
		}	
		
		$sibling_student = explode(",",$sibling_student_code);
		
		if (($key = array_search($student_code, $sibling_student)) !== false) {
		    unset($sibling_student[$key]);
		}
		
		$str_sibling_student_codes = implode(",",$sibling_student);
		$data = array("sibling_student_codes"=>$str_sibling_student_codes,
									"updated_by"=>'',
									"updated_on"=>'NOW()'
					                 );
        $result = DB::table('k12.student_siblings')
			       ->where('institute_code',$institute_code)
	        	   ->where('session_code',$session_code)
			       ->where('id',$id)
			->update($data);
		if($result)
		{
			
			$dbStatus = 'SUCCESS';
			$dbMessage = 'Delete Successfully';
		}	
		else 
		{
			$dbStatus = 'FAILURE';
			$dbMessage = 'Error while saving';
		}
		$output = array("dbStatus"=>$dbStatus,"dbMessage"=>$dbMessage);
		echo json_encode($output);		        
	}
	
	public function SEARCHSTUDENT(Request $request)
	{
		$output = array("aaData" => array());
		$key = $request->input('q');
		if(is_numeric($key))
		{
			$query = DB::table(DB::raw('k12.ac_student_master as A'))
			->join(DB::raw('k12.ac_course_master AS B'), function ($join) {
				$join->on(DB::raw('A.course_code'), '=', DB::raw('B.course_code'))
				->where(DB::raw('A.institute_code'), '=', DB::raw('B.institute_code'))
				->where(DB::raw('A.session_code'), '=', DB::raw('B.session_code'));
				})
			->join(DB::raw('k12.ac_class_master AS C'), function ($join) {
				$join->on(DB::raw('A.class_code'), '=', DB::raw('C.class_code'))
				->where(DB::raw('A.institute_code'), '=', DB::raw('C.institute_code'))
				->where(DB::raw('A.session_code'), '=', DB::raw('C.session_code'));
				})	
			->select(DB::raw("admission_no, student_name, A.course_code, B.course_name, A.class_code, C.class_name, A.fathers_name, A.gender"))
			
    		->where(DB::raw('A.institute_code'), $this->institute_code)
    		->where(DB::raw('A.session_code'), $this->session_code)
    		->where('A.admission_no', 'ILIKE', '%'.$key.'%')
    		->get()->toArray();
		}
		else
		{
			$query = DB::table(DB::raw('k12.ac_student_master as A'))
			->join(DB::raw('k12.ac_course_master AS B'), function ($join) {
				$join->on(DB::raw('A.course_code'), '=', DB::raw('B.course_code'))
				->where(DB::raw('A.institute_code'), '=', DB::raw('B.institute_code'))
				->where(DB::raw('A.session_code'), '=', DB::raw('B.session_code'));
				})
			->join(DB::raw('k12.ac_class_master AS C'), function ($join) {
				$join->on(DB::raw('A.class_code'), '=', DB::raw('C.class_code'))
				->where(DB::raw('A.institute_code'), '=', DB::raw('C.institute_code'))
				->where(DB::raw('A.session_code'), '=', DB::raw('C.session_code'));
				})	
			->select(DB::raw("admission_no, student_name, A.course_code, B.course_name, A.class_code, C.class_name, A.fathers_name, A.gender"))
			
    		->where(DB::raw('A.institute_code'), $this->institute_code)
    		->where(DB::raw('A.session_code'), $this->session_code)
    		->orWhere('A.admission_no', 'ILIKE', '%'.$key.'%')
    		->get()->toArray();
		}
		$myArray = json_decode(json_encode($query), true); 
		foreach($myArray as $key=>$row)
		{
			$output['aaData'][] = $row;
		}
		echo json_encode($output);	
	}
	
	public function HOUSE_INFO(Request $request)
	{
		$results = DB::table(DB::raw('k12.ac_student_master as A'))
			->join(DB::raw('k12.ac_house_master AS B'), DB::raw('A.house_code'), '=', DB::raw('B.house_code'))
			->select(DB::raw("B.house_name, 
		        	COUNT(CASE WHEN gender = 'M' THEN student_code END) AS boys, 
					COUNT(CASE WHEN gender = 'F' THEN student_code END) AS girls 
					"))
    		->where(DB::raw('A.record_status'),'1')
    		->where(DB::raw('A.institute_code'), $this->institute_code)
    		->where(DB::raw('A.session_code'), $this->session_code)
    		->groupBy(DB::raw('A.house_code,B.house_name'))
    		->get()->toArray();
    	$output = array("aaData" => array());	
    	$start=0;
    	$slno = $start + 1;
		$index = 0;
		foreach($results as $row)
		{
			$obj = array($index, $slno);
			$extended = array_merge((array)$obj, (array)$row);
			
			$output['aaData'][] = array_values($extended);
			$slno ++;
			$index++;
		}	
		
		return response()->json($output);
	}
	
	//@save Student
	public function Delete_STUDENT(Request $request)
	{
		$admissionnumber = trim($request->admissionnumber);
		echo($admissionnumber);
	}
	
	public function SELECT_STATE(Request $request)
	{
		$output = array("aaData" => array());
		$result = DB::table('admin.state_master')
			        ->select('state_code','state_name')
			        ->where('status','1')
			        ->orderBy('state_name')
			        ->get()->toArray();
		return response()->json($result);
	}
	
	public function CITY_BY_STATE(Request $request)
	{
		$state_code = $request->input('state_code');
		$result = DB::table('k12.city_master')
			        ->select('city_code','city_name')
			        ->where('state_code','IN-'.$state_code)
			        ->orderBy('city_name')
			        ->get()->toArray();
		return response()->json($result);
	}
	
	public function ADD_CONTACT(Request $request)
	{
		$session_code = $this->session_code;
		$institute_code = $this->institute_code;	
		$admissionnumber = trim($request->admissionnumber);	
		$alternate_contact_no = trim($request->alternatemobileno);	
		$email_id = trim($request->email_id);	
		$address = trim($request->address);	
		$permanent_address = trim($request->permanent_address);	
		$state = trim($request->state);	
		$city = trim($request->city);
		$student_code = $admissionnumber.'_'.$this->session_code;	
		$data = array("alternate_contact_no"=>$alternate_contact_no,
				"email_id"=>$email_id,
				"address"=>$address,
				"permanent_address"=>$permanent_address,
				"city_code"=>$city,
				"state_code"=>$state
                 );
        $result = DB::table('k12.ac_student_master')
						->where('admission_no',$admissionnumber)
						->where('student_code',$student_code)
				        ->where('institute_code',$institute_code)
				        ->where('session_code',$session_code)
						->update($data);  
		if($result)
		{
            $output['dbStatus'] = 'SUCCESS';
            $output['dbMessage'] = 'Data Saved Successfully.';
        }
        else
        {
            $output['dbStatus'] = 'FAILURE';
            $output['dbMessage'] = 'OOPS! Someting Went Wrong on insert Operation.';
            exit();
        }
		return response()->json($output);       
	}
	
	public function addStudent(Request $request)
	{
		$admissionnumber = trim($request->admissionnumber);
		
		$admissionnumber = trim($request->admissionnumber);
		$studentname = trim($request->studentname);
		
		$gender = trim($request->gender);
		$bloodgroup = trim($request->cmbBloodgroup);
		$birthDate = trim($request->txtBirthDate);
		$fathersname = trim($request->fathersname);
		$mothersname = trim($request->mothersname);
		$regdmobileno = trim($request->regdmobileno);
		$course_code = trim($request->course_code);
		$class_code = trim($request->class_code);
		$section_code = trim($request->section_code);
		$roll_no = trim($request->roll_no);
		$category1 = trim($request->category1);
		$category2 = trim($request->category2);
		$admissionType = trim($request->admissionType);
		$admissiondate = trim($request->admissiondate);
		$registrationno = trim($request->registrationno);
		$house = trim($request->house);
		$serviceCategory = trim($request->serviceCategory);
		
		$enrollmentdate = trim($request->enrollmentdate);
		
		$birthDate = date("Y-m-d", strtotime($birthDate));
		$admissiondate = date("Y-m-d", strtotime($admissiondate));
		$enrollmentdate = date("Y-m-d", strtotime($enrollmentdate));
		$arr_adm_date = explode("-", $admissiondate);
		$admission_year = intval($arr_adm_date[2]);
		
		$session_code = $this->session_code;
		$institute_code = $this->institute_code;
		
		$student_code = date('dmyhis'.str_replace(".","",substr(microtime(), 1, 4))).'_'.$this->session_code;
		
		$result = DB::table('k12.ac_student_master')
			->select('student_name')
			->where('admission_no',$admissionnumber)
			->where('institute_code',$institute_code)
			->where('session_code',$session_code)
			->get()->toArray();
		
		if($result==NULL)
		{
			$data = array("student_code"=>$student_code,
				"admission_no"=>$admissionnumber,
				"student_name"=>strtoupper($studentname),
				"course_code"=>$course_code,
				"section_code"=>$section_code,
				"class_code"=>$class_code,
				"gender"=>$gender,
				"category_1" => $category1,
				"category_2" => $category2,
				"birth_date"=>$birthDate,
				"fathers_name"=>strtoupper($fathersname),
				"mothers_name"=>strtoupper($mothersname),
				"register_mobile_no"=>$regdmobileno,
				"alternate_contact_no"=>'',
				"email_id"=>'',
				"address"=>'',
				"admission_date"=>$admissiondate,
				"enrollment_date"=>$enrollmentdate,
				"institute_code"=>$institute_code,
				"session_code"=>$session_code,
				"created_by"=>'test',
				"created_on"=>'NOW()',
				"school_group_regd_no"=>'',
				"school_regd_no"=>'',
				"blood_group"=>$bloodgroup,
				"cbse_regd_no" =>$registrationno,
				"roll_no" =>$roll_no,
				"is_exit"=>0,
				"record_status" =>'1',
				"admission_type"=>$admissionType,
				"service_category"=>$serviceCategory,
				"house_code"=>$house
            );
			$result = DB::table('k12.ac_student_master')->insert($data);
		}
		else
		{
			$data = array("admission_no"=>$admissionnumber,
				"student_name"=>strtoupper($studentname),
				"course_code"=>$course_code,
				"section_code"=>$section_code,
				"class_code"=>$class_code,
				"gender"=>$gender,
				"category_1" => $category1,
				"category_2" => $category2,
				"birth_date"=>$birthDate,
				"fathers_name"=>strtoupper($fathersname),
				"mothers_name"=>strtoupper($mothersname),
				"register_mobile_no"=>$regdmobileno,
				"alternate_contact_no"=>'',
				"email_id"=>'',
				"address"=>'',
				"admission_date"=>$admissiondate,
				"enrollment_date"=>$enrollmentdate,
				"institute_code"=>$institute_code,
				"session_code"=>$session_code,
				"updated_by"=>'test',
				"updated_on"=>'NOW()',
				"blood_group"=>$bloodgroup,
				"cbse_regd_no" =>$registrationno,
				"roll_no" =>$roll_no,
				"is_exit"=>0,
				"record_status" =>'1',
				"admission_type"=>$admissionType,
				"service_category"=>$serviceCategory,
				"house_code"=>$house
            );
			$result = DB::table('k12.ac_student_master')
					->where('admission_no',$admissionnumber)
			        ->where('institute_code',$institute_code)
			        ->where('session_code',$session_code)
					->update($data);	
		}
		
		if($result)
		{
            $output['dbStatus'] = 'SUCCESS';
            $output['dbMessage'] = 'Data Saved Successfully.';
        }
        else
        {
            $output['dbStatus'] = 'FAILURE';
            $output['dbMessage'] = 'OOPS! Someting Went Wrong on insert Operation.';
            exit();
        }
		return response()->json($output);
	}
	
	//akash
	public function GET_ASSIGNED_SECTION_DETAILS(Request $request)
	{
		$class_code = $request->input('class');
		$course_code = $request->input('course');
		
		$result = DB::table('k12.ac_student_master AS f')
			    ->join('k12.ac_section_master AS B',function ($join){
            		$join->on('f.section_code','=','B.section_code')
		                 ->where('B.record_status','1');
		        	})
		        ->select(DB::raw("section_name, COUNT(student_code) AS strength,MAX(CAST(roll_no AS DECIMAL(10))) AS last_roll_no"))
		        ->where('f.institute_code', $this->institute_code)
			    ->where('f.session_code', $this->session_code)
			    ->where('f.record_status','1')
			    ->where('f.class_code', $class_code)
			    ->where('f.course_code', $course_code)
		        ->groupBy('f.section_code','B.sl_no','B.section_name')
			    ->orderBy('B.sl_no')
		        ->get()->toArray();
		        
		$output = array("aaData" => array());
		//$slno = 1;
		$index = 1;
		foreach($result as $row)
		{
			$obj = array($index);
			$extended = array_merge((array)$obj, (array)$row);
			
			$output['aaData'][] = array_values($extended);
			//$slno ++;
			$index++;
		}
		
		echo json_encode( $output );	    	      
	}
	
	public function GET_ASSIGNED_STUDENT_SECTION_ROLLNO(Request $request)
	{
		$class_code = $request->input('class');
		$course_code = $request->input('course');
		$section_code = $request->input('section');
		$result = DB::table('k12.ac_student_master AS f')
			    ->join('k12.ac_section_master AS B',function ($join){
            		$join->on('f.section_code','=','B.section_code')
		                 ->where('B.record_status','1');
		        	})
		        ->select(DB::raw("admission_no, student_name, section_name, roll_no"))
		        ->where('f.institute_code', $this->institute_code)
			    ->where('f.session_code', $this->session_code)
			    ->where('f.record_status','1')
			    ->where('f.class_code', $class_code)
			    ->where('f.course_code', $course_code)
			    ->where('f.section_code', $section_code)
		        ->groupBy('f.section_code','f.student_name','f.admission_no','f.record_status','B.section_name','f.roll_no')
			    ->orderBy('student_name')
		        ->get()->toArray();	
		 //dd($result);exit;       
		 $output = array("aaData" => array());
		//$slno = 1;
		$index = 1;
		foreach($result as $row)
		{
			$obj = array($index);
			$extended = array_merge((array)$obj, (array)$row);
			
			$output['aaData'][] = array_values($extended);
			$index++;
		}
		
		echo json_encode( $output );       	
	}
	
	public function selectAllCategoryOne(Request $request)
	{
		$results = DB::table('admin.code_desc')->select('code','code_desc')
				->where('status', 1)->where('category', 'Student Category 1')
				->orderBy('sl_no')->get()->toArray();
		return response()->json($results);
	}
	
	public function selectAllCategoryTwo(Request $request)
	{
		$results = DB::table('admin.code_desc')->select('code','code_desc')
				->where('status', 1)->where('category', 'Student Category 2')
				->orderBy('sl_no')->get()->toArray();
		return response()->json($results);
	}
	
	public function selectAdmissionType(Request $request)
	{
		$results = DB::table('admin.code_desc')->select('code','code_desc')
				->where('status', 1)
				->where('category', 'ADM_TYPE')
				->orderBy('sl_no')->get()->toArray();
		return response()->json($results);
	}
	
	public function selectHouse(Request $request)
	{
		$results = DB::table('k12.ac_house_master')->select('house_code','house_name')
				->where('record_status', 1)
				->where('session_code', $this->session_code)
				->orderBy('house_name')->get()->toArray();
		return response()->json($results);
	}
	
	public function selectServiceCategory(Request $request)
	{
		$results = DB::table('admin.code_desc')->select('code','code_desc')
				->where('status', 1)
				->where('category', 'SERVICE_CATEGORY')
				->orderBy('sl_no')->get()->toArray();
		return response()->json($results);
	}
	
	public function SAVE_PREVIOUS_SCHOOL_DETAILS(Request $request)
	{

		
		$admissionnumber = trim($request->admissionnumber);	
		$school_name = trim($request->txtschoolname);	
		$tcno = trim($request->txttcno);	
		$issuedate = trim($request->issuedate);	
		$txtpassedexamclass = trim($request->txtpassedexamclass);	
		$txtmarksperc = trim($request->txtmarksperc);	
		$isschoolrecog = trim($request->optradio);
		$txtmodeofinst = trim($request->txtmodeofinst);
		$txtexambody = trim($request->txtexambody);
		$txtrollno = trim($request->txtrollno);
		$txtregno = trim($request->txtregno);

		$institute_code = $this->institute_code;
		$session_code = $this->session_code;
		$logged_user = $this->user_code;
		//$student_code = $admissionnumber.'_'.$this->session_code;

		$student_code = '1807112019-20_APSDEC';
		$data = array("student_code"=>$student_code,
					"school_name"=>$school_name,
					"tc_no"=>$tcno,
					"issue_date"=>$issuedate,
					"gender"=>$txtpassedexamclass,
					"category_1" => $txtmarksperc,
					"category_2" => $isschoolrecog,
					"birth_date"=>$txtmodeofinst,
					"fathers_name"=>$txtexambody,
					"mothers_name"=>$txtrollno,
					"house_code"=>$txtregno
				 );	
		//  $insertQuery = DB::INSERT("INSERT INTO k12.student_previous_school_detail (student_code,school_name,tc_no,issue_date,
		// 		 exam_passed,percentage,is_school_recognised,mode_of_instruction,examination_body,roll_no,
		// 		 registration_no,institute_code,session_code,created_by,created_on,record_status)
		// 	 VALUES('$student_code','$school_name','$tcno',STR_TO_DATE('$issuedate','%d-%m-%Y'),
		// 			'$txtpassedexamclass','$txtmarksperc','$isschoolrecog','$txtmodeofinst','$txtexambody',
		// 			'$txtrollno','$txtregno','$institute_code','$session_code','$logged_user',NOW(),1)
		// 	 ON DUPLICATE KEY UPDATE
		// 			 school_name = '$school_name',tc_no = '$tcno',issue_date = STR_TO_DATE('$issuedate','%d-%m-%Y'), 
		// 			 exam_passed = '$txtpassedexamclass',percentage ='$txtmarksperc',
		// 			 is_school_recognised = '$isschoolrecog',mode_of_instruction = '$txtmodeofinst',
		// 			 examination_body = '$txtexambody',roll_no = '$txtrollno',registration_no = '$txtregno',
		// 			 updated_by = '$logged_user',updated_on = NOW()");		 
		

		$student_count = DB::SELECT("select count(student_code) AS count 
			from  k12.student_previous_school_detail 
			where student_code = '$student_code'");
		
		$stu_count = 0;	
		foreach($student_count AS $stu_countd)
		{
			$stu_count = $stu_countd->count;
		}		
		$result  = 'blank';
		if($stu_count > 0)
		
		{
			$update_query = DB::UPDATE("UPDATE k12.student_previous_school_detail
			SET  school_name='$school_name', 
				tc_no='$tcno', 
				exam_passed='$txtpassedexamclass', 
				percentage='$txtmarksperc', 
				is_school_recognised='$isschoolrecog',
				 mode_of_instruction='$txtmodeofinst',
				  examination_body='$txtexambody', 
				  roll_no='$txtrollno', 
				  registration_no='$txtregno'
				WHERE student_code='$student_code'");

			$result = $update_query;	
		}
		else{

			$insertQuery = DB::INSERT("INSERT INTO k12.student_previous_school_detail (student_code,school_name,tc_no,
					exam_passed,percentage,is_school_recognised,mode_of_instruction,examination_body,roll_no,
					registration_no,institute_code,session_code)
			VALUES('$student_code','$school_name','$tcno',
		   '$txtpassedexamclass','$txtmarksperc','$isschoolrecog','$txtmodeofinst','$txtexambody',
		   '$txtrollno','$txtregno','$institute_code','$session_code')");   
		   $result  = $insertQuery;


		}
		echo($result);
		die();
    	$insertQuery = DB::INSERT("INSERT INTO k12.student_previous_school_detail (student_code,school_name,tc_no,
							exam_passed,percentage,is_school_recognised,mode_of_instruction,examination_body,roll_no,
							registration_no,institute_code,session_code)
						VALUES('$student_code','$school_name','$tcno',
							   '$txtpassedexamclass','$txtmarksperc','$isschoolrecog','$txtmodeofinst','$txtexambody',
							   '$txtrollno','$txtregno','$institute_code','$session_code')");        
	}
	
	public function ACHIEVEMENT_CATEGORY(Request $request)
	{
		$results = DB::table('k12.ac_achievement_category_master')
				->select('category_code', 'category_name')
				->where('status', 1)
				->orderBy('category_name')->get()->toArray();
		return response()->json($results);
	}
	
	public function ACHIEVEMENT_SUB_CATEGORY(Request $request)
	{
		$achievement_category = $_REQUEST['achievement_category'];
		$results = DB::table('k12.ac_achievement_subcategory_master')
				->select('achievement_subcategory_code', 'achievement_subcategory_name')
				->where('status', 1)
				->where('category_code',$achievement_category)
				->orderBy('sl_no')->get()->toArray();
		return response()->json($results);
	}		
	
	public function statusDetails(Request $request)
	{
		$status = trim($request->status);
		
		$results = DB::table(DB::raw('admin.code_desc'))->select('code','code_desc')
				->where('status', 1)
				->where('category', 'K12_STUDENT_STATUS');
		if($status == '0' || $status == 0)
		{
			$results->where(DB::raw('code'),'<>','0');
		}  	
		else
		{
			$results->where(DB::raw('code'),'0');
		}	
		
		$query=$results->orderBy(DB::raw('sl_no'))->get()->toArray();
		
		return response()->json($query);		
		
	}
	
	public function selectCertificateType(Request $request)
	{
		$institute_code = 'DAVCSP';
		$results = DB::table('admin.code_desc as cd')
				->join(DB::raw('admin.doctemplate_to_institute AS dti'), DB::raw('dti.template_code'), '=', DB::raw('cd.code'))
			
				->select('code','code_desc')
				->where('dti.status', 1)
				->where('cd.category', 'CERTIFICATE_TEMPLATE')
				->where('dti.institute_code',$institute_code)
				->orderBy('sl_no')->get()->toArray();
		return response()->json($results);
		
		/*$sQuery = "SELECT code,code_desc AS description 
					FROM admin.code_desc cd
					INNER JOIN admin.doctemplate_to_institute as dti ON dti.template_code = cd.code
						WHERE cd.category = 'CERTIFICATE_TEMPLATE' 
							AND dti.institute_code='$institute_code' 
							AND dti.status = 1;";*/
	}
	
	public function studentexit(Request $request)
	{		
		$editstudentcode = trim($request->editstudentcode);
		$admissionno = trim($request->admissionno);
		$exitType= trim($request->type1);
		$exitTypeName= trim($request->exittype);
			
		$remark= trim($request->remark);
		$date_req = trim($request->date);
		$institute_code = $this->institute_code;
		$session_code= $this->session_code;
		$stu_user_code = $admissionno.$institute_code;
				
		$date = date("Y-m-d", strtotime($date_req));
		
		$tc_result = '';
		
		$student_id = explode("_",substr($editstudentcode,0,(strlen($editstudentcode)-14)));
		$user_code = $student_id[0]."_".$institute_code;
		
		if($exitTypeName == "Retrieve")
		{
			
			$data = array("is_exit"=>$exitType,"record_status"=>'1');
			$result = DB::table('k12.ac_student_master')
						->where('student_code','=',$editstudentcode)
						->where('institute_code','=',$institute_code)
						->where('session_code','=',$session_code)
						->where('record_status','=','0')
						->update($data);
													
			$data = array("status"=>'1');
			$userupdate = DB::table('admin.user_master')
						->where('user_code','=',$stu_user_code)
						->where('institute_code','=',$institute_code)
						->update($data);
						
			$data = array("student_code"=>$editstudentcode,"exit_type"=>$exitTypeName,"exit_date"=>$date,
					"remark"=>$remark,"institute_code"=>$institute_code,"session_code"=>$session_code,
					"created_by"=>'',
					"created_on"=>"NOW()");
				
			$result1 = DB::table('k12.ac_student_exit')->insert($data);					
		}	
		else
		{
			$data = array("is_exit"=>$exitType,"record_status"=>'0');
			$result = DB::table('k12.ac_student_master')
						->where('student_code','=',$editstudentcode)
						->where('institute_code','=',$institute_code)
						->where('session_code','=',$session_code)
						->where('record_status','=','1')
						->update($data);
			$data = array("status"=>'0');
			$userupdate = DB::table('admin.user_master')
						->where('user_code','=',$stu_user_code)
						->where('institute_code','=',$institute_code)
						->update($data);
			$data = array("student_code"=>$editstudentcode,"exit_type"=>$exitTypeName,"exit_date"=>$date,
					"remark"=>$remark,"institute_code"=>$institute_code,"session_code"=>$session_code,
					"created_by"=>'',
					"created_on"=>"NOW()");
			$result1 = DB::table('k12.ac_student_exit')->insert($data);							
				
		}									  
		$output = array('dbStatus' =>"",'dbMessage' =>"");
		if($result and $userupdate and $result1)
		{
            $output['dbStatus'] = 'SUCCESS';
            $output['dbMessage'] = 'Data Inserted Successfully.';
        }
        else
        {
            $output['dbStatus'] = 'FAILURE';
            $output['dbMessage'] = 'OOPS! Someting Went Wrong on insert Operation.';
        }
		
		return response()->json($output);
	}	
	
	public function EXIT_DETAILS(Request $request)
	{
		$student_code = $request->input('student_code');
		$result = DB::table('k12.ac_student_exit')
		        ->select(DB::raw("exit_type,TO_CHAR(exit_date::date,'DD-MM-YYYY') AS exit_date,remark"))
		        ->where('institute_code', $this->institute_code)
			    ->where('session_code', $this->session_code)
			    ->where('record_status','1')
			    ->where('student_code', $student_code)
			    ->orderBy('student_exit_id', 'desc')
		        ->get()->toArray();	
		 //dd($result);exit;       
		 $output = array("aaData" => array());
		//$slno = 1;
		$index = 1;
		foreach($result as $row)
		{
			$obj = array($index);
			$extended = array_merge((array)$obj, (array)$row);
			
			$output['aaData'][] = array_values($extended);
			//$slno ++;
			$index++;
		}
		
		echo json_encode( $output );
	}
	
	public function studentexit_copy(Request $request)
	{		
		$editstudentcode = trim($request->editstudentcode);
		$admissionno = trim($request->admissionno);
		$exitType= trim($request->type1);
		$exitTypeName= trim($request->exittype);
		
		
		$remark= trim($request->remark);
		$date_req = trim($request->date);
		$institute_code = $this->institute_code;
		$session_code= $this->session_code;
		
		//$date = DateTime::createFromFormat('d-m-Y', $date_req)->format('Y-m-d');
		
		$date = date("Y-m-d", strtotime($date_req));
		
		$student_id = $editstudentcode;
		$stu_user_code = $admissionno.$institute_code;
		$present_record_status = '1';
		$updated_record_status = '0';
		
		if($exitType == "0" || $exitType == 0)
		{
			$present_record_status = '0';
			$updated_record_status = '1';
		}
		
		$data = array("is_exit"=>$exitType,"record_status"=>$updated_record_status,"tc_date"=>$date,
					"tc_remark"=>$remark,"updated_on"=>"NOW()");
					
		//$data = array("is_exit"=>$exitType);		
					
					
			$result = DB::table('k12.ac_student_master')
			->where('student_code','=',$editstudentcode)
			->where('institute_code','=',$this->institute_code)
			->where('session_code','=',$session_code)
			//->where('record_status','=',$present_record_status)
			
			
			->update($data);
			//->toSql();		
			
			/*echo($result.'</br>');
			
			echo($editstudentcode.'</br>');
			echo($this->institute_code.'</br>');
			echo($session_code.'</br>');
			echo($result.'</br>');
			die();*/
			
			
		if($result)
		{
            
            $query = "UPDATE admin.user_master 
									SET status = '$updated_record_status' 
									WHERE user_code='$stu_user_code'
									AND institute_code = '$institute_code';";
									
			//echo($query);die();					
									
			$data = array("status"=>$updated_record_status);
			
			$result = DB::table('admin.user_master')
			->where('user_code','=',$stu_user_code)
			->where('institute_code','=',$this->institute_code)
			
			->update($data);	
			
			$result = 1;
			if($result)
			{
				/*$str = "INSERT INTO k12.ac_student_exit(student_code, exit_type, exit_date, remark, 
			            institute_code, session_code, created_by, created_on,  record_status)
			    VALUES ('$editstudentcode','$exitTypeName','$date','$remark','$institute_code','$session_code','$user_code','NOW()','1');";
		      */
		      	$data = array("student_code"=>$editstudentcode,"exit_type"=>$exitTypeName,"exit_date"=>$date,
					"remark"=>$remark,"institute_code"=>$this->institute_code,"session_code"=>$this->session_code,
					"created_by"=>'',
					"created_on"=>"NOW()",
					"record_status"=>"1");
				
				$result = DB::table('k12.ac_student_exit')->insert($data);
				
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
			
				
			}
			else
			{
				 $output['dbStatus'] = 'FAILURE2';
            	$output['dbMessage'] = 'OOPS! Someting Went Wrong.';
			}							
        }
        else
        {
            $output['dbStatus'] = 'FAILURE1';
            $output['dbMessage'] = 'OOPS! Someting Went Wrong.';
        }	
		return response()->json($output);
	}
	
	
	public function GET_MISC_DETAILS(Request $request)
	{
		$student_code = $request->input('student_code');
		
		$html = '';
		
		$field_arr = DB::table('k12.field_master as cf')
				->join(DB::raw('k12.entity_sub_type_master em'), function ($join) {
					$join->on(DB::raw('em.entity_sub_type_code'), '=', DB::raw('cf.entity_sub_type_code'))
					->where(DB::raw('em.entity_type_code'), 'STUDENT');
				})
				->leftJoin(DB::raw('k12.field_values fv'), function ($join)use($student_code) {
					$join->on(DB::raw('fv.field_code'), '=', DB::raw('cf.field_code'))
					->where(DB::raw('fv.student_code'), $student_code)
					->where(DB::raw('fv.institute_code'), $this->institute_code);
				})
				->select(DB::raw("cf.entity_sub_type_code,em.entity_sub_type_name,cf.field_code,cf.field_label,
					cf.sl_no,cf.field_type,fv.field_value,cf.allowed_value"))
				->orderBy(DB::raw("em.sl_no,cf.sl_no"))
				->get()
				->toArray();
		/*echo '<pre>';
		print_r($field_arr);
		exit;*/
		$prev = '';
		$k = 1;
		$html .='<form  id="misc_form" name = "misc_form" >';
		foreach($field_arr as $row)
		{
			
			if($prev != $row->entity_sub_type_name)
			{
				$html .= '<tr>
							<td colspan="2">
								<h4 style="color:#3f51b5;">'.$row->entity_sub_type_name.'</h4>
							</td>
						</tr>';
			}
			
			if($row->field_type != 'DROPDOWN')
			{
				$html .= '<tr>
						<td width="50%">
							<label class="control-label" style="padding-left:4%;">'.$k.') '.$row->field_label.'</label>
						</td>
						<td>
							<input class="form-control" id="'.$row->field_code.'" name="'.$row->field_code.'" value="'.$row->field_value.'" type="text" autocomplete="off" style="width:55%;" />
						</td>
					</tr>';
			}
			else
			{
				$options = '<option value="">SELECT</option>';
				$optionsarray = explode("|",$row->allowed_value);
				for($j=0;$j<sizeof($optionsarray);$j++)
				{
					if($optionsarray[$j] == $row->field_value)
					{
						$options .= '<option value="'.$optionsarray[$j].'"  selected >'.$optionsarray[$j].'</option>';
					}
					else
					{
						$options .= '<option value="'.$optionsarray[$j].'" >'.$optionsarray[$j].'</option>';
					}
				}
				$html .= '<tr>
						<td>
							<label class="control-label" style="padding-left:4%;">'.$k.') '.$row->field_label.'</label>
						</td>
						<td>
							<select class="form-control" id="'.$row->field_code.'" name="'.$row->field_code.'" style="width:30%;" >'.$options.'</select>
						</td>
					</tr>';
				
			}
			
			$k++;
			$prev = $row->entity_sub_type_name;
			
		}
		$html .= '<tr><button type="button" class="btn btn-primary" onclick="save_misc_data()">Save</button></tr>';
		
		$html.='</form>';
		echo $html;
		exit;
	}

	public function SAVE_MISC_DETAILS(Request $request)
	{
		echo('<pre>');
		print_r( $request->input());
	}
}

