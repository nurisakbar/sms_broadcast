<?php

function is_login(){
      $CI     =   & get_instance();
      if($CI->session->userdata('login_status')==''){
          redirect('auth');
      }
}

function sms($nohp,$pesan)
{
    $jmlSMS     = ceil(strlen($pesan)/153);
    

    if($jmlSMS>1)
    {
        longsms($nohp,$pesan);
    }else{
        
        shortsms($nohp,$pesan);
    }
}

    function shortsms($nohp,$pesan)
    {
        $CI =& get_instance();
        $params = array(
            'SendingDateTime'   =>  date('Y-m-d H:i:s'),
            'DestinationNumber' =>  $nohp,
            'TextDecoded'       =>  $pesan
        );
        $CI->db->insert('outbox',$params);
        
    }
    
    
    function longsms($nohp,$pesan){
       $CI =& get_instance();
        // hitung jumlah sms
        $jmlSMS     = ceil(strlen($pesan)/153);
        // pecah sms
        $pecah      = str_split($pesan,153);
        // baca id terakhir dari tabel outbox
        $sql        = $CI->db->query("show table status like 'outbox'")->row_array();
        $newID      = $sql['Auto_increment'];
        // random bilangan 1 sampai 225
        $random     = rand(1,255);
        // ubah random ke hedaximal 2 digit
        $headerUDH  = sprintf("%02s",  strtoupper(dechex($random)));
        // proses insert tiap part sms 
        for($i=1;$i<=$jmlSMS;$i++)
        {
            // kontruksi UDH untuk setiap part
            $udh    =   "050003".$headerUDH.sprintf("%02s",$jmlSMS).sprintf("%02s",$i);
            $msg    =   $pecah[$i-1];
            // jika part 1 maka sisipkan ke tabel outbox
            if($i==1)
            {
                $sms = array(
                    'DestinationNumber' =>'085861790446',
                    'UDH'=>$udh,
                    'SendingDateTime'=>date('Y-m-d H:i:s'),
                    'TextDecoded'=>$msg,
                    'ID'=>$newID,
                    'Multipart'=>'true');
                $CI->db->insert('outbox',$sms);
            }else{
                // selain itu ke tabel outbx multipart
                 $sms = array(
                    'UDH'=>$udh,
                    'TextDecoded'=>$msg,
                    'ID'=>$newID,
                    'SequencePosition'=>$i);
                $CI->db->insert('outbox_multipart',$sms);
            }
        }
        
        
    }


           function rp($x)
       {
           return number_format($x,0,",",".");
       }
       
       function waktu()
       {
           date_default_timezone_set('Asia/Jakarta');
           return date("Y-m-d H:i:s");
       }
              
       function tgl_indo($tgl)
       {
            return substr($tgl, 8, 2).' '.getbln(substr($tgl, 5,2)).' '.substr($tgl, 0, 4);
       }
    
    function tgl_indojam($tgl,$pemisah)
    {
        return substr($tgl, 8, 2).' '.getbln(substr($tgl, 5,2)).' '.substr($tgl, 0, 4).' '.$pemisah.' '.  substr($tgl, 11,8);
    }
    
    
    function getbln($bln)
    {
        switch ($bln) 
        {
            
            case 1:
                return "Januari";
            break;
        
            case 2:
                return "Februari";
            break;
        
            case 3:
                return "Maret";
            break;
        
            case 4:
                return "April";
            break;
        
            case 5:
                return "Mei";
            break;
        
            case 6:
                return "Juni";
            break;
        
            case 7:
                return "Juli";
            break;
        
            case 8:
                return "Agustus";
            break;
        
            case 9:
                return "September";
            break;
        
             case 10:
                return "Oktober";
            break;
        
            case 11:
                return "November";
            break;
        
            case 12:
                return "Desember";
            break;
        }
        
    }
?>
