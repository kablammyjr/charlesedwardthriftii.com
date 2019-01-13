<?php

class PageSwitch {

public function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
}

public function changePage($url) {

  echo "<script>

    window.location = '$url'; 

  </script>";
}

public function changePageButtonById($buttonid, $url) {

echo "<script>

  var btn = document.getElementById($buttonid);
  btn.addEventListener('click', function() {
    document.location.href = $url;
  });

</script>";

}







}