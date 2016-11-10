<?php

Class Draft extends CI_Controller {

    function __construct() {
        parent::__construct();
        is_login();
    }

    function data() {
        $this->load->library('datatables_ssp');
        $table = 'draf';
        $primaryKey = 'id';
        $columns = array(
            array('db' => 'judul',       'dt' => 'judul'),
            array('db' => 'pesan',     'dt' => 'pesan'),
            array(
                'db' => 'id',
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
                $this->template->load('template', 'draft/list');
            }

            function add() {
                if (isset($_POST['submit'])) {
                    $this->db->insert('draf', $this->params());
                    redirect($this->uri->segment(1));
                } else {
                    $this->template->load('template', 'draft/add');
                }
            }

            function edit() {
                if (isset($_POST['submit'])) {
                    $this->db->where('id',  $this->input->post('id'));
                    $this->db->update('draf', $this->params());
                    redirect($this->uri->segment(1));
                } else {
                    $id = $this->uri->segment(3);
                    $data['row'] = $this->db->get_where('draf',array('id'=>$id))->row_array();
                    $this->template->load('template', 'draft/edit',$data);
                }
            }

            function delete($id) {
                $this->db->where('id', $id);
                $this->db->delete('tabel_menu');
                redirect($this->uri->segment(1));
            }

            function params() {
                $data = array(
                    'judul'      => $this->input->post('judul'),
                    'pesan'    => $this->input->post('pesan'));
                return $data;
            }

        }
        