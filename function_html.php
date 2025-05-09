<?php
function getInputHeader($classKey, $class, $header, $headerName)
{
    $html = '';
    if (empty($header)) {
        return $html;
    }
    foreach ($header as $key2 => $val2)
    {
        $html .= '<tr><td class="'.$class.'">' . $val2['title'] . ' (' . $headerName . ')</th>';
        $html .= '<td>'. $val2['name'] .'</td>';
        $html .= '<td>'. $val2['type'] .'</td>';
        $html .= '<td>'. $val2['help'] .'</td>';

        $html .= '<td>';
        $dataAsync = "";
        //var_dump($val2);
        if (isset($val2['async'])) {
            $dataAsync = "async_" . $val2['async'];
        }
        $c = sprintf('class="key_%s %s %s" data-key="%s" ', $key2, $classKey, $dataAsync, $key2);

        switch($val2['type']) 
        {
            case 'file':
            {
                $html .= '<input type="file" ' . $c .' placeholder="' . $val2['help'] . '" value="' . $val2['v'] .  '">';                
            } break;
            case 'file_multiple':
            {
                $html .= '<input type="file" ' . $c .' placeholder="' . $val2['help'] . '" value="' . $val2['v'] .  '" multiple>';                
            } break;            
            case 'text':
            {
                $html .= '<textarea ' . $c  . ' auto-height" rows="6" cols="">'. $val2['v'] .'</textarea>';                
            } break;
            default:
            {
                $html .= '<input type="text" ' . $c .' placeholder="' . $val2['help'] . '" value="' . $val2['v'] .  '">';                
            } break;
        }

        $html .= '</td></tr>';
    }
    echo $html;
}
function getInputList($classKey, $class, $header, $headerName)
{
    $html = '';
    if (empty($header)) {
        return $html;
    }
    foreach ($header as $key2 => $val2)
    {
        $html .= '<tr><td class="'.$class.'">' . $val2['title'] . ' (' . $headerName . ')</th>';
        $html .= '<td>'. $val2['name'] .'</td>';
        $html .= '<td>'. $val2['type'] .'</td>';
        $html .= '<td>'. $val2['help'] .'</td>';

        $html .= '<td>';

        $dataAsync = "";
        if (isset($val2['async'])) {
            $dataAsync = "async_" . $val2['async'];
        }
        $c = sprintf('class="key_%s %s %s" data-key="%s" ', $key2, $classKey, $dataAsync, $key2);

        switch($val2['type']) 
        {
            case 'file':
            {
                $html .= '<input type="file" name="' . $key2 . '" ' . $c .'" placeholder="' . $val2['help'] . '" value="' . $val2['v'] .  '">';                
            } break;
            case 'file_multiple':
            {
                $html .= '<input type="file" name="' . $key2 . '[]" ' . $c .'" placeholder="' . $val2['help'] . '" value="' . $val2['v'] .  '" multiple>';                
            } break;            
            case 'text':
            {
                $html .= '<textarea name="' . $key2 . '"' . $c  . ' auto-height" rows="6" cols="">'. $val2['v'] .'</textarea>';                
            } break;
            default:
            {
                $html .= '<input type="text" name="' . $key2 . '" ' . $c .' placeholder="' . $val2['help'] . '" value="' . $val2['v'] .  '">';                
            } break;
        }
        $html .= '</td></tr>';
    }
    echo $html;
}
// =======================================================================================
// laraveltest.php 
// 自動生成 laravel phpunit test

function getTestBasename($url, $text)
{
    // 使用parse_url()函數解析URL並獲取查詢部分
    $queryPart = parse_url($url, PHP_URL_QUERY);
    
    if (!empty($queryPart)) {
        // 使用parse_str()函數將查詢字符串解析為關聯數組
        parse_str($queryPart, $params);
        if (isset($params['filter'])) {
            return str_replace('NameTest', basename($params['filter']).'Test', $text);
        }
    }
    return str_replace('NameTest', basename($url).'Test', $text);
}

function deleteKeywordLine($text)
{
    $keyword = "參考資料可刪除";
    // 將文本按行分割成數組
    $lines = explode("\n", $text);

    // 查找包含關鍵字的行並刪除
    foreach ($lines as $index => $line) {
        if (strpos($line, $keyword) !== false) {
            unset($lines[$index]);
        }
    }

    // 將處理後的數組重新拼接成文本
    $result = implode("\n", $lines);
    return $result;
}

