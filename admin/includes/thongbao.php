<?php
function flashMessage($type, $message) {
    if ($type === 'success') {
        $alertClass = 'alert-success';
    } elseif ($type === 'error') {
        $alertClass = 'alert-danger';
    } elseif ($type === 'warning') {
        $alertClass = 'alert-warning';
    } else {
        $alertClass = 'alert-info';
    }

    echo "
    <div class='alert $alertClass' id='flash-alert'>
        $message
    </div>
    <script>
        setTimeout(() => {
            const alertBox = document.getElementById('flash-alert');
            if (alertBox) {
                alertBox.classList.add('fade');
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3000);
    </script>
    ";
}
?>
