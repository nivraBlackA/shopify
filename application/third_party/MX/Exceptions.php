<?php
// application/core/MY_Exceptions.php
class MX_Exceptions extends CI_Exceptions
{

    public $error_controller = 'error';
    public $error_method_404 = 'error_404';

    public function __construct()
    {
        parent::__construct();
    }

    public function show_404($page = '', $log_error = TRUE)
    {
        header("HTTP/1.1 404 Not Found");
        redirect("pagenotfound");
        exit(4); // EXIT_UNKNOWN_FILE
    }
}