// 處理自動更換 queryParams
function changeLaravelQueryParams($queryParams, $text)
{
    $changeKey = "\r\n                'queryParams' => [\r\n                    'start_date' => '2023-07-18',\r\n                ],";
    // 如果空白傳送值清空
    if (empty($queryParams)) {
        $text = str_replace($changeKey, "", $text); // 傳送參數清空
        $text = str_replace(', $queryParams', "", $text); // test function 清空
        return $text;
    }
    $queryString = "\r\n                'queryParams' => [\r\n";
    foreach ($queryParams as $row) {
        $title = $row['title'];
        $val = $row['v'];
        switch ($row['type']) 
        {
            case 'int':
            {
                $query =  sprintf("                    '%s' => %d,\r\n", $title, $val);
            } break;
            default:
            {
                $query =  sprintf("                    '%s' => '%s',\r\n", $title, $val);
            } break;
        }
        $queryString .= $query;
    }
    $queryString .= "                ],";
    $text = str_replace($changeKey, $queryString, $text); // 傳送參數清空
    return $text;
}

// 處理自動更換 headerParams
function changeLaravelHeaderParams($headerParams, $text)
{
    $changeKey = "\r\n                'headers' => [\r\n                    'token' => 'token',\r\n                ],";
    // 如果空白傳送值清空
    if (empty($headerParams)) {
        $text = str_replace($changeKey, "", $text); // 傳送參數清空
        $text = str_replace('$headers, ', "", $text); // test function 清空
        $text = str_replace('withHeaders($headers)->', "", $text); // test function 清空
        return $text;
    }
    $headerString = "\r\n                'headers' => [\r\n";
    foreach ($headerParams as $row) {
        $title = $row['title'];
        $val = $row['v'];
        switch ($row['type']) 
        {
            case 'int':
            {
                $query =  sprintf("                    '%s' => %d,\r\n", $title, $val);
            } break;
            default:
            {
                $query =  sprintf("                    '%s' => '%s',\r\n", $title, $val);
            } break;
        }
        $headerString .= $query;
    }
    $headerString .= "                ],";
    $text = str_replace($changeKey, $headerString, $text); // 傳送參數清空
    return $text;
}

// 處理自動更換 uriParams
function changeLaravelUriParams($url, $uriParams, $text)
{
    $changeKey = "'/api/test";
    $urlPath = parse_url($url, PHP_URL_PATH);
    if (empty($uriParams)) {
        return str_replace($changeKey, "'".$urlPath, $text); // 傳送參數清空
    }
    $uriString = $urlPath;
    foreach ($uriParams as $row) {
        $title = $row['title'];
        $val = $row['v'];
        $query =  sprintf("/%s", $val);
        $uriString .= $query;
    }
    $text = str_replace($changeKey, $uriString, $text); // 傳送參數清空
    return $text;
}

function getLaravelTest($url, $tpl_delete, $queryVal)
{
    $text = file_get_contents ('tpl/include/laraveltest.php');
    if ($tpl_delete) {
        $text = deleteKeywordLine($text);
    }
    $text = changeLaravelQueryParams($queryVal['key'], $text);
    if (isset($queryVal['header'])) {
        $text = changeLaravelHeaderParams($queryVal['header'], $text);
    } else {
        $text = changeLaravelHeaderParams(array(), $text);
    }
    if (isset($queryVal['uri'])) {
        $text = changeLaravelUriParams($url, $queryVal['uri'], $text);
    } else {
        $text = changeLaravelUriParams($url, array(), $text);
    }
    return htmlspecialchars($text);
}

// 處理自動更換 addAsyncInput
function addAsyncInputJavascript($key)
{
    echo "
document.querySelectorAll('.async_" . $key . "').forEach(input => {
  input.addEventListener('input', (e) => {
    const newValue = e.target.value;
    document.querySelectorAll('.async_" . $key . "').forEach(otherInput => {
      if (otherInput !== e.target) {
        otherInput.value = newValue;
      }
    });
  });
});";

}

// 處理自動更換 addAsyncInput
function addAsyncInput($data)
{
   foreach ($data as $row) {
      addAsyncInputJavascript($row);
   }

}
// =======================================================================================
?>