<?php
session_start();

if(isset($_POST["submit"])){
    $pName=$_POST["pName"];
    $pDes=$_POST["pDes"];
    $pCat=$_POST["pCat"];
    $pPrice=$_POST["pPrice"];
    $pSup=$_POST["pSup"];
    $newFileName=null;
    //$createrId=$_SESSION["id"];

    require_once 'db.php';
    require_once 'functions.inc.php';

    $inputsEmptyProduct=inputsEmptyProduct($pName,$pDes,$pCat,$pPrice,$pSup);

    if($inputsEmptyProduct!==false){
        header("Location:../insertProduct.php?error=Inputs empty");
        exit();
    }

    if (isset($_FILES["pImg"]) && $_FILES["pImg"]["error"]!==4) {
        $pImg=$_FILES["pImg"];
        $fileName=$pImg["name"];
        $fileTmpName=$pImg["tmp_name"];
        $fileSize=$pImg["size"];
        $fileError=$pImg["error"];
        $fileType=$pImg["type"];
        

        $fileExt=strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
        $allowed=["jpg","jpeg","png","gif"];

        if (in_array($fileExt,$allowed)) {
            if ($fileError===0) {
                if ($fileSize<=5*1024*1024) {
                $newFileName=uniqid("",true).".".$fileExt;
                $uploadPath="../productImgs/";
                $fileDestination=$uploadPath.$newFileName;

                if(!is_dir($uploadPath)){
                    mkdir($uploadPath,0777,true);
                }

                move_uploaded_file($fileTmpName,$fileDestination);
                
            }else {
                header("Location:../signUp.php?error=File too large");
                exit();
            }

            }else {
                header("location: ../signUp.php?error=upload error");
                exit();
            }
        }else {
            header("Location:../signUp.php?error=Invalid file type");
            exit();
        }
    }


    registerProduct($connect,$pName,$pDes,$newFileName,$pCat,$pPrice,$pSup);

}else{
    header('Location:../signUp.php');
    exit();
}
