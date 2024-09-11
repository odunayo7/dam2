<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {
        Parent::__construct();
		$this->load->model('Adminmodels');
	}

	function index(){
		$this->load->view('Admin/Index');
	}

	function logout() {
		#$this->session->sess_destroy();
		$this->session->unset_userdata('IntAdmin');
		redirect('admin');
    }

	function login(){
		$this->output->set_content_type('application_json');
        
        $this->form_validation->set_rules('username', 'username', 'required|trim|strip_tags');
        $this->form_validation->set_rules('userpass', 'password', 'required|trim|strip_tags');
        
		if($this->form_validation->run() == false){
			redirect("admin");
		}else{
			$username=$this->input->post('username');
			$password=$this->input->post('userpass');
			$data = array(
            
                'username' => $username,
                'password' => newpassword($password)
            );
            $login = $this->Adminmodels->login($data);
            //hello

            #echo $login;
            if ($login == 'true') {
            	$this->session->set_userdata('IntAdmin', 1);
    			redirect("admin/dashboard");
            }else{
            	$this->session->set_flashdata('msg', 'Invalid Username and Password');
				redirect("admin");
            }
            //hello
		}
	}

	function dashboard(){
		$user = $this->session->userdata('IntAdmin');
		if(!$user){
            $this->logout();
        }else{
			$this->ui->loadAdminTemplate("Dashboard",'Admin/Home', array());
        }
	}

	function edit_link(){
		$user = $this->session->userdata('IntAdmin');
		if(!$user){
            $this->logout();
        }else{
			$this->ui->loadAdminTemplate("Edit Link",'Admin/Editlink', array());
        }
	}

	function updatenote(){
	    $this->form_validation->set_rules('link_title', 'Link Title', 'required|trim|strip_tags');
		$this->form_validation->set_rules('link', 'Link', 'required|trim|strip_tags');
        $this->form_validation->set_rules('sort', 'Sort', 'required|trim|strip_tags');
        $id = $this->input->post('did');
		if($this->form_validation->run() == false){
			redirect('admin/edit_project/'.$id);
        }else{
			$id= $this->input->post('did');
			$data = array(
    			'title' => $this->input->post('link_title'),
    			'link' => $this->input->post('link'),
    			'sort' => $this->input->post('sort')
			);
			
			$this->Adminmodels->updateLink($id,$data);
			$this->session->set_flashdata('updatedlink', 'Link Successfully Updated.');
			redirect('admin/dashboard');
		}
	}

	function addnew(){
		$user = $this->session->userdata('IntAdmin');
		if(!$user){
            $this->logout();
        }else{
			$this->ui->loadAdminTemplate("Add New",'Admin/Addnew', array());
        }
	}

	function link_details(){
		$user = $this->session->userdata('IntAdmin');
		if(!$user){
            $this->logout();
        }else{
			$this->ui->loadAdminTemplate("Link Details",'Admin/Details', array());
        }
	}

	function createnew(){
		$this->form_validation->set_rules('title', 'Link Title', 'trim|strip_tags');
		$this->form_validation->set_rules('url', 'url', 'trim|strip_tags');
        $this->form_validation->set_rules('sort', 'Sort', 'trim|strip_tags');
		if($this->form_validation->run() == false){
			$this->ui->loadAdminTemplate("Add New",'Admin/Addnew', array());
        }else{
			

        	$_FILES['file']['name']     = $_FILES['picture']['name'][0];
            $_FILES['file']['type']     = $_FILES['picture']['type'][0];
            $_FILES['file']['tmp_name'] = $_FILES['picture']['tmp_name'][0];
            $_FILES['file']['error']     = $_FILES['picture']['error'][0];
            $_FILES['file']['size']     = $_FILES['picture']['size'][0];


			$config['upload_path'] ='./uploads/';
        	$config['allowed_types'] ='png|jpg|jpeg';
        	$config['max_size'] = 40000;
        	$new_name = time();
			$config['file_name'] = $new_name;
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('file')){
				$data = array(
	    			'title' => $this->input->post('title'),
	    			'description' => $this->input->post('desc'),
	    			'video' => $this->input->post('video'),
	    			'link' => $this->input->post('url'),
	    			'image_name' => 'default.jpg',
	    			'date_created' => date('Y-m-d H:i:s'),
	    			'published' => '1',
	    			'sort' => $this->input->post('sort')
				);
				$this->Adminmodels->createnew($data);
				$this->session->set_flashdata('created', 'Link Successfully Created.');
				echo json_encode(array("status"=>"success"));
			}else{
				$fileName = $this->upload->data('file_name');
				$data = array(
	    			'title' => $this->input->post('title'),
	    			'description' => $this->input->post('desc'),
	    			'video' => $this->input->post('video'),
	    			'link' => $this->input->post('url'),
	    			'image_name' => $fileName,
	    			'date_created' => date('Y-m-d H:i:s'),
	    			'published' => '1',
	    			'sort' => $this->input->post('sort')
				);
				$this->Adminmodels->createnew($data);
				$this->session->set_flashdata('created', 'Link Successfully Created.');
				echo json_encode(array("status"=>"success"));
			}
		}
	}

	function updatelink(){
		$id = $this->input->post('did');
		$this->form_validation->set_rules('title', 'Link Title', 'trim|strip_tags');
		$this->form_validation->set_rules('url', 'url', 'trim|strip_tags');
        $this->form_validation->set_rules('sort', 'Sort', 'trim|strip_tags');
		if($this->form_validation->run() == false){
			$this->ui->loadAdminTemplate("Add New",'Admin/Addnew', array());
        }else{
			

        	$_FILES['file']['name']     = $_FILES['picture']['name'][0];
            $_FILES['file']['type']     = $_FILES['picture']['type'][0];
            $_FILES['file']['tmp_name'] = $_FILES['picture']['tmp_name'][0];
            $_FILES['file']['error']     = $_FILES['picture']['error'][0];
            $_FILES['file']['size']     = $_FILES['picture']['size'][0];


			$config['upload_path'] ='./uploads/';
        	$config['allowed_types'] ='png|jpg|jpeg';
        	$config['max_size'] = 40000;
        	$new_name = time();
			$config['file_name'] = $new_name;
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('file')){
				$data = array(
	    			'title' => $this->input->post('title'),
	    			'description' => $this->input->post('desc'),
	    			'video' => $this->input->post('video'),
	    			'link' => $this->input->post('url'),
	    			'date_created' => date('Y-m-d H:i:s'),
	    			'sort' => $this->input->post('sort')
				);
				$this->Adminmodels->updateLink($id, $data);
				$this->session->set_flashdata('updatedlink', 'Link Successfully Updated.');
				echo json_encode(array("status"=>"success"));
			}else{
				$fileName = $this->upload->data('file_name');
				$data = array(
	    			'title' => $this->input->post('title'),
	    			'description' => $this->input->post('desc'),
	    			'video' => $this->input->post('video'),
	    			'link' => $this->input->post('url'),
	    			'image_name' => $fileName,
	    			'date_created' => date('Y-m-d H:i:s'),
	    			'sort' => $this->input->post('sort')
				);
				$this->Adminmodels->updateLink($id, $data);
				$this->session->set_flashdata('updatedlink', 'Link Successfully Updated.');
				echo json_encode(array("status"=>"success"));
			}
		}
	}
	
	function unpublishlink($id){
	    $data = array(
			'published' => '0'
		);
	    $this->Adminmodels->links($id, $data);
	    redirect('admin/dashboard');
	}
	
	function publishlink($id){
	    $data = array(
			'published' => '1'
		);
	    $this->Adminmodels->links($id, $data);
	    redirect('admin/dashboard');
	}

	function clicksChart(){
		// 
		$rty = $this->Adminmodels->clicksChart();
	}

	function linkClicksChart(){
		$linkId = $this->input->get('linkid');
		$rty = $this->Adminmodels->linkClicksChart($linkId);
	}

	function exportdata(){
		$linkId = $this->input->post('linkid');

		// Export function 
			$filename = 'MTN_Linktree_Clicks_'.date('Y-m-d His').'.csv'; 
			header("Content-Description: File Transfer"); 
			header("Content-Disposition: attachment; filename=$filename"); 
			header("Content-Type: application/csv; ");

			// get data 
			$usersData = $this->Adminmodels->exportdata($linkId);
			// file creation 
			$file = fopen('php://output', 'w');

			$header = array("Link Title", "Link Url", "Unique Indentity", "Date"); 
			fputcsv($file, $header);
			foreach ($usersData as $key=>$line){ 
			 fputcsv($file,$line); 
			}
			fclose($file); 
			exit;
	}

	function links(){
		$config = array();
        $config["base_url"] = base_url() . "admin/links/";
        $config["total_rows"] = $this->Adminmodels->countAllSubmissions();
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["results"] = $this->Adminmodels->fetch_submission($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data["headers"]  = array(
        	'title' => 'Name',
        	'link' => 'Email',
        	'sort' => 'Scholarship Type',
        	'published' => 'Date Submiited',
        	'date_created' => 'Action',
        	'id' => 'NewAction'
        );

		$this->ui->loadAdminTemplate("Links",'Admin/Submissions', $data);
	}
}