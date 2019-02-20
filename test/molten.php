<?php
$c = curl_init("http://www.baidu.com");
curl_exec($c);
curl_close($c);
