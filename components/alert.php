<?php
if (isset($success_msg)) {
    foreach ($success_msg as $msg) {
        echo '<script>Swal.fire({ icon: "success", title: "'.$msg.'" });</script>';
    }
}
if (isset($warning_msg)) {
    foreach ($warning_msg as $msg) {
        echo '<script>Swal.fire({ icon: "warning", title: "'.$msg.'" });</script>';
    }
}
if (isset($info_msg)) {
    foreach ($info_msg as $msg) {
        echo '<script>Swal.fire({ icon: "info", title: "'.$msg.'" });</script>';
    }
}
if (isset($error_msg)) {
    foreach ($error_msg as $msg) {
        echo '<script>Swal.fire({ icon: "error", title: "'.$msg.'" });</script>';
    }
}
?>
