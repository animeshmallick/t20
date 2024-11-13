<?php
include "model/Score.php";
class Common {
    function get_auth_cookie($name) {
        if (isset($_COOKIE[$name]))
            return $_COOKIE[$name];
        return 0;
    }

    function over_started($connection, $match_id, $innings, $over): bool
    {
        $ball_count = ($over - 1) * 6;
        $sql = "Select `id` from `scorecard` where `match_id`='$match_id' and `innings`=$innings and `ball_id`>$ball_count";
        return $connection->query($sql)->num_rows > 0;
    }

    function get_actual_run($connection, $match_id, $innings, $over): int
    {
        $ball_count = $over * 6;
        $sql = "Select * from `scorecard` where `match_id`='$match_id' and `innings`=$innings and `ball_id`=$ball_count";
        $result = $connection->query($sql);
        if($result->num_rows == 1)
            $x = (int)($result->fetch_assoc()['runs']);
        else
            $x = -1;

        $ball_count = ($over - 1) * 6;
        $sql = "Select * from `scorecard` where `match_id`='$match_id' and `innings`=$innings and `ball_id`=$ball_count";
        $result = $connection->query($sql);
        if($result->num_rows == 1)
            $y = (int)($result->fetch_assoc()['runs']);
        else
            $y = -1;

        if($x == -1 || $y == -1)
            return -1;
        return $x - $y;
    }

    function get_expected_run($connection, $match_id, $innings, $over_id): float
    {
        if($over_id <= 0)
            return -1;
        $ball_id = $over_id * 6;
        $sql = "Select * from `scorecard` where `match_id`='$match_id' and `innings`=$innings and `ball_id`=$ball_id";
        $run = -1;
        $result = $connection->query($sql);
        if ($result->num_rows == 1)
            $run = (int)($result->fetch_assoc()['runs']);
        
        if ($run == -1)
            return -1;
        return (floatval($run) / $over_id);
    }

    function get_expected_runs_from_over($connection, $match_id, $innings, $over_id): float
    {
        if($over_id < 0)
            return -1;
        if($over_id > 0 && $over_id <= 2)
            return 8.15;
        $expected_run = $this->get_expected_run($connection, $match_id, $innings, ($over_id - 2));
        if ($expected_run == -1)
            return $this->get_expected_run($connection, $match_id, $innings, ($over_id - 3));
        if($expected_run <= 0)
            return -1;
        return $expected_run;
    }

    function get_rate($connection, $max_affordable_loss, $match_id, $innings, $over_id, $amount, $slot, $status, $max_rate): float {
        $money_collected = $this->get_collected_amount($connection, $match_id, $innings, $over_id, $status);
        $money_distributed = $this->get_distributed_amount($connection, $match_id, $innings, $over_id, $slot, $status);
        $remaining = $money_collected - $money_distributed;
        if ($remaining >= $amount)
            $rate = ($remaining + $amount) / $amount;
        else
            $rate = ($remaining + $amount + min($max_affordable_loss, ($amount - $remaining))) / $amount;

        return min($rate, $max_rate);
    }

    function get_collected_amount($connection, $match_id, $innings, $over_id, $status): int
    {
        $sql = "Select `amount` from `bid_table` where `match_id`='$match_id' and `innings`=$innings and `over_id`=$over_id and `status`='$status'";
        $result = $connection->query($sql);
        $amount = 0;
        while($row = $result->fetch_assoc())
            $amount += (int)$row['amount'];
        return $amount;
    }
    function get_distributed_amount($connection, $match_id, $innings, $over_id, $slot, $status): float
    {
        $sql = "Select * from `bid_table` where `match_id`='$match_id' and `innings`=$innings and `over_id`=$over_id and `slot`='$slot' and `status`='$status'";
        $result = $connection->query($sql);
        $amount = 0;
        while($row = $result->fetch_assoc())
            $amount += ((int)$row['amount'])*(floatval($row['rate']));
        return $amount;
    }

    function get_wallet_balance($connection, $ref_id): float {
        $sql = "Select `wallet` from `users` where `ref_id`=$ref_id";
        $result = $connection->query($sql);
        if ($result->num_rows == 1)
            return $result->fetch_assoc()['wallet'];
        return -1;
    }

