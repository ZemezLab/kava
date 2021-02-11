<?php
/**
 * Template Name: User Deals
 */

$user = wp_get_current_user();
if ( !(in_array( 'subscriber', (array) $user->roles ) ||  in_array( 'administrator', (array) $user->roles ) )) {
    wp_redirect(site_url());
}

$deals = [];
$apiToken = \TyTo\Config::getValue('pipedriveToken'); // TODO check if apiToken not empty (+pipedrive sender)
$companyDomain = \TyTo\Config::getValue('pipedriveCompany');
$client = new Pipedrive\Client(null, null, null, $apiToken); // First 3 parameters are for OAuth
$searchUser = $client->getPersons()->findPersonsByName(['term' => $user->user_email, 'searchByEmail' => 1]); // TODO check if email not empty (+pipedrive sender)
if ($searchUser->success == 1 && !empty($searchUser->data)) {
    $person_id = $searchUser->data[0]->id;
}
if ($person_id) {
    $options = ['person_id' => $person_id, 'term' => 'FIT'];
    $url = 'https://'.$companyDomain.'.pipedrive.com/api/v1/deals/search?api_token='.$apiToken;
    $params = http_build_query($options);

    //GET request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url.'&'.$params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);
    curl_close($ch);

    $searchUserDeals = json_decode($output);

    if ($searchUserDeals->success == 1 && !empty($searchUserDeals->data)) {
        $deals = $searchUserDeals->data->items;
    }
}
$date_format = get_option( 'date_format' );
$time_format = get_option( 'time_format' );
// stage (stageId), addTime
get_header();
wp_enqueue_style('data-tables','https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css');
wp_enqueue_script('data-tables', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js', ['jquery']);
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1><?php the_title() ?></h1>
            <table id="deals" class="display" style="width:100%; text-align: left">
                <thead>
                <tr>
                    <th>Reise</th>
                    <th>Status</th>
                    <th>Budget</th>
                </tr>
                </thead>
                <tbody>
            <?php foreach ($deals as $deal) { ?>
                <tr>
            <?php  $deal_data = pipedrive_getDealDetails($apiToken, $companyDomain, $deal->item->id);
                echo '<td>'.$deal->item->title.'</td>';
                echo '<td>'.$deal->item->stage->name.'</td>';
                echo '<td>'.$deal->item->value.' '.$deal->item->currency.'</td>';
                ?>
                </tr>
            <?php } ?>
                </tbody>
            </table>
            <script>
                jQuery(document).ready(function() {
                    jQuery('#deals').DataTable(
                        {
                            "language": {
                                "url": "dataTables.german.lang"
                            }
                        }
                    );
                } );
            </script>
        </div>
    </div>
</div>
<style>
    .site-content { padding-top: 150px }
</style>
<?php
get_footer();
