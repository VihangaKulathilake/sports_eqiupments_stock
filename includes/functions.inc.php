<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function inputsEmptyProduct($pName,$pDes,$pCat,$pPrice,$pSup){
    $result=null;
    if(empty($pName) || empty($pDes) || empty($pCat) || empty($pPrice)|| empty($pSup)){
        $result=true;
    }else{
        $result=false;
    }
    return $result;
}

function registerProduct($connect,$pName,$pDes,$pImgName,$pCat,$pPrice,$pSup){
    $sql="INSERT INTO products (name,description,image_path,category,price,supplier_id) VALUES (?,?,?,?,?,?);";
    $stmt=mysqli_stmt_init($connect);

    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location:../insertProduct.php?error=statement failed");
        exit();
    }

    mysqli_stmt_bind_param($stmt,"ssssss",$pName,$pDes,$pImgName,$pCat,$pPrice,$pSup);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location:../insertProduct.php?error=none");
    exit();
}