<?php
if (!session_id()) session_start();

function flashMessage($type = null, $message = null) {
    if ($type && $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    } elseif (isset($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $message = $_SESSION['flash']['message'];

        switch ($type) {
            case 'success':
                $class = 'alert-success';
                break;
            case 'error':
                $class = 'alert-danger';
                break;
            case 'warning':
                $class = 'alert-warning';
                break;
            default:
                $class = 'alert-info';
        }

        echo "
        <div class='alert $class' id='flash-alert'>
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

        unset($_SESSION['flash']);
    }
}
?>
