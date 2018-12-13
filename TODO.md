- implement phing
- Bundle logger
    - http://v-1538412492-907.test.yoctu.ovh:8001
- filter service
    - http://127.0.0.1:8000/api/dat/V-1529334206-686.dev?service=[esm]


    http://127.0.0.1:8000/api/cfg/service/no-notif-service



post   http://127.0.0.1:8000/api/cfg/service

{
    "name": "generic-service", //OR service-description
    "active_checks_enabled": "1",
    "passive_checks_enabled": "1",
    "parallelize_check": "1",
    "obsess_over_service": "1",
    "check_freshness": "0",
    "notifications_enabled": "1",
    "event_handler_enabled": "1",
    "flap_detection_enabled": "1",
    "failure_prediction_enabled": "1",
    "process_perf_data": "1",
    "retain_status_information": "1",
    "retain_nonstatus_information": "1",
    "notification_interval": "0",
    "is_volatile": "0",
    "check_period": "24x7",
    "normal_check_interval": "1",
    "retry_check_interval": "1",
    "max_check_attempts": "4",
    "notification_period": "24x7",
    "notification_options": [
        "w",
        "u",
        "c",
        "r"
    ],
    "contact_groups": "admins",
    "register": "0",
    "filePath": "/workspace/yoctu/nagios-api/samples/conf.d/generic-service_nagios2.cfg"
}


put   http://127.0.0.1:8000/api/cfg/service/no-notif-service

{
    "active_checks_enabled": "1",
    "max_check_attempts": "4",
    "notification_period": "24x7",
    "notification_options": [
        "w",
        "u",
        "c",
        "r"
    ]
}



delete   http://127.0.0.1:8000/api/cfg/service/no-notif-service






GET http://127.0.0.1:8000/api/cfg/hostgroup/haproxy/members
