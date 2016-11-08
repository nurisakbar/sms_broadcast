<?php
class Auth extends MX_Controller{
    
    function __construct() {
        parent::__construct();

    }
    
    function index(){
        if(isset($_POST['submit']))
        {
        

            $data = array(
                'email'     => $this->input->post('email'),
                'password'  => md5($this->input->post('password')));
            $users = $this->db->get_where('users',$data);
            if($users->num_rows()>0)
            {
                $user= $users->row_array();
                $this->session->set_userdata(array('login_status'=>'oke','level'=>$user['level']));
                $this->session->set_userdata($user);
                redirect('transaksi');
            }else{
                redirect('auth');
            }
        }else{
                    $this->load->view('auth/login');
        }
    }
    
    function signup(){
        if(isset($_POST['submit']))
        {
            $this->load->helper('string');
            $token = random_string('alnum', 4);
            $nama  = $this->input->post('nama_lengkap');
            $account = array(
                'nama_lengkap'      => $nama,
                'email'             => $this->input->post('email'),
                'password'          => md5($this->input->post('password')),
                'no_hp'             => $this->input->post('no_hp'),
                'token'             => $token
            );
            $this->db->insert('users',$account);
            // send sms token
            $pesan = "Terima kasih $nama telah mendaftar di belajarphp.net, ini adalah demo dari tutorial membangun layanan sms online berbasis client server yang baru saja rilis, Token anda adalah ".$token;
            sms($this->input->post('no_hp'), $pesan);
            redirect('auth/verivy');
        }else{
            $this->load->view('auth/signup');
        }
    }
    
    function verivy(){
        if(isset($_POST['submit'])){
            $token = $this->input->post('token');
            $account = $this->db->get_where('users',array('token'=>$token));
            if($account->num_rows()>0)
            {
                $akun = $account->row_array();
                $this->db->where('users_id',$akun['users_id']);
                $this->db->update('users',array('active'=>'1'));
                $this->db->insert('pbk',array('users_id'=>$akun['users_id'],'GroupID'=>1,'Name'=>$akun['nama_lengkap'],'Number'=>$akun['no_hp']));
                redirect('inbox');
            }else{
                redirect('auth/verivy');
            }
        }else{
            $this->load->view('verivy');
        }
    }
    
    function logout(){
        $this->session->sess_destroy();
        redirect('auth');
    }
}