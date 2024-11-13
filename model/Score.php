<?php

class Score
{
    private int $score;
    private int $innings;
    private int $ball_id;
    private int $runs;
    private int $wickets;

    public function __construct(int $score, int $innings, int $ball_id, int $runs, int $wickets){
        $this->score = $score;
        $this->innings = $innings;
        $this->ball_id = $ball_id;
        $this->runs = $runs;
        $this->wickets = $wickets;
    }
    public function getScore(){
        return $this->score;
    }
    public function getInnings(){
        return $this->innings;
    }
    public function getBallId(){
        return $this->ball_id;
    }
    public function getRuns(){
        return $this->runs;
    }
    public function getWickets(){
        return $this->wickets;
    }
}