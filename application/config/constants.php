<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
defined('VERSION')      OR define('VERSION', date("YmdH")); // highest automatically-assigned error code


//ONESIGNAL
defined('ONESIGNAL_URL')            OR define('ONESIGNAL_URL','https://onesignal.com/api/v1/notifications');
//LIVE
// defined('ONESIGNAL_REST_API')       OR define('ONESIGNAL_REST_API','Yjg1ZWI0OWUtMTFlYy00MjQ0LWJiZGYtZGZjZTRiYjlmZDc1');
// defined('ONESIGNAL_APP_ID')         OR define('ONESIGNAL_APP_ID','63969774-3636-4ea3-bd4e-108719adfd51');
// defined('ONESIGNAL_TEMPLATE_ID')    OR define('ONESIGNAL_TEMPLATE_ID','71d9315d-3889-49db-8d65-6e664d403d50');
//SANDBOX
defined('ONESIGNAL_REST_API')       OR define('ONESIGNAL_REST_API','NjE4MDlmMDktN2ZhOS00YWY1LThkODQtNDI5Y2FjNTE0MzYx');
defined('ONESIGNAL_APP_ID')         OR define('ONESIGNAL_APP_ID','192c95ca-e799-443a-bf1b-1a91e94b6b1e');
defined('ONESIGNAL_TEMPLATE_ID')    OR define('ONESIGNAL_TEMPLATE_ID','bc472d5a-ffae-485b-b1c3-630cbdd3cc8d');

defined('ST_URL')           OR define('ST_URL','https://stg.biztreats.ph:9443/');

defined('ST_CLIENT_CODE')      OR define('ST_CLIENT_CODE',"blackarrow");
defined('ST_API_PASSWORD')      OR define('ST_API_PASSWORD',"eXpRess0bLaCkArrOw");

defined('ST_CLIENT_ID')      OR define('ST_CLIENT_ID',"5566952a-e6c4-440e-a4f3-a887b5a74d31");
defined('ST_CLIENT_SECRET')      OR define('ST_CLIENT_SECRET',"qkII07PMx18IKLqHCATvMBLEeVDRAvoT");

defined('ST_AUTH_KEY')      OR define('ST_AUTH_KEY',base64_encode(ST_CLIENT_ID.":".ST_CLIENT_SECRET));