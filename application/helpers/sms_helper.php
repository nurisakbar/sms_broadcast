<?php

function sms($nohp,$pesan)
{
    $waktu = date("Y-m-d h:i:sa");
    $jmlSMS     = ceil(strlen($pesan)/153);
    

    if($jmlSMS>1)
    {
        longsms($nohp,$pesan,$waktu);
    }else{
        
        shortsms($nohp,$pesan,$waktu);
    }
}

    function shortsms($nohp,$pesan,$waktu)
    {
        $CI =& get_instance();
        $params = array(
            'SendingDateTime'   =>  $waktu,
            'DestinationNumber' =>  $nohp,
            'TextDecoded'       =>  $pesan
        );
        $CI->db->insert('outbox',$params);
        
    }
    
    
    function longsms($nohp,$pesan,$waktu){
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
                    'DestinationNumber' =>$nohp,
                    'UDH'=>$udh,
                    'SendingDateTime'=>$waktu,
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
?>
