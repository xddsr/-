<?php
include "config.php";
session_start();

if (!isset($_SESSION['hislog']) && !isset($_SESSION['uislog']) && !isset($_SESSION['naver_access_token']) && !isset($_SESSION['kakao_access_token']) && !isset($_SESSION["mislog"])) {
  echo "<script>alert('로그인후 이용하실 수 있습니다.'); location.href='/hongber/index.php'</script>";
}

$name = $_SESSION['hname'];
$email = $_SESSION['hemail'];
$sql = "SELECT * FROM spread WHERE spread_id = '$email' AND spread_name = '$name'";
$res = $connect->query($sql);
$row = $res->fetch();
?>
<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/hongber/css/reset.css">
  <link rel="stylesheet" href="/hongber/css/spread_modify.css">
  <link rel="icon" href="/hongber/favicon.ico" type="image/x-icon">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.0.min.js"></script>
  <title>Spread</title>
</head>

<body>
  <!-- 상단 바 -->
  <?php
  include "../header.php";
  ?>
  <div class="spread_comment">
    <p>Spreading Ads</p>
    <h3>함께하실 홍버분들 모십니다!</h3>
  </div>
  <div class="form_container">
    <div class="form_wrap">
      <form action="upspdb.php" enctype="multipart/form-data" method="POST">
        <div class="spread_date">
          <p>모집기간</p>
          <input type="date" name="sd" id="sd" required> ~ <input type="date" name="ed" id="ed" required>
        </div>
        <p class="select_p">모집인원 추가</p>
        <select name="recper" id="select_num">
          <option value="0">0명</option>
          <option value="10">10명</option>
          <option value="20">20명</option>
          <option value="30">30명</option>
          <option value="40">40명</option>
          <option value="50">50명</option>
          <option value="60">60명</option>
          <option value="70">70명</option>
          <option value="80">80명</option>
          <option value="90">90명</option>
          <option value="100">100명</option>
        </select><br><br>
        <div class="tool_wrap">
          <p class="tool_p">요구 수단</p>
          <input type="radio" name="tool" value="SNS">SNS
          <input type="radio" name="tool" value="YouTube">YouTube
          <input type="radio" name="tool" value="Web">Web
          <input type="radio" name="tool" value="App">App
          <input type="radio" name="tool" value="기타" class="another" id="another">기타
          <br>
          <input type="text" name="etc" class="etc" id="etc" placeholder="기타 선택시에 작성해주세요." disabled>
        </div>
        <div class="kakao_ad">
          <p>오픈채팅 주소</p>
          <input type="text" name="openc" id="openc" placeholder="https://open.kakao.com..." required>
        </div>
        <div class="upload_wrap">
          <p>광고주 소개</p>
          <div class="filebox">
            <img src="" id="addintro">
            <label for="file">업로드</label>
            <input type="file" id="file" name="file" accept="image/gif, image/jpeg, image/png">
          </div><br>
          <textarea type="text" name="add" id="addintro_t" placeholder="광고주 본인을 소개해주세요!" required class="intro" maxlength="300"></textarea>
        </div>
        <div class="upload_wrap2">
          <p>홍보할 제품 소개</p>
          <div class="filebox">
            <img src="" id="productintro">
            <label for="file2">업로드</label>
            <input type="file" id="file2" name="file2" accept="image/gif, image/jpeg, image/png">
          </div><br>
          <div class="introduce_prod">
            <textarea type="text" name="prod" id="prodintro_t" placeholder="홍보계획에 대한 정보를 적어주세요!" required class="intro" maxlength="300"></textarea>
          </div>
        </div>
        <input type="submit" value="수정하기" id="sbtn" class="sbtn">
      </form>
    </div>
  </div>
  <script>
    $('input:radio[name="tool"]:radio[value = "<?= $row['spread_tool'] ?>"]').attr("checked", true);
    let tool_l = $('input:radio[name="tool"]').length;
    let tool_v = [];

    for (let i = 0; i < tool_l - 1; i++) {
      tool_v[i] = document.getElementsByName('tool')[i].value
    }

    if (tool_v[0] != "<?= $row['spread_tool'] ?>" && tool_v[1] != "<?= $row['spread_tool'] ?>" && tool_v[2] != "<?= $row['spread_tool'] ?>" && tool_v[3] != "<?= $row['spread_tool'] ?>") {
      $('input:radio[name="tool"]:radio[value = "기타"]').attr("checked", true);
      document.getElementById('etc').value = "<?= $row['spread_tool'] ?>";
      $(".etc").attr("disabled", false);
      $(".etc").attr("required", true);
    }
  </script>
  <script>
    document.getElementById('openc').value = "<?= $row['spread_oc'] ?>";
    document.getElementById('addintro').src = "<?= $row['introduce_add_img'] ?>";
    document.getElementById('addintro_t').value = "<?= $row['introduce_add'] ?>";
    document.getElementById('productintro').src = "<?= $row['introduce_prod_img'] ?>";
    document.getElementById('prodintro_t').value = "<?= $row['introduce_prod'] ?>";
  </script>
  <script>
    $('#openc').blur(function() {
      const openc_url = $('#openc').val();
      const findString = "<?= 'https://open.kakao.com/' ?>";
      if (openc_url.indexOf(findString) == -1) {
        alert("정확한 카카오 오픈채팅 링크를 입력해주세요.");
        document.getElementById('openc').value = "";
      }
    });
  </script>
  <script>
    document.getElementById('sd').value = new Date().toISOString().substring(0, 10);
    document.getElementById('sd').min = new Date().toISOString().substring(0, 10);
    document.getElementById('ed').min = new Date().toISOString().substring(0, 10);
  </script>
  <script>
    $('#ed').blur(function() {
      if ($('#sd').val() > $('#ed').val()) {
        alert("뿌릴 기간을 제대로 설정해주세요.");
        document.getElementById('ed').value = "";
      }
    });
  </script>
  <script>
    $('#sd').blur(function() {
      if ($('#ed').val() < $('#sd').val()) {
        if ($('#ed').val() != "") {
          alert("뿌릴 기간을 제대로 설정해주세요.");
          document.getElementById('sd').value = new Date().toISOString().substring(0, 10);
        }
      }
    });
  </script>
  <script>
    function readURL(input) {
      if (input.files && input.files[0]) {
        let file = input.files;
        if (!/(.*?)\.(jpg|jpeg|png|gif|png)$/i.test(file[0].name)) {
          alert('jpg, jpeg, gif, png 파일만 선택해 주세요.');
        } else {
          let reader = new FileReader();
          reader.onload = function(e) {
            $('#addintro').attr('src', e.target.result);
          }
          reader.readAsDataURL(file[0]);
        }
      }
    }
    $('#file').change(function() {
      readURL(this);
    });
  </script>
  <script>
    function readURL2(input) {
      if (input.files && input.files[0]) {
        let file2 = input.files;
        if (!/(.*?)\.(jpg|jpeg|png|gif|png)$/i.test(file2[0].name)) {
          alert('jpg, jpeg, gif, png 파일만 선택해 주세요.');
        } else {
          let reader2 = new FileReader();
          reader2.onload = function(e2) {
            $('#productintro').attr('src', e2.target.result);
          }
          reader2.readAsDataURL(file2[0]);
        }
      }
    }
    $('#file2').change(function() {
      readURL2(this);
    });
  </script>
  <script>
    $(document).ready(function() {
      $("input:radio[name=tool]").click(function() {
        if ($("input[name=tool]:checked").val() == "기타") {
          $(".etc").attr("disabled", false);
          $(".etc").attr("required", true);
        } else if ($("input[name=tool]:checked").val() != "기타") {
          $(".etc").attr("disabled", true);
          $(".etc").attr("required", false);
        }
      });
    });
  </script>
  <?php
  include "home.php";
  ?>
</body>

</html>