    function recharge_user_wallet($connection, $ref_id, $amount, $recharge_admin, $type, $tran_id): void
    {
        $current_amount = $this->get_current_amount($connection, $ref_id);
        $final_amount = $current_amount + $amount;
        $sql = "Select * from `recharge` where `tran_id`=$tran_id";
        if ($connection->query($sql)->num_rows == 0) {
            $sql = "UPDATE `users` SET `wallet`=$final_amount WHERE `ref_id`='$ref_id'";
            if ($connection->query($sql) === True) {
                $this->update_recharge_table($connection, $recharge_admin, $ref_id, $type, $amount, $tran_id);
            } else
                echo "Failed to update wallet balance.";
        } else {
            echo "Duplicate Recharge";
        }
    }
    function give_referral_bonus($connection, $ref_id, $bonus, $type, $tran_id): void
    {
        $parent_ref_id = $this->get_parent_ref_id($connection, $ref_id);
        if ($this->is_eligible_for_bonus($connection, $ref_id) && (int)$parent_ref_id > 0){
            //For user
            $this->recharge_user_wallet($connection, $parent_ref_id, $bonus, $ref_id, $type, $tran_id);
            //For parent
            $this->recharge_user_wallet($connection, $ref_id, $bonus, $parent_ref_id, $type, $tran_id);
        }
    }
    private function update_recharge_table($connection, $recharge_admin, $ref_id, $type, $amount, $tran_id): void
    {
        $sql = "INSERT INTO `recharge` (tran_id, admin, ref_id, amount, type) VALUES ('$tran_id', '$recharge_admin', '$ref_id', '$amount', '$type')";
        if (!$connection->query($sql) === TRUE) {
            echo "Failed to update recharge table.";
        }
    }
    function is_eligible_for_bonus($connection, $ref_id): bool
    {
        $sql = "Select `tran_id` from `recharge` where `ref_id`='$ref_id'";
        return $connection->query($sql)->num_rows == 1;
    }
    function get_unique_tran_id($connection): int
    {
        $tran_id = mt_rand(10000000, 99999999);
        $sql = "Select `tran_id` from `recharge` where `tran_id`='$tran_id'";
        while($connection->query($sql)->num_rows != 0){
            $tran_id = mt_rand(10000000, 99999999);
        }
        return $tran_id;
    }
    function get_ref_id_from_phone($connection, $phone, $status): int {
        $sql = "Select `ref_id` from `users` where `phone`='$phone' and `status`='$status'";
        $result = $connection->query($sql);
        if ($result->num_rows == 1) {
            return $result->fetch_assoc()['ref_id'];
        } else {
            return -1;
        }
    }
    function get_parent_ref_id($connection, $ref_id): int {
        $sql = "Select `parent_ref_id` from `users` where `ref_id`='$ref_id'";
        $result = $connection->query($sql);
        if ($result->num_rows == 1) {
            return $result->fetch_assoc()['parent_ref_id'];
        } else {
            return -1;
        }
    }
    function get_current_amount($connection, $ref_id) {
        $sql = "Select `wallet` from `users` where `ref_id`='$ref_id'";
        $result = $connection->query($sql);
        if ($result->num_rows == 1) {
            return $result->fetch_assoc()['wallet'];
        } else {
            return -1;
        }
    }

    function get_user_name_from_ref_id($connection, $ref_id) {
        $sql = "Select `fname` from `users` where `ref_id`=$ref_id";
        $result = $connection->query($sql);
        if($result->num_rows == 1)
            return $result->fetch_assoc()['fname'];
        return "Unknown User";
    }
    function get_match_name($connection, $match_id) {
        $sql = "Select `match_title` from `matches` where `match_id`='$match_id'";
        $result = $connection->query($sql);
        if ($result->num_rows == 1)
            return $result->fetch_assoc()['match_title'];
        return "Not a valid match";
    }
    function deduct_wallet_balance($connection, $ref_id, $amount, $bid_id, $tran_id): void
    {
        $amount = $amount * -1.0;
        $recharge_admin = "bid_".$bid_id;
        $this->recharge_user_wallet($connection, $ref_id, $amount, $recharge_admin, "bid_placed", $tran_id);
    }
    function rate_convertor($rate, $base_amount): string
    {
        return (int)((($rate * $base_amount) / 10) * 10);
    }

    public function is_group_over_open(mysqli $get_connection, int $match_id, int $int, int $int1): bool
    {
        return true;
    }

    public function get_expected_runs_from_over_slots(mysqli $connection, mixed $match_id, mixed $innings, int $over_start, int $over_end, $expected_runs_default)
    {
        $sql = "Select * from `scorecard` where `match_id`='$match_id' and `innings`=$innings and `ball_id`>=($over_start - 1)*6 and `ball_id`<=($over_end * 6) ORDER BY `ball_id` DESC";
        $result = $connection->query($sql);
        $current_ball = 0;
        $current_runs = 0;
        $balls = array();
        while ($row = $result->fetch_assoc()) {
            $ball = new Score($row['match_id'], $row['innings'], $row['ball_id'], $row['runs'], $row['wickets']);
            $balls[] = $ball;
            $current_ball = max($current_ball, $ball->getBallId());
            $current_runs = max($current_runs, $ball->getRuns());
        }
        echo $current_ball."  ".$current_runs;
        echo $innings;
        if ($current_ball == 0)
            return $expected_runs_default;

        $expected_runs_from_run_rate = $current_runs / $current_ball * (($over_end) * 6);
        $runs_gap = $expected_runs_default - $expected_runs_from_run_rate;
        $expected_runs = $expected_runs_default - ($runs_gap * count($balls) / (($over_end - $over_start) * 6));
        echo "exp: ".$expected_runs;
        return $expected_runs;
    }
    public function get_score($connection, $match_id, $innings) {

    }

    public function get_all_matches(): array
    {
        $url = 'https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/get_all_matches'; // replace with your URL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return response as string
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects
        $response = curl_exec($ch);
        curl_close($ch);
        $response = substr(str_replace("},{","}&&{", $response), 1, -1);
        $matches = [];
        $match_id = 0;
        foreach (explode('&&', $response) as $match) {
            $matches[$match_id] = json_decode($match);
            $match_id = $match_id + 1;
        }
        return $matches;
    }
    public function get_scorecard_latest($series_id, $match_id): string
    {
        $url = 'https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/scores/' . $series_id . '/' . $match_id . '/latest';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return response as string
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}