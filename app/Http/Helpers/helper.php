<?php 

    use App\Models\ActivityLogs;

    if(!function_exists('create_activity_log'))
    {
        function create_activity_log($data)
        {
            $status = ActivityLogs::insert($data);
        }
    }

