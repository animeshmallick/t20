<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$data = new Data();

$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$match_id = $common->get_cookie("match_id");
$series_id = $common->get_cookie("series_id");
$ref_user_id = $common->get_cookie($data->get_auth_cookie_name());
if($series_id!=null && $match_id!=null){
    $all_bids = $common->get_all_bids($series_id, $match_id);
}
else if($ref_user_id!=null){
    $all_bids = $common->get_all_bids_by_user($ref_user_id);
}
else {
    header("Location: ".$data->get_path());
}
$user_bids = $common->get_bid_from_userid($all_bids, $ref_user_id);
$all_matches= $common->get_all_matches();
$match_name=$common->get_match_name_match_id($all_matches, $match_id, $series_id);
$result=explode(" VS ", $match_name);
$bids_type_session = array();
$bids_type_winner = array();
foreach($user_bids as $bids) {
    if($bids->type=='session'){
        $bids_type_session[]=$bids;
    }
    else{
        $bids_type_winner[]=$bids;
    }
}
usort($bids_type_session, function($a, $b) {
    return strcmp($a->series_id.'&&'.$a->match_id.'&&'.$a->innings.'&&'.$a->session,
        $b->series_id.'&&'.$b->match_id.'&&'.$b->innings.'&&'.$a->session);
});
usort($bids_type_winner, function($a, $b) {
    return strcmp($a->series_id.'&&'.$a->match_id, $b->series_id.'&&'.$b->match_id);
});

?>
<html lang="">
    <head>
        <title>All Bids</title>
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <script src="../scripts.js"></script>
        <link rel="stylesheet" href="../styles/style.css">
    </head>
    <body onload="fill_header(); fill_profile(); fill_controls();fill_footer()">
    <div id="header"></div>
    <div id="profile"></div>
    <div class="separator"></div>
        <div class="bid_container">
        <div class="bids_heading">ALL BIDS</div>
            <div class="bid_container">
                <div class ="title">BIDS-SESSION</div>
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
                        $match_name = $common->get_match_name_match_id($all_matches, $bids->match_id, $bids->series_id);
                        if ($last_match_name != $match_name){
                            $last_match_name = $match_name; ?>
                            <tr><td colspan="3" style="border: 0; padding-top: <?php echo $start; $start=2;?>rem"></td></tr>
                            <tr><td colspan="3" class="match_name"><?php echo $match_name; ?></td></tr>
                        <?php }
                        if($last_session != $bids->innings.'&'.$bids->session){
                            $last_session = $bids->innings.'&'.$bids->session; ?>
                            <tr><td colspan="3" style="border: 0; padding-top: 0.5rem"></td></tr>
                            <tr><td colspan="3" class="session">
                                    <?php if($bids->session=='a'){
                                        echo "Innings ".$bids->innings." : Over (1 to 6)";}
                                    else if($bids->session=='b'){
                                        echo "Innings ".$bids->innings." : Over (7 to 10)";}
                                    else if($bids->session=='c'){
                                        echo "Innings ".$bids->innings." : Over (11 to 16)";}
                                    else if($bids->session=='d'){
                                        echo "Innings ".$bids->innings." : Over (17 to 20)";}
                                    ?>
                                </td></tr>
                        <?php }
                        ?>
                        <tr class="row_<?php echo $flag;?>">
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
                            <td><?php echo $bids->status; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <div class="gap"></div>
            <div class="bid_container">
                <div class="title">BIDS-WINNER</div>
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
                        $match_name = $common->get_match_name_match_id($all_matches, $bids->match_id, $bids->series_id);
                        if ($last_match_name != $match_name){
                            $last_match_name = $match_name; ?>
                            <tr><td colspan="3" style="border: 0; padding-top: <?php echo $start; $start=2;?>rem"></td></tr>
                            <tr><td colspan="3" class="match_name"><?php echo $match_name; ?></td></tr>
                        <?php }
                        ?>
                        <tr class="row_<?php echo $flag;?>">
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
            <div class="gap"></div>
        </div>
        <div class="separator"></div>
        <div id="main_controls"></div>
        <div id="footer"></div>
    </body>
</html>

