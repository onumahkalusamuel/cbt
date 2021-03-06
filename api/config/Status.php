<?php
class Status {
    //HHTP Status Codes
    public $continue = 100;
    public $switching_protocols = 101;
    public $processing = 102;
    public $early_hints = 103;
    public $ok  = 200;
    public $created  = 201;
    public $accepted  = 202;
    public $non_authoritative  = 203;
    public $no_content  = 204;
    public $reset_content  = 205;
    public $partial_content = 206;
    public $multi_status = 207;
    public $already_reported = 208;
    public $im_used = 226;
    public $multiple_choices  = 300;
    public $moved_permanently  = 301;
    public $found = 302;
    public $see_other = 303;
    public $not_modified = 304;
    public $use_proxy = 305;
    public $temporary_redirect = 307;
    public $permanent_redirect = 308;
    public $bad_request = 400;
    public $unauthorized = 401;
    public $payment_required = 402;
    public $forbidden = 403;
    public $not_found = 404;
    public $method_not_allowed  = 405;
    public $not_acceptable  = 406;
    public $proxy_authentication_required  = 407;
    public $request_timeout  = 408;
    public $conflict  = 409;
    public $gone  = 410;
    public $length_required  = 411;
    public $precondition_failed  = 412;
    public $unsupported_media_type  = 415;
    public $range_not_satisfiable  = 416;
    public $expectation_failed  = 417;
    public $im_a_teapot = 418;
    public $misdirected_request  = 421;
    public $unprocessable_entity  = 422;
    public $failed_dependency  = 424;
    public $too_early = 425;
    public $upgrade_required  = 426;
    public $precondition_required  = 428;
    public $too_many_requests  = 429;
    public $request_header_fields_too_large  = 431;
    public $unavailable_for_legal_reasons  = 451;
    public $internal_server_error  = 500;
    public $not_implemented  = 501;
    public $bad_gateway  = 502;
    public $service_unavailable  = 503;
    public $gateway_timeout  = 504;
    public $http_version_not_supported  = 505;
    public $variant_also_negotiates = 506;
    public $insufficient_storage = 507;
    public $loop_detected = 508;
    public $not_extended = 510;
    public $network_authentication_required = 511;
    public $checkpoint = 103;
    public $this_is_fine = 218;
    public $page_expired = 419;
    public $token_required = 499;
    public $site_is_overloaded = 529;
    public $site_is_frozen = 530;
    public $login_timeout = 440;
    public $retry_with = 449;
    public $redirect = 451;
    public $no_response = 444;
    public $request_header_too_large = 494;
}