<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$data = new Data();

$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$match_id = $common->get_cookie("match_id");
$series_id = $common->get_cookie("series_id");
$ref_user_id = $common->get_cookie($data->get_auth_cookie_name());

$all_bids = $common->get_all_bids($series_id, $match_id);
//print_r($all_bids);
$user_bids = $common->get_bid_from_userid($all_bids, $ref_user_id);
$all_matches= $common->get_all_matches();
$match_name=$common->get_match_name_match_id($all_matches, $match_id, $series_id);
$result=explode(" VS ", $match_name);
//print_r($result[0]);

//print_r($all_matches);
//print_r($user_bids);
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
//print_r($bids_type_session);
//print_r($bids_type_winner);
//print_r($user_bids);



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
    <body onload="fill_header();fill_controls();fill_footer()">
        <div id="header"></div>
        <div class="bid_container">
        <div class="bids_heading">ALL BIDS</div>
            <div class="bid_container">
                <div class ="title">BIDS-SESSION</div>
                <table>
                    <thead>
                    <tr>
                        <th>REF ID</th>
                        <th>MATCH NAME</th>
                        <th>INNINGS</th>
                        <th>SESSION</th>
                        <th>AMOUNT</th>
                        <th>MIN RUN</th>
                        <th>MAX RUN</th>
                        <th>TYPE</th>
                        <th>EXPECTED AMOUNT</th>
                        <th>ACTUAL AMOUNT</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($bids_type_session as $bids) :
                        $flag = "others";
                        if ($bids->status=="win")
                            $flag="win";
                        if($bids->status=="loss")
                            $flag="loss";
                        ?>
                        <tr class="row_<?php echo $flag;?>">
                            <td><?php echo $bids->ref_id; ?></td>
                            <td><?php echo $common->get_match_name_match_id($all_matches, $bids->match_id, $bids->series_id) ?></td>
                            <td><?php echo $bids->innings; ?></td>
                            <td><?php if($bids->session=='a'){
                                        echo "1st to 6th overs";}
                                      else if($bids->session=='b'){
                                          echo "7th to 10th overs";}
                                      else if($bids->session=='c'){
                                          echo "11th to 16th overs";}
                                      else if($bids->session=='d'){
                                          echo "17th to 20th overs";}
                                      ?></td>
                            <td><?php echo $bids->amount; ?></td>
                            <td><?php echo $bids->runs_min; ?></td>
                            <td><?php echo $bids->runs_max; ?></td>
                            <td><?php echo $bids->type; ?></td>
                            <td><?php echo (int)($bids->amount*$bids->rate); ?></td>
                            <td><?php if($bids->status=="placed"){
                                    echo "--";}
                                else if($bids->status=="cancel"){
                                    echo "--";}
                                else if($bids->status=="win"){
                                    echo (int)($bids->amount*$bids->rate);
                                    $row_color="highlight_green";}
                                else if($bids->status=="loss"){
                                    echo "0";
                                    $row_color="highlight_red";}
                                ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <div class="gap"></div>
            <div class="bid_container">
                <div class="title">BIDS-WINNER</div>
                <table>
                    <thead>
                    <tr>
                        <th>REF ID</th>
                        <th>MATCH NAME</th>
                        <th>AMOUNT</th>
                        <th>TYPE</th>
                        <th>EXPECTED WINNER</th>
                        <th>EXPECTED AMOUNT</th>
                        <th>ACTUAL AMOUNT</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($bids_type_winner as $bids) :
                        $flag = "others";
                        if ($bids->status=="win")
                            $flag="win";
                        if($bids->status=="loss")
                            $flag="loss";
                        ?>
                        <tr class="row_<?php echo $flag;?>">
                            <td><?php echo $bids->ref_id; ?></td>
                            <td><?php echo $common->get_match_name_match_id($all_matches, $bids->match_id, $bids->series_id) ?></td>
                            <td><?php echo $bids->amount; ?></td>
                            <td><?php echo $bids->type; ?></td>
                            <td><?php if($bids->slot=="T1"){
                                            echo $result[0];
                                }
                                        else{
                                            echo $result[1];
                                        }?></td>
                            <td><?php echo (int)($bids->amount*$bids->rate); ?></td>
                            <td><?php if($bids->status=="placed"){
                                    echo "--";}
                                else if($bids->status=="cancel"){
                                    echo "--";}
                                else if($bids->status=="win"){
                                    echo (int)($bids->amount*$bids->rate);
                                    }
                                else if($bids->status=="loss"){
                                    echo "0";
                                    }
                                ?></td>
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




