<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$series_id = $common->get_cookie('series_id');
$match_id = $common->get_cookie('match_id');
$espn_url = "https://www.espncricinfo.com/series/" . $series_id . "/" . $match_id . "/live-cricket-score";

$scores = $common->get_scores_of_match($series_id, $match_id, 'all');

if(isset($_GET['expand']) && $_GET['expand'] == 1){ ?>
    <html lang="">
    <head>
        <title>Detailed Scorecard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <link rel="icon" type="image/x-icon" href="../cricket.ico">
        <style>
            .over_details {
                display: none;
                font-size: 0.9rem;
                color: #555;
            }
        </style>
        <script src="../scripts.js?version=<?php echo time(); ?>"></script>
        <script>
            let expanded_over_id = 0;
            setTimeout(() => {
                let detailed_scorecard_btn = document.getElementById('detailed-scorecard');
                detailed_scorecard_btn.innerHTML = 'Refresh';
                detailed_scorecard_btn.setAttribute('href', '../model_ui/detailed_scorecard.php');
            }, 1000);
            function toggleDetails(id) {
                let old = document.getElementById('row_over_' + expanded_over_id);
                let newRow = document.getElementById('row_over_' + id);
                if(old) {
                    old.style.display = 'none';
                    document.getElementById('row_' + Math.floor(expanded_over_id / 100) + "_" + (expanded_over_id % 100)).style.background = 'rgba(189, 183, 107, 0.6)';
                }
                document.getElementById('row_' + Math.floor(id / 100) + "_" + (id % 100)).style.background = 'wheat';
                newRow.style.display = 'table-cell';

                expanded_over_id = id;
            }
        </script>
    </head>
    <body onload="fill_header();fill_balance();fill_scorecard('<?php echo $series_id;?>', '<?php echo $match_id;?>');fill_footer()">
    <div id="header"></div>
    <div id="scorecard"></div>
    <div class="separator"></div>
    <div class="detailed-scorecard-container">
        <table>
            <thead>
            <tr>
                <td colspan="3">Detailed Score Per Over</td>
            </tr>
            <tr>
                <th><?php echo $scores[0]->teams[0]; ?></th>
                <th><?php echo $scores[0]->teams[1]; ?></th>
            </tr>
            </thead>
            <tbody>
                <?php
                for($i = 1; $i <= 20; $i++){
                    $score_1 = $common->get_score_at_over($scores, (100 + $i));
                    $score_2 = $common->get_score_at_over($scores, (200 + $i));
                    ?>
                    <tr>
                        <td id="row_1_<?php echo $i;?>" style="width: 45%" onclick="toggleDetails(<?php echo (100 + $i);?>)"><?php echo $common->get_score_string($score_1);?>
                        </td>
                        <td id="row_2_<?php echo $i;?>" style="width: 45%" onclick="toggleDetails(<?php echo (200 + $i);?>)">
                            <?php echo $common->get_score_string($score_2);?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="over_details" id="row_over_<?php echo (100 + $i);?>">
                            <div style="display: flex">
                                <?php
                                if(isset($score_1->this_over)){
                                    $width = count($score_1->this_over) < 4 ? 33.3 : 100 / count($score_1->this_over);
                                    for($j=0;$j<count($score_1->this_over);$j++) { ?>
                                        <div class="balls" style="align-content: center; width: <?php echo $width.'%'; ?>">
                                            <span><?php echo $score_1->this_over[$j];?></span>
                                        </div>
                                    <?php }
                                }
                                ?>
                            </div>
                            <div class="over_summary"><span><?php echo $score_1->this_over_summary?></span></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="over_details" id="row_over_<?php echo (200 + $i);?>">
                            <div style="display: flex">
                                <?php
                                if(isset($score_2->this_over)){
                                    $width = count($score_2->this_over) < 4 ? 33.3 : 100 / count($score_2->this_over);
                                    for($j=0;$j<count($score_2->this_over);$j++) { ?>
                                        <div class="balls" style="align-content: center; width: <?php echo $width.'%'; ?>">
                                            <span><?php echo $score_2->this_over[$j];?></span>
                                        </div>
                                    <?php }
                                }
                                ?>
                            </div>
                            <div class="over_summary"><span><?php echo $score_2->this_over_summary?></span></div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php }else{ ?>
    <html lang="">
    <head>
        <title>Detailed Scorecard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <link rel="icon" type="image/x-icon" href="../cricket.ico">
        <script src="../scripts.js?version=<?php echo time(); ?>"></script>
        <script>
            setTimeout(() => {
                let detailed_scorecard_btn = document.getElementById('detailed-scorecard');
                detailed_scorecard_btn.innerHTML = 'Expand';
                detailed_scorecard_btn.setAttribute('href', '../model_ui/detailed_scorecard.php?expand=1');
            }, 1000);
        </script>
    </head>
    <body onload="fill_header();fill_balance();fill_scorecard('<?php echo $series_id;?>', '<?php echo $match_id;?>');fill_footer()">
        <div id="header"></div>
        <div id="scorecard"></div>
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
<?php } ?>
        <a class="button" style="background: gray" href="<?php echo $espn_url;?>">Open Espn CricketInfo for more details</a>
        <div class="separator"></div>
        <div id="footer"></div>
    </body>
    </html>
