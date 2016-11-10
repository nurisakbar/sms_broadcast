<?php

Class Phonebook extends CI_Controller {

    function __construct() {
        parent::__construct();
        is_login();
    }

    function data() {
        $this->load->library('datatables_ssp');
        $table = 'view_phonebook';
        $primaryKey = 'ID';
        $columns = array(
            array('db' => 'Name',       'dt' => 'Name'),
            array('db' => 'Number',     'dt' => 'Number'),
            array('db' => 'GroupName',  'dt' => 'GroupName'),
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

            function index() {
                $this->template->load('template', 'phonebook/list');
            }

            function add() {
                if (isset($_POST['submit'])) {
                    $this->db->insert('pbk', $this->params());
                    redirect($this->uri->segment(1));
                } else {
                    $this->template->load('template', 'phonebook/add');
                }
            }

            function edit() {
                if (isset($_POST['submit'])) {
                    $this->db->where('ID',  $this->input->post('id'));
                    $this->db->update('pbk', $this->params());
                    redirect($this->uri->segment(1));
                } else {
                    $id = $this->uri->segment(3);
                    $data['row'] = $this->db->get_where('pbk',array('ID'=>$id))->row_array();
                    $this->template->load('template', 'phonebook/edit',$data);
                }
            }
            
            function import(){
                $this->load->library('CPHPexcel');
                // upload
                $file_tmp = $_FILES['file']['tmp_name'];
                $file_name = $_FILES['file']['name'];
                $file_size =$_FILES['file']['size'];
                $file_type=$_FILES['file']['type'];
                 move_uploaded_file($file_tmp,"uploads/".$file_name);
                // nama file
                $inputFileName = "uploads/".$file_name;
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                } catch(Exception $e) {
                    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
                }
                //  Get worksheet dimensions
                $sheet = $objPHPExcel->getSheet(0); 
                $highestRow = $sheet->getHighestRow(); 
                $highestColumn = $sheet->getHighestColumn();
                $group_name = array('Name'=>$this->input->post('group'));
                $this->db->insert('pbk_groups',$group_name);
                $GroupID = $this->db->insert_id();
                //  Loop through each row of the worksheet in turn
                for ($row = 2; $row <= $highestRow; $row++){ 
                    //  Read a row of data into an array
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
                    $nama     = $rowData[0][0];
                    $no_hp    = $rowData[0][1];
                    $this->db->insert('pbk',array('Name'=>$nama,'Number'=>$no_hp,'GroupID'=>$GroupID));
                }
                redirect('phonebook');
            }

            function delete($id) {
                $this->db->where('id', $id);
                $this->db->delete('pbk');
                redirect($this->uri->segment(1));
            }

            function params() {
                $data = array(
                    'Name'      => $this->input->post('Name'),
                    'Number'    => $this->input->post('Number'),
                    'GroupID'   => $this->input->post('GroupID'));
                return $data;
            }

        }
        