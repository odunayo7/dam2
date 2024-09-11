<?php

class Adminmodels extends CI_Model
{
	function __construct() {
        parent::__construct();
    }
	
	function login($data) {
        $this->db->select('*');
		$this->db->from('mlt_admin');
		$this->db->where('username', $data['username']);
		$this->db->where('password', $data['password']);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
    }

    function updateLink($id,$data){
        $this->db->where('id', $id);
        $this->db->update('mlt_links', $data);
    }

    function createnew($data){
        $this->db->insert('mlt_links', $data);
    }
    
    function links($id, $data){
        $this->db->where('id', $id);
        $this->db->update('mlt_links', $data);
    }

    function read_link_details($id){
    	$this->db->select('*');
		$this->db->from('mlt_links');
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			echo "failed";
		}
    }

    function logs($data){
        $this->db->insert('mlt_clicks', $data);
    }

    function clicksChart(){
    	for($i=30;$i>=0;$i--){
            $newdates=date('d-m-Y', strtotime('-'.$i.' days'));
            $todate = date_create($newdates);
            $todate=date_format($todate,"Y-m-d");
            $result=$this->db->query("Select count(date_clicked) as num from mlt_clicks where date_clicked LIKE '$todate%'");
            $resulttwo=$this->db->query("Select count(DISTINCT ip_address) as num from mlt_clicks where date_clicked LIKE '$todate%'");
            $vale[] = array(
                'date' => $newdates,
                'number' => $result->row()->num,
                'unique' => $resulttwo->row()->num,
            );
        }
        echo json_encode($vale);
    }

    function linkClicksChart($linkId){
    	for($i=30;$i>=0;$i--){
            $newdates=date('d-m-Y', strtotime('-'.$i.' days'));
            $todate = date_create($newdates);
            $todate=date_format($todate,"Y-m-d");
            $result=$this->db->query("Select count(date_clicked) as num from mlt_clicks where date_clicked LIKE '$todate%' AND link_id = '$linkId'");
            $resulttwo=$this->db->query("Select count(DISTINCT ip_address) as num from mlt_clicks where date_clicked LIKE '$todate%' AND link_id = '$linkId'");
            $vale[] = array(
                'date' => $newdates,
                'number' => $result->row()->num,
                'unique' => $resulttwo->row()->num,
            );
        }
        echo json_encode($vale);
    }

    function exportdata($linkId){
        
        $q = $this->db->query("SELECT link_title, link_url, ip_address, date_clicked FROM mlt_clicks WHERE link_id = '$linkId'");
        $response = $q->result_array();
     
        return $response;
    }

    function countAllSubmissions(){
        $this->db->from("mlt_links");
        return $this->db->count_all_results();
    }

    function fetch_submission($limit, $start){
        $sortorder = 'desc';
        $sortcol = array('surname','email','scholarship_type','date_created','id' );
        $sortby = 'date_created';


        $this->db->limit($limit, $start);
        $this->db->order_by($sortby, $sortorder);
        $query = $this->db->get("mlt_links");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
}