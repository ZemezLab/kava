<?php
if (!function_exists('pipedrive_isDate')) {
    function pipedrive_isDate($value)
    {
        if (!$value) {
            return false;
        } else {
            $date = date_parse($value);
            if ($date['error_count'] == 0 && $date['warning_count'] == 0) {
                return checkdate($date['month'], $date['day'], $date['year']);
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('pipedrive_searchOrganizations')) {
    function pipedrive_searchOrganizations($api_token, $company_domain, $options) {
        //URL for Deal listing with your $company_domain and $api_token variables
        $url = 'https://'.$company_domain.'.pipedrive.com/api/v1/organizations/search?api_token=' . $api_token;
        $params = http_build_query($options);

        //GET request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'&'.$params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($output);

        return $result;
    }
}

if (!function_exists('pipedrive_getDealDetails')) {
    function pipedrive_getDealDetails($api_token, $company_domain, $id) {
        $url = 'https://'.$company_domain.'.pipedrive.com/api/v1/deals/'.$id.'?api_token='.$api_token;
//$params = http_build_query($options);

//GET request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($output);

        return $result;
    }
}
