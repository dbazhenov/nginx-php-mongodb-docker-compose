<?php

function fn_print_progress(&$app, $string, $line_break = false)
{

    if (php_sapi_name() == 'cli' && DEBUG == false) {
        return true; 
    }

    if ($line_break) {
        if (php_sapi_name() == 'cli') {
            $string = PHP_EOL . $string;
        } else {
            $string = '<br/>' . $string;
        } 
    }

    $microtime = hrtime(true) - $app['profiler']['start'];
    $microtime = $microtime/1e+6;

    $start = $app['report']['timer']['start'];
    $time = time() - $start;

    $string = $string . ' / Microtime: ' . $microtime . ', Time: '. $time; 

    echo $string;

    if (php_sapi_name() != 'cli') {
        @ob_end_flush();
        @flush();
    }

}

function fn_finish($app, $profiler = true)
{

    if ($profiler) {
        fn_profiler($app);
    }

    $app['report']['timer']['finish'] = date("m.d.y H:i:s", time());
    $app['report']['timer']['total'] = time() - $app['report']['timer']['start'];
    $app['report']['timer']['start'] = date("m.d.y H:i:s", $app['report']['timer']['start']);

    $microtime = hrtime(true) - $app['profiler']['start'];
    $microtime = $app['report']['timer']['microtime'] = $microtime/1e+6;

    if (php_sapi_name() == 'cli') {
        $report =   'Start ' . $app['report']['timer']['start']
                    . ' Finish: ' . $app['report']['timer']['finish']
                    . ' Time: ' . $app['report']['timer']['total'] 
                    . ' Microtime: ' . $microtime; 

        $report .= PHP_EOL . PHP_EOL;

        echo $report;
        exit;
    } else {
        dd($app['report']);
    }

}

function fn_profiler($app)
{

    $hrtime = hrtime(true) - $app['profiler']['start'];
    $hrtime = $hrtime/1e+6;

    $time = time() - $app['report']['timer']['start'];

    /* Currently used memory */
    $mem_usage = memory_get_usage();
   
    /* Peak memory usage */
    $mem_peak = memory_get_peak_usage();    

    if (php_sapi_name() == 'cli') {

        $print = PHP_EOL . 'Profiler: Microtime: ' . $hrtime . ', Secs: ' . $time
               . '. The script is now using: ' . round($mem_usage / 1024) . 'KB of memory.' 
               . ' Peak usage: ' . round($mem_peak / 1024) . 'KB of memory' . PHP_EOL . PHP_EOL;

    } else {
        $print = '<br>Profiler: Secs: <strong>' . $time . '</strong>, Microtime: <strong>' . $hrtime . '</strong><br>' .
            'The script is now using: <strong>' . round($mem_usage / 1024) . 'KB</strong> of memory.' .
            ' Peak usage: <strong>' . round($mem_peak / 1024) . 'KB</strong> of memory.<br>';

    }

    if (php_sapi_name() != 'cli') {
        @ob_end_flush();
        @flush();
    }

    echo $print;        
}

function fn_add_num(&$array, $key, $value = 1)
{
    if (!isset($array[$key])) {
        $array[$key] = $value;
    } else {
        $array[$key] += $value;
    }
}