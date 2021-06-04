<?php
include "config.php";
include "config2.php";
session_start();

if (!isset($_SESSION['hislog']) && !isset($_SESSION['uislog']) && !isset($_SESSION['naver_access_token']) && !isset($_SESSION['kakao_access_token']) && !isset($_SESSION["mislog"])) {
    echo "<script>alert('로그인후 이용하실 수 있습니다.'); location.href='/hongber/index.php'</script>";
}

if (!isset($_SESSION['hislog'])) {
} else {
    $sp_name = $_SESSION['hname']; // 광고주 이름
    $sp_email = $_SESSION['hemail']; // 광고주 이메일*
    $sd = $_POST['sd']; // 시작날짜
    $ed = $_POST['ed']; // 마무리 날짜
    $add = $_POST['add']; // 광고주 소개
    $prod = $_POST['prod']; // 제품 소개
    $recper = $_POST['recper']; // 추가 인원
    $tool = $_POST['tool']; // 수단
    $etc = $_POST['etc']; // 수단 중 기타
    if ($tool == "기타") {
        $tool = $etc;
    }
    $openc = $_POST['openc']; // 카카오 오픈채팅*
}

if (is_uploaded_file($_FILES['file']['tmp_name']) && getimagesize($_FILES['file']['tmp_name']) != false) {
    $size = getimagesize($_FILES['file']['tmp_name']);
    $type = $size['mime'];
    $imgfp = fopen($_FILES['file']['tmp_name'], 'rb');
    $size = $size[3];
    $name = $_FILES['file']['name'];
    $maxsize = 99999999;

    if ($_FILES['file']['size'] < $maxsize) {
        $stmt = $dbcon->prepare("UPDATE spread SET introduce_add_img = ? WHERE spread_id = '$sp_email'");
        $stmt->bindParam(1, $imgfp, PDO::PARAM_LOB);
        $stmt->execute();

        $sql3 = "SELECT * FROM spread WHERE spread_id = '$sp_email'";
        $res3 = $connect->query($sql3);
        $row3 = $res3->fetch();
        $ti = 'data:image/png;base64,' . base64_encode($row3['introduce_add_img']);
        $stmt2 = $dbcon->prepare("UPDATE spread SET introduce_add_img = ? WHERE spread_id = '$sp_email'");
        $stmt2->bindParam(1, $ti, PDO::PARAM_LOB);
        $stmt2->execute();
    } else {
        echo "<script>alert('사진의 크기가 너무 큽니다.'); location.href='/hongber/index.php'</script>";
    }
}

if (is_uploaded_file($_FILES['file2']['tmp_name']) && getimagesize($_FILES['file2']['tmp_name']) != false) {
    $size = getimagesize($_FILES['file2']['tmp_name']);
    $type = $size['mime'];
    $imgfp = fopen($_FILES['file2']['tmp_name'], 'rb');
    $size = $size[3];
    $name = $_FILES['file2']['name'];
    $maxsize = 99999999;

    if ($_FILES['file2']['size'] < $maxsize) {
        $stmt = $dbcon->prepare("UPDATE spread SET introduce_prod_img = ? WHERE spread_id = '$sp_email'");
        $stmt->bindParam(1, $imgfp, PDO::PARAM_LOB);
        $stmt->execute();

        $sql3 = "SELECT * FROM spread WHERE spread_id = '$sp_email'";
        $res3 = $connect->query($sql3);
        $row3 = $res3->fetch();
        $ti = 'data:image/png;base64,' . base64_encode($row3['introduce_prod_img']);
        $stmt2 = $dbcon->prepare("UPDATE spread SET introduce_prod_img = ? WHERE spread_id = '$sp_email'");
        $stmt2->bindParam(1, $ti, PDO::PARAM_LOB);
        $stmt2->execute();
    } else {
        echo "<script>alert('사진의 크기가 너무 큽니다.'); location.href='/hongber/index.php'</script>";
    }
}
$sql = "UPDATE spread SET spread_sd = '$sd', spread_ed = '$ed', introduce_add = '$add', introduce_prod = '$prod', bespread_num = bespread_num + '$recper', spread_tool = '$tool', spread_oc = '$openc' WHERE spread_id = '$sp_email' AND spread_name = '$sp_name'";
$res = $connect->query($sql);

$sql4 = "INSERT INTO mying(mying_sd, mying_ed, mying_adv_email, mying_adv_name, mying_prod, mying_tool, mying_oc) ";
$sql4 .= "VALUES ('$sd', '$ed', '$sp_email', '$sp_name', '$prod', '$tool', '$openc')";
$connect->query($sql4);

$updsql = "SET @COUNT = 0;";
$updsql .= "UPDATE spread SET num = @COUNT:=@COUNT+1;";
$connect->query($updsql);

$updsql2 = "SET @COUNT = 0;";
$updsql2 .= "UPDATE mying SET num = @COUNT:=@COUNT+1;";
$connect->query($updsql2);

echo "<script>alert('변경사항이 적용되었습니다.'); location.href='/hongber/php/pickup.php'</script>";
