<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$data = new Data();

$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$match_id = $common->get_cookie("match_id");
$series_id = $common->get_cookie("series_id");
$ref_user_id = $common->get_cookie($data->get_auth_cookie_name());
if($series_id == null || $match_id == null || !$common->is_user_logged_in()){
    header("Location: ".$data->get_path());
}
$all_bids = $common->get_all_bids_by_user($series_id, $match_id, $ref_user_id);
$all_matches= $common->get_all_matches();

$bids_type_session = array();
$bids_type_winner = array();
$bids_type_special = array();
foreach($all_bids as $bids) {
    if($bids->type=='session'){
        $bids_type_session[] = $bids;
    }
    if ($bids->type=='winner'){
        $bids_type_winner[] = $bids;
    }
    if ($bids->type=='special'){
        $bids_type_special[] = $bids;
    }
}
usort($bids_type_session, function($a, $b) {
    return strcmp($a->timestamp, $b->timestamp) * -1;
});
usort($bids_type_winner, function($a, $b) {
    return strcmp($a->timestamp, $b->timestamp) * -1;
});
$match_name = $common->get_match_name_match_id($all_matches, $match_id, $series_id);
$scores = $common->get_scores_of_match($series_id, $match_id, 'all');
?>
<html lang="">
    <head>
        <title>All Bids</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <link rel="icon" type="image/x-icon" href="../cricket.ico">
        <script src="../scripts.js?version=<?php echo time(); ?>"></script>
    </head>
    <body onload="fill_header();fill_balance();fill_profile();fill_footer()">
    <div id="header"></div>
    <div id="profile"></div>
    <div class="separator"></div>
    <div class="detailed-scorecard-container">
        <table>
            <thead>
            <tr>
                <th>Overs</th>
                <th><?php echo $scores[0]->teams[0]; ?></th>
                <th><?php echo $scores[0]->teams[1]; ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>End of 6th Over</td>
                <td><?php echo $common->get_score_string($common->get_score_at_over($scores, 106));?></td>
                <td><?php echo $common->get_score_string($common->get_score_at_over($scores, 206));?></td>
            </tr>
            <tr>
                <td>End of 10th Over</td>
                <td><?php echo $common->get_score_string($common->get_score_at_over($scores, 110));?></td>
                <td><?php echo $common->get_score_string($common->get_score_at_over($scores, 210));?></td>
            </tr>
            <tr>
                <td>End of 16th Over</td>
                <td><?php echo $common->get_score_string($common->get_score_at_over($scores, 116));?></td>
                <td><?php echo $common->get_score_string($common->get_score_at_over($scores, 216));?></td>
            </tr>
            <tr>
                <td>End of 20th Over</td>
                <td><?php echo $common->get_score_string($common->get_score_at_over($scores, 120));?></td>
                <td><?php echo $common->get_score_string($common->get_score_at_over($scores, 220));?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="bid_container">
        <div class="bids_heading">Your Bids</div>
            <div class="bid_container">
                <div class ="title"><?php echo $match_name;?></div>
                <a class="button" style="font-weight: normal; margin-left: 12.5%; width: 75%" href="../matches/match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>">See Match Details</a>
                <div class ="sub-title">Bids For Session</div>
                <table>
                    <tbody>
                    <?php
                    $last_match_name = "NA";
                    $last_session = 'NA';
                    $start = 0;
                    foreach ($bids_type_session as $bids) :
                        $flag = "others";
                        if ($bids->status=="win")
                            $flag="win";
                        if($bids->status=="loss")
                            $flag="loss";
                        if($last_session != $bids->innings.'&'.$bids->session){
                             ?>
                            <tr><td colspan="4" class="session">
                                    <?php if($bids->session=='a'){
                                        echo "Innings ".$bids->innings." : Over (1 to 6)";}
                                    else if($bids->session=='b'){
                                        echo "Innings ".$bids->innings." : Over (7 to 10)";}
                                    else if($bids->session=='c'){
                                        echo "Innings ".$bids->innings." : Over (11 to 16)";}
                                    else if($bids->session=='d'){
                                        echo "Innings ".$bids->innings." : Over (17 to 20)";}
                                    ?>
                                </td>
                            </tr>
                        <?php }
                        $last_session = $bids->innings.'&'.$bids->session;
                        ?>
                        <tr class="row_<?php echo $flag;?>">
                            <td><?php echo $bids->timestamp?></td>
                            <?php if($common->is_user_an_agent() && property_exists($bids, 'bid_name')){ ?>
                                <td><?php echo $bids->bid_name; ?></td>
                            <?php } ?>
                            <td><?php if ($bids->slot == 'x')
                                        echo 'Runs '.$bids->runs_max." or Less";
                                      else if($bids->slot == 'y')
                                        echo "Runs ".$bids->runs_min." to ".$bids->runs_max;
                                      else if($bids->slot == 'z')
                                          echo "Runs ".$bids->runs_min." or More";
                                ?>
                            </td>
                            <?php $amount_string = '₹'.$bids->amount." && ₹".(int)($bids->amount*$bids->rate);
                                if($bids->status=="placed")
                                    $amount_string = str_replace('&&', "may return", $amount_string);
                                else if($bids->status=="cancel")
                                    $amount_string = '₹'.$bids->amount.' Cancelled';
                                else if($bids->status=="win")
                                    $amount_string = str_replace('&&', "became", $amount_string);
                                else if($bids->status=="loss")
                                    $amount_string = str_replace('&&', "failed to", $amount_string);
                                else
                                    $amount_string = "BID Status Invalid";
                                ?>
                            <td><?php echo $amount_string; ?></td>
                            <td><?php echo 'Room '.$bids->room; ?></td>
                            <td><?php echo $bids->status; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <div class="gap"></div>
            <div class="bid_container">
                <div class="sub-title">Bids For Match Winner</div>
                <table>
                    <tbody>
                    <?php
                    $last_match_name = "NA";
                    $last_session = 'NA';
                    $start = 0;
                    foreach ($bids_type_winner as $bids) :
                        $flag = "others";
                        if ($bids->status=="win")
                            $flag="win";
                        if($bids->status=="loss")
                            $flag="loss";
                        ?>
                        <tr class="row_<?php echo $flag;?>">
                            <td><?php echo $bids->timestamp?></td>
                            <td><?php $result=explode(" VS ", $common->get_match_name_match_id($all_matches, $bids->match_id, $bids->series_id));
                                if($bids->slot=="T1"){
                                            echo $result[0]." Wins";
                                }
                                        else{
                                            echo $result[1]." Wins";
                                        }?></td>
                            <?php $amount_string = '₹'.$bids->amount." && ₹".(int)($bids->amount*$bids->rate); ?>
                            <?php if($bids->status=="placed"){
                                    $amount_string = str_replace('&&', "may return", $amount_string);}
                                else if($bids->status=="cancel"){
                                    $amount_string = '₹'.$bids->amount.' Cancelled';}
                                else if($bids->status=="win"){
                                    $amount_string = str_replace('&&', "became", $amount_string);}
                                else if($bids->status=="loss"){
                                    $amount_string = str_replace('&&', "failed to", $amount_string);}
                                else
                                    $amount_string = "BID Status Invalid";
                                ?>
                            <td><?php echo $amount_string; ?></td>
                            <td><?php echo $bids->status; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="separator"></div>
        <div id="footer"></div>
    </body>
</html>

