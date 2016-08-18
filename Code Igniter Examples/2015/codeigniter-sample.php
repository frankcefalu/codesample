<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Careers_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    public function getlatest(){
       
        // Run the query
		$this->db->order_by("title", "asc"); 
        $query = $this->db->get('careers');
        // Let's check if there are any results
        if($query->num_rows > 0)
        {
		// If there is a user, then create session data
			$result = $query->result();
			foreach($result as $key=>$value):
			$cid = $value->cid;
			$job[$cid]['title'] = $value->title;
			$job[$cid]['cid'] = $value->cid;
			$job[$cid]['description'] = $value->description;
			$job[$cid]['recruiter_id'] = $value->recruiter_id;
			
				$this->db->order_by("cid", "asc"); 
				$this->db->where("cid", $cid); 
				$query2 = $this->db->get('careers_bullets');
				
				$result2 = $query2->result();
				foreach($result2 as $key2=>$value2):
				$job[$cid]["bullet"][] = $value2->content;
				endforeach;
						
			endforeach;
			
			return $job;
        }
        // If the previous process did not validate
        // then return false.
        return false;
    }
	
    public function load(){
	    $cid = $this->security->xss_clean($this->uri->segment(4));
        // Run the query
		$this->db->where('cid', $cid);
        $query = $this->db->get('careers');
		$this->db->where('cid', $cid);
        $query2 = $this->db->get('careers_bullets');
        // Let's check if there are any results
        if($query->num_rows > 0)
        {
		// If there is a user, then create session data
			 $load["main"] = $query->result();
			 $load["bullets"] = $query2->result();
			 return $load;
        }
        // If the previous process did not validate
        // then return false.
        return false;
    }
	
    public function create(){
	    $data = array("title" => $this->security->xss_clean($_POST['title']),
					  "description" => $this->security->xss_clean($_POST['description']), 
		 		      "recruiter_id" => $this->security->xss_clean($_POST['recruiter_id']));
		
		$this->db->insert("careers",$data);
		$last_id = $this->db->insert_id();
				
	    $bullet = $_POST['bullet'];
		for($i=0;$i<count($bullet);$i++):
			
			$data2["content"] = $bullet[$i];
			$data2["cid"] = $last_id;
			$this->db->insert("careers_bullets",$data2);

		
		endfor;
		
        // Run the query
		$this->db->where('cid', $cid);
        $query = $this->db->get('careers');
        // Let's check if there are any results
        if($last_id > 0)
        {
		// If there is a user, then create session data
			return $last_id;
        }
        // If the previous process did not validate
        // then return false.
        return false;
		
    }
	
	
	public function save(){
	
	    $data = array("title" => $this->security->xss_clean($_POST['title']),
					  "description" => $this->security->xss_clean($_POST['description']), 
		 		      "recruiter_id" => $this->security->xss_clean($_POST['recruiter_id']));
	
		$this->db->where("cid", $_POST['cid']);
		$this->db->update("careers",$data);
	
		foreach($_POST['bullet'] as $id=>$content):
		
		$this->db->where("cbid", $id);
		$this->db->where("cid", $_POST['cid']);
		$this->db->update("careers_bullets",array("content"=>$content));
		
		endforeach;
		
	    $bullet = $_POST['new_bullet'];
		for($i=0;$i<count($bullet);$i++):
			
			$data2["content"] = $bullet[$i];
			$data2["cid"] = $_POST['cid'];
			$this->db->insert("careers_bullets",$data2);

		
		endfor;
	
	
		return $_POST['cid'];
	}
	
	
	
	public function delete_bullet(){
	
	$cbid = $this->uri->segment(5);
	$this->db->where('cbid', $cbid);
    $query = $this->db->get('careers_bullets');
	$result = $query->result();
	$this->db->delete('careers_bullets', array('cbid' => $cbid)); 	
	return $result[0]->cid;
   }
	
	
	public function delete_job(){
	$cid = $this->uri->segment(5);
	$this->db->delete('careers', array('cid' => $cid));
	return true;	

   }
	
}
?>