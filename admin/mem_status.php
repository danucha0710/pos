<?php 
    if($row_member['ref_l_id'] == 0){
        echo "ผู้ดูแลระบบสูงสุด";
    }
    elseif($row_member['ref_l_id'] == 1){
        echo "ผู้ดูแลระบบ";
    }
    elseif($row_member['ref_l_id'] == 2){
        echo "พนักงาน";
    }
    elseif($row_member['ref_l_id'] == 3){
        echo "ลูกค้าทั่วไป";
    }
    elseif($row_member['ref_l_id'] == 4){
        echo "สมาชิก";
    } 
?>