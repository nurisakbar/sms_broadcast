<?php

function jumlah_pesan_masuk() {
    $CI = & get_instance();
    $inbox = $CI->db->get('inbox')->num_rows();
    return $inbox;
}

function jumlah_pesan_keluar() {
    $CI = & get_instance();
    $outbox = $CI->db->get('sentitems')->num_rows();
    return $outbox;
}

function jumlah_pesan_proses_kirim() {
    $CI = & get_instance();
    $outbox = $CI->db->get('outbox')->num_rows();
    return $outbox;
}

function jumlah_phonebook() {
    $CI = & get_instance();
    $phonebook = $CI->db->get('pbk')->num_rows();
    return $phonebook;
}

function menu_title($uri1, $uri2) {
    $CI = & get_instance();
    if (empty($uri2)) {
        $uri = $uri1;
    } elseif($uri2=='add') {
        $uri=$uri1;
    }else{
        $uri = $uri1 . '/' . $uri2;
    }

    $menu = $CI->db->get_where('tabel_menu', array('link' => $uri));
    if ($menu->num_rows() > 0) {
        $row = $menu->row_array();
        $judul = $row['judul_menu'];
    } else {
        $judul = "Undefined";
    }
    return "<i class='".$row['icon']."'></i> ".strtoupper($judul);
}

function cmb_dinamis($name, $table, $field, $pk, $selected) {
    $ci = get_instance();
    $cmb = "<select name='$name' class='form-control'>";
    $data = $ci->db->get($table)->result();
    foreach ($data as $d) {
        $cmb .="<option value='" . $d->$pk . "'";
        $cmb .= $selected == $d->$pk ? " selected='selected'" : '';
        $cmb .=">" . strtoupper($d->$field) . "</option>";
    }
    $cmb .="</select>";
    return $cmb;
}

function kirim_sms($nohp, $pesan) {
    $CI = & get_instance();
    $jumsms = ceil(strlen($pesan) / 160);
    if ($jumsms == 1) {
        $CI->db->insert('outbox', array('DestinationNumber' => $nohp, 'TextDecoded' => $pesan));
    } else {
        $hitpecah = ceil(strlen($pesan) / 153);
        $pecah = str_split($pesan, 153);
        $data = $CI->db->query("SHOW TABLE STATUS LIKE 'outbox'")->row_array();
        $newID = $data['Auto_increment'];
        for ($i = 1; $i <= $jmlSMS; $i++) {
            // membuat UDH untuk setiap pecahan, sesuai urutannya
            $udh = "050003A7" . sprintf("%02s", $hitpecah) . sprintf("%02s", $i);
            // membaca text setiap pecahan
            $msg = $pecah[$i - 1];
            if ($i == 1) {
                // jika merupakan pecahan pertama, maka masukkan ke tabel OUTBOX
                $query = "INSERT INTO outbox (DestinationNumber, UDH, TextDecoded, ID, MultiPart, SenderID, CreatorID, Class)
		VALUES ('085649921023', '$udh', '$msg', '$newID', 'true', 'prolink', 'Gammu', '0')";
            } else {
                // jika bukan merupakan pecahan pertama, simpan ke tabel OUTBOX_MULTIPART
                $query = "INSERT INTO outbox_multipart(UDH, TextDecoded, ID, SequencePosition)
		VALUES ('$udh', '$msg', '$newID', '$i')";
            }
            // jalankan query
            $CI->db->query($query);
        }
    }
}
