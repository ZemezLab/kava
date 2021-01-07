<?php

add_filter('wp_ajax_pafe_ajax_form_builder', 'tyto_pipedrive_send_data');
add_filter('wp_ajax_nopriv_pafe_ajax_form_builder', 'tyto_pipedrive_send_data');


//$apiToken = '5e75970cf5b424b98736620a2f5d47ce11cee236';
//$client = new Pipedrive\Client(null, null, null, $apiToken); // First 3 parameters are for OAuth2
//$response = $client->getPersons()->findPersonsByName(['term' => '1freedrich.paul@gmail.com', 'searchByEmail' => 1]);
//print_r($response);
//echo empty($response->data);

function tyto_pipedrive_send_data()
{
    $form_data = json_decode(stripslashes(html_entity_decode($_POST["fields"])), true);

    $apiToken = \TyTo\Config::getValue('pipedriveToken');
    $companyDomain = \TyTo\Config::getValue('pipedriveCompany');
    $client = new Pipedrive\Client(null, null, null, $apiToken); // First 3 parameters are for OAuth2

    $newDealFields = $newPersonFields = $newOrgFields = [];
    $allDealFields = $allPersonFields = $allOrgFields = [];
    $deal_customer_name = $customerrequests_key = $customer_requests = $firstname_key = $lastname_key = $contact_name = $org_id = '';

    foreach ($form_data as $val) {
        $key = $val['name'];
        // collect Deal fields
        if (strpos($key, 'deal_') === 0) {
            if (empty($allDealFields)) {
                $response = $client->getDealFields()->getAllDealFields([]);
                foreach ($response->data as $v) {
                    if ($v->name == 'Kundenwünsche') $customerrequests_key = $v->key;
                    if ($v->name == 'Vorname (Endkunde)') $firstname_key = $v->key;
                    if ($v->name == 'Nachname (Endkunde)') $lastname_key = $v->key;
                    if ($v->name == 'Reisezeitraum') $date_key = $v->key;

                    $allDealFields[$v->key] = $v;
                }
            }
            $_key = str_replace('deal_', '', $key);
            if ($_key == $firstname_key) $deal_customer_name .= $val['value'];
            if ($_key == $lastname_key) $deal_customer_name .= ' ' . $val['value'];
            if ($_key == $date_key) $deal_customer_name .= ' '.$val['value'];

            if ($allDealFields[$_key]) {
                if ($allDealFields[$_key]->fieldType == 'daterange' &&
                    strpos($val['value'], ' bis ') !== false
                    && $val['type'] == 'text') {
                    $dates = explode(' bis ', $val['value']);
                    if (count($dates) == 2 && pipedrive_isDate($dates[0]) && pipedrive_isDate($dates[1])) {
                        $newDealFields[$_key] = date('Y-m-dTH:i:s', strtotime($dates[0]));
                        $newDealFields[$_key . '_until'] = date('Y-m-dTH:i:s', strtotime($dates[1]));
                    }
                } else if ($allDealFields[$_key]->fieldType == 'date' && pipedrive_isDate($val['value'])) {
                    $newDealFields[$_key] = date('Y-m-dTH:i:s', strtotime($val['value']));
                } else {
                    $newDealFields[$_key] = $val['value'];
                }
            } else {
                if ($customerrequests_key) {
                    $newDealFields[$customerrequests_key] .= "\n" . $val['value'];
                }
            }
        }
        // collect Organization fields
        if (strpos($key, 'org_') === 0) {
            if (empty($allOrgFields)) {
                $response = $client->getOrganizationFields()->getAllOrganizationFields();
                foreach ($response->data as $v) $allOrgFields[$v->key] = $v;
            }
            $_key = str_replace('org_', '', $key);
            if ($allOrgFields[$_key]) {
                if ($allOrgFields[$_key]->fieldType == 'date' && pipedrive_isDate($val['value']))
                    $newOrgFields[$_key] = date('Y-m-dTH:i:s', strtotime($val['value']));
                else
                    $newOrgFields[$_key] = $val['value'];
            }
        }
        // collect Person fields
        if (strpos($key, 'contact_') === 0) {
            if (empty($allPersonFields)) {
                $response = $client->getPersonFields()->getAllPersonFields();
                foreach ($response->data as $v) $allPersonFields[$v->key] = $v;
            }
            $_key = str_replace('contact_', '', $key);
            if ($_key == 'first_name') $contact_name .= $val['value'];
            if ($_key == 'last_name') $contact_name .= ' ' . $val['value'];
            if ($_key == 'email') $email = $val['value'];
            if ($allPersonFields[$_key]) {
                if ($allPersonFields[$_key]->fieldType == 'date' && pipedrive_isDate($val['value']))
                    $newPersonFields[$_key] = date('Y-m-dTH:i:s', strtotime($val['value']));
                else
                    $newPersonFields[$_key] = $val['value'];
            }
        }
    }

    $wp_user = wp_get_current_user();
    if (empty($email) && $wp_user !== null)
        $email = $wp_user->user_email;
    if (!empty($email)) {
        $response = $client->getPersons()->findPersonsByName(['term' => $email, 'searchByEmail' => 1]);
        if ($response->success == 1 && !empty($response->data)) {
            $person_id = $response->data[0]->id;
            if ($response->data[0]->orgId) $org_id = $response->data[0]->orgId;
        }
    }
    if (empty(trim($contact_name)) && $wp_user !== null) {
        $contact_name = $wp_user->first_name.' '.$wp_user->last_name;
    }
    $newPersonFields['email'] = $email;
    $newPersonFields['name'] = $contact_name;
    if (empty($deal_customer_name)) $deal_customer_name = $contact_name;

    if (!empty($newOrgFields) && $newOrgFields['name'] && empty($org_id)) {
//        $search_org = $client->getOrganizations()->findOrganizationsByName(['term' => $newOrgFields['name']]);
        $search_org = pipedrive_searchOrganizations($apiToken, $companyDomain, ['term' => $newOrgFields['name'], 'exact_match' => 1]);
        // if Organization doesn't exists
        if ($search_org->success == 1 && empty($search_org->data->items)) {
            //create new Organization
            $new_org = $client->getOrganizations()->addAnOrganization($newOrgFields);
            if ($new_org->success == 1 && $new_org->data->id) {
                $org_id = $new_org->data->id;
            }
        } else {
            $org_id = $search_org->data->items[0]->item->id;
        }
    }

    $persons = $client->getPersons();
    if (!empty($newPersonFields) && $contact_name && $email) {
        if (empty($person_id)) {
            if ($org_id) $newPersonFields['org_id'] = $org_id;
            $persons->addAPerson($newPersonFields);
        } else {
            if ($org_id)
                $persons->updateAPerson([
                    'id' => $person_id,
                    'orgId' => $org_id
                ]);
        }
    }


    if (empty($deal_customer_name) && $wp_user !== null)
        $deal_customer_name = $wp_user->first_name.' '.$wp_user->last_name;
    if (!empty($deal_customer_name)) {
        $newDealFields['title'] = 'FIT ' . $deal_customer_name;

        sleep(5);
        $response = $client->getPersons()->findPersonsByName(['term' => $email, 'searchByEmail' => 1]);
        if ($response->success == 1 && !empty($response->data)) {
            $person_id = $response->data[0]->id;
            if ($response->data[0]->orgId) $org_id = $response->data[0]->orgId;
        }
        if ($person_id) $newDealFields['person_id'] = $person_id;
        if ($org_id) $newDealFields['org_id'] = $org_id;
    }

    if (!empty($newDealFields)) {
        $deals = $client->getDeals();
        $deals->addADeal($newDealFields);
    }


    // TODO low: save after user registration
    // TODO private page with table of deals for user

    //TODO don't create new org for existing contact, but create a deal
    //TODO make deal-contact relation

    wp_die();
}

//$options = ['term' => 'AA', 'exact_match' => 1];
//$url = 'https://adfa.pipedrive.com/api/v1/organizations/search?api_token=2d15fa4265308e8c322da51ae3aa73ee41456614';
//$params = http_build_query($options);
//
////GET request
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, $url.'&'.$params);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//$output = curl_exec($ch);
//curl_close($ch);
//
//$result = json_decode($output);
//
//print_r($result);
//echo $result->data;