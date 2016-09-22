<?php
$connect = mysqli_connect("localhost", "root", "asdf", "praneeth");
require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'Classes/PHPExcel.php';
   if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

      $expensions= array("jpeg","jpg","png","txt","xls","xlsx");

      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose an appropriate file.";
      }

      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      /*"uploads/".*/
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"uploads/".$file_name);
         echo "Success";
        echo "uploads"."/".$file_name;
         $html="<table border='1'>";
         $objPHPExcel = PHPExcel_IOFactory::load("uploads/".$file_name);
        echo "bro";
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
         {
              $highestRow = $worksheet->getHighestRow();
              for ($row=2; $row<=$highestRow; $row++)
              {
                   $html.="<tr>";
                   $name = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
                   $email = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(1 , $row)->getValue());
                   echo $name;
                   echo "<br>";
                   echo $email;
                   $sql = "INSERT INTO `test`(`name`, `value`) VALUES ('$name','$email')";
                   mysqli_query($connect, $sql);
                   $html.= '<td>'.$name.'</td>';
                   $html .= '<td>'.$email.'</td>';
                   $html .= "</tr>";
              }
         }
         $html .= '</table>';
         echo '<br />Data Inserted';

      }else{
         print_r($errors);
      }
   }
?>
<html>
   <body>

      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="image" />
         <input type="submit"/>
      </form>

   </body>
</html>
