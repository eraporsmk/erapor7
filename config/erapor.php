<?php

return [
    'api_url' => 'http://sync.erapor-smk.net/api/v7/dapodik/',
    //'api_url' => 'http://sync-erapor.test/api/v7/dapodik/',
    'rapor_pts' => env('APP_PTS', FALSE),
    'bentuk_pendidikan' => explode(',', env('APP_JENJANG', 15)),
];
