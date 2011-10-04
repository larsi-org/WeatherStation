<?php
    // Create some random text-encoded data for a line chart.
    header('content-type: image/png');

    $params = array(
        'DewPoint_C' => array(
            'Unit'   => 'C',
            'M'      => 1.0,
            'N'      => 0.0,
            'MinMax' => '0,40',
            'LabelY' => '|0|5|10|15|20|25|30|35|40',
            'GridY'  => '12.5',
            'Background' => 'c,ls,90,CCCCFF,0.45,FFFFFF,0.175,FFCCCC,0.375'
            /*
             *       cold   < 18
	     *  18 < normal < 25
             *  25 < hot
             */
        ),
        'DewPoint' => array(
            'Unit'   => 'F',
            'M'      => 1.8,
            'N'      => 32.0,
            'MinMax' => '30,100',
            'LabelY' => '|30|40|50|60|70|80|90|100',
            'GridY'  => '14.286',
            'Background' => 'c,ls,90,CCCCFF,0.5,FFFFFF,0.17,FFCCCC,0.33'
            /*
             *       cold   < 65
	     *  65 < normal < 77
             *  77 < hot
             */
        ),
        'LightTEMT6000' => array(
            'Unit'       => '%',
            'M'          => 1.0,
            'N'          => 0.0,
            'MinMax'     => '0,1023',
            'LabelY'     => '|0%|10%|20%|30%|40%|50%|60%|70%|80%|90%|100%',
            'GridY'      => '10',
            'Background' => 'c,lg,90,333333,0,FFFFFF,1'
        ),
        'Pressure' => array(
            'Unit'   => 'hPa (mbar)',
            'M'      => 0.01,
            'N'      => 0.0,
            'MinMax' => '950,1050',
            'LabelY' => '|950|960|970|980|990|1000|1010|1020|1030|1040|1050',
            'GridY'  => '10',
            'Background' => 'c,ls,90,BBBBFF,0.18,DDDDFF,0.2,FFFFFF,0.24,FFDDDD,0.2,FFBBBB,0.18'
            /*
             *        stormy   <  968
	     *  968 < rain     <  988
             *  988 < change   < 1012
             * 1012 < fair     < 1032
             * 1032 < very dry
             */
        ),
        'Pressure_inHg' => array(
            'Unit'   => 'inHg',
            'M'      => 2.953e-4,
            'N'      => 0.0,
            'MinMax' => '28,31',
            'LabelY' => '|28|28.5|29|29.5|30|30.5|31',
            'GridY'  => '16.666',
            'Background' => 'c,ls,90,BBBBFF,0.2,DDDDFF,0.2,FFFFFF,0.2,FFDDDD,0.2,FFBBBB,0.2'
            /*
             *        stormy   < 28.6
	     * 28.6 < rain     < 29.3
             * 29.2 < change   < 29.8
             * 29.8 < fair     < 30.4
             * 30.4 < very dry
             */
        ),
        'RelativeHumidity' => array(
            'Unit'   => '%',
            'M'      => 1.0,
            'N'      => 0.0,
            'MinMax' => '0,100',
            'LabelY' => '|0%|10%|20%|30%|40%|50%|60%|70%|80%|90%|100%',
            'GridY'  => '10',
            'Background' => 'c,ls,90,FFCCCC,0.4,FFFFFF,0.3,CCCCFF,0.3'
            /*
             *       dry       < 40
	     *  40 < normal    < 70
             *  70 < humid/wet
             */
        ),
        'Temperature_C' => array(
            'Unit'   => 'C',
            'M'      => 1.0,
            'N'      => 0.0,
            'MinMax' => '0,40',
            'LabelY' => '|0|5|10|15|20|25|30|35|40',
            'GridY'  => '12.5',
            'Background' => 'c,ls,90,CCCCFF,0.45,FFFFFF,0.175,FFCCCC,0.375'
            /*
             *       cold   < 18
	     *  18 < normal < 25
             *  25 < hot
             */
        ),
        'Temperature' => array(
            'Unit'   => 'F',
            'M'      => 1.8,
            'N'      => 32.0,
            'MinMax' => '30,100',
            'LabelY' => '|30|40|50|60|70|80|90|100',
            'GridY'  => '14.286',
            'Background' => 'c,ls,90,CCCCFF,0.5,FFFFFF,0.17,FFCCCC,0.33'
            /*
             *       cold   < 65
	     *  65 < normal < 77
             *  77 < hot
             */
        )
    );

    $url = 'http://chart.apis.google.com/chart?chid=' . md5(uniqid(rand(), true));

    $type = $_GET['Type'];
    $chd = 't:';
    $tmp = "|";

    $factor = $params[$type]['M'];
    $offset = $params[$type]['N'];

    require('website.mysql.inc');
    $mysqli = new mysqli($mysql_host, $mysql_username, $mysql_passwd, $mysql_dbname);
    if (mysqli_connect_errno()) {
        //printf("Can't connect to MySQL Server. Errorcode: %s\n", mysqli_connect_error());
        exit;
    }

    $log_query = "SELECT `DateTime`, `Value` FROM `datalogger` WHERE `Type`=\"$type\" AND `DateTime` BETWEEN \"{$_GET['Date']} 00:00:00\" AND \"{$_GET['Date']} 23:59:59\" ORDER BY `DateTime`;";
    if ($log_result = $mysqli->query($log_query)) {
        if ($log_result->num_rows == 0) {
            //print "<strong>log is empty!<strong>\n";
        } else {
            while ($log_row = $log_result->fetch_assoc()) {
                $dateTime = $log_row['DateTime'];
                $h = intval(substr($dateTime, 11, 2));
                $m = intval(substr($dateTime, 14, 2));
                $s = intval(substr($dateTime, 17, 2));
                $dataX = (($h * 60 + $m) * 60 + $s) / (24 * 60 * 60);
                $chd .= $dataX . ',';
                $tmp .= ($factor * $log_row['Value'] + $offset) . ',';
            }
            $log_result->close();
        }
    } else {
        //print "<strong>Log query failed!<strong>\n";
    }
    $mysqli->close();

  $chd = substr($chd, 0, -1).substr($tmp, 0, -1);

  // Add data, chart type, chart size, and scale to params.
  $chart = array(
    'cht'  => 's',
    'chs'  => '480x360',
    'chm'  => 'o,FF0000,0,-1,3',
    'chco' => 'FF0000',
    'chds' => '0,1,'.$params[$type]['MinMax'],
    'chxl' => '0:|00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|00|1:'.$params[$type]['LabelY'],
    //'chxr' => '0,0,100|1,20,40',
    'chxt' => 'x,y',
    'chg'  => '16.666,'.$params[$type]['GridY'],
    'chtt' => $type.' in '.$params[$type]['Unit'],
    'chf'  => $params[$type]['Background'],
    'chd'  => $chd
  );

  // Send the request, and print out the returned bytes.
  $context = stream_context_create(
    array('http' => array('method' => 'POST', 'content' => http_build_query($chart)))
  );
  fpassthru(fopen($url, 'rb', false, $context));
?>
