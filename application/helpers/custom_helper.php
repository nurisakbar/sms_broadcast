<?php

$CI =& get_instance();

function menu_title($uri1,$uri2){
    $CI    = & get_instance();
    if(empty($uri2)){
        $uri = $uri1;
    }else{
        $uri = $uri1.'/'.$uri2;
    }
    
    $menu  = $CI->db->get_where('tabel_menu',array('link'=>$uri));
    if($menu->num_rows()>0){
        $row   = $menu->row_array();
        $judul = $row['judul_menu']; 
    }else{
        $judul = "Undefined";
    }
    return strtoupper($judul);
    //return $uri;
}
function cmb_dinamis($name,$table,$field,$pk,$selected){
    $ci = get_instance();
    $cmb = "<select name='$name' class='form-control'>";
    $data = $ci->db->get($table)->result();
    foreach ($data as $d){
        $cmb .="<option value='".$d->$pk."'";
        $cmb .= $selected==$d->$pk?" selected='selected'":'';
        $cmb .=">".  strtoupper($d->$field)."</option>";
    }
    $cmb .="</select>";
    return $cmb;  
}