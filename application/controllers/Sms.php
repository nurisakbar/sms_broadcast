<?php
Class SMS extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        is_login();
    }
    function outbox_data(){
        $this->load->library('datatables_ssp');
        $table = 'sentitems';
        $primaryKey = 'ID';
        $columns = array(
            array('db' => 'SendingDateTime',       'dt' => 'SendingDateTime'),
            array('db' => 'DestinationNumber',     'dt' => 'DestinationNumber'),
            array('db' => 'TextDecoded',  'dt' => 'TextDecoded'),
            array(
                'db' => 'ID',
                'dt' => 'aksi',
                'formatter' => function( $d ) {
                    return anchor($this->uri->segment(1) . '/edit/' . $d, '<i class="fa fa-eye"></i>', array('class' => 'btn btn-danger btn-sm', 'title' => 'Edit Data')) . '  ' . anchor($this->uri->segment(1) . '/delete/' . $d, '<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-sm', 'title' => 'Delete Data'));
                }
                    ),
                );
                $sql_details = array(
                    'user' => $this->db->username,
                    'pass' => $this->db->password,
                    'db' => $this->db->database,
                    'host' => $this->db->hostname
                );

                echo json_encode(
                        Datatables_ssp::simple($_GET, $sql_details, $table, $primaryKey, $columns)
                );
    }
    
    function inbox_data(){
        $this->load->library('datatables_ssp');
        $table = 'inbox';
        $primaryKey = 'ID';
        $columns = array(
            array('db' => 'ReceivingDateTime',       'dt' => 'ReceivingDateTime'),
            array('db' => 'SenderNumber',     'dt' => 'SenderNumber'),
            array('db' => 'TextDecoded',  'dt' => 'TextDecoded'),
            array(
                'db' => 'ID',
                'dt' => 'aksi',
                'formatter' => function( $d ) {
                    return anchor($this->uri->segment(1) . '/read/' . $d, '<i class="fa fa-eye"></i>', array('class' => 'btn btn-danger btn-sm', 'title' => 'Edit Data')) . '  ' . anchor($this->uri->segment(1) . '/delete/' . $d, '<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-sm', 'title' => 'Delete Data'));
                }
                    ),
                );
                $sql_details = array(
                    'user' => $this->db->username,
                    'pass' => $this->db->password,
                    'db' => $this->db->database,
                    'host' => $this->db->hostname
                );

                echo json_encode(
                        Datatables_ssp::simple($_GET, $sql_details, $table, $primaryKey, $columns)
                );
    }
    
    function dashboard(){
        $this->template->load('template','sms/dashboard');
    }
    
    function inbox(){
        $this->template->load('template','sms/inbox');
    }
    
    
    function outbox(){
        $this->template->load('template','sms/outbox');
    }
    
    function send(){
        if(isset($_POST['submit'])){
            $GroupID = $this->input->post('GroupID');
            $message = $this->input->post('message');
            $pbk     = $this->db->get_where('pbk',array('GroupID'=>$GroupID));
            foreach ($pbk->result() as $row){
               sms($row->Number, $message);
            }
            redirect('sms/inbox');
        }else{
            $this->template->load('template','sms/send');
        }
    }
    
    function read($id){
        if(isset($_POST['submit'])){
           sms($this->input->post('no_hp'), $this->input->post('pesan'));
            redirect('sms/outbox');
        }else{
            $id = $this->uri->segment(3);
            $data['row'] = $this->db->get_where('inbox',array('ID'=>$id))->row_array();
            $this->template->load('template','sms/read',$data);
        }
    }
}