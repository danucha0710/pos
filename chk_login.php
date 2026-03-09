<?php 
session_start();
  if(isset($_POST['mem_username'])){
    include("condb.php");
    $mem_username = mysqli_real_escape_string($condb,trim($_POST['mem_username']));
    $mem_password = mysqli_real_escape_string($condb,sha1(trim($_POST['mem_password'])));
    if($mem_username === '' || $mem_password === ''){
      echo '<script>';
      echo "alert(\"กรุณากรอกชื่อผู้ใช้งาน และรหัสผ่าน\");"; 
      echo "window.history.back()";
      echo '</script>';
    }
    else{
      $sql="SELECT * FROM tbl_member WHERE mem_username='".$mem_username."' AND mem_password='".$mem_password."' ";
      $result = mysqli_query($condb,$sql);
      //echo mysqli_num_rows($result);

      if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_array($result);
        $_SESSION["mem_id"] = $row["mem_id"];
        $_SESSION["mem_name"] = $row["mem_name"];
        $_SESSION["ref_l_id"] = $row["ref_l_id"];
        $_SESSION["mem_img"] = $row["mem_img"];
        //print_r($_SESSION);
        //var_dump($_SESSION);

        if($_SESSION["ref_l_id"]=="2" OR $_SESSION["ref_l_id"]=="1" OR $_SESSION["ref_l_id"]=="0"){
          Header("Location: admin/");
        }
        else{
          echo '<script>';
          echo "alert(\"ไม่มีสิทธิเข้าใช้งาน กรุณาติดต่อผู้ดูแลระบบ\");"; 
          echo "window.history.back()";
          echo '</script>';
          Header("Location: user/");
        }
      }
      else{
        echo '<script>';
        echo "alert(\"ชื่อผู้ใช้งาน หรือรหัสผ่านไม่ถูกต้อง !\");"; 
        echo "window.history.back()";
        echo '</script>';
        //Header("Location: login.php");
      }
	  mysqli_close($condb);
    }
  }
?>