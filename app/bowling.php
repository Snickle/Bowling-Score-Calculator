<?php

class BowlingGame {

    protected $score;

    protected $total_score;

    protected $formatted_score;

    protected $on_a_spare;

    protected $on_a_strike;

    protected $on_a_double;


    function __construct() {
        $this->score = array();
        $this->total_score = 0;
        $this->played_frames = 0;

        $this->on_a_spare = false;
        $this->on_a_strike = false;
        $this->on_a_double = false;
    }

    public function getScore() {
        return $this->score;
    }

    public function getFormattedScore( $score ) {

        if( !empty( $score ) ) {
            $i = 0;
            $played_frames = explode( ',',  $score );

            foreach( $played_frames as $played_frame ) {

                $bowls = str_split($played_frame);

                $this->formatted_score[] = array(
                    'bowl_1'    => $bowls[0],
                    'bowl_2'    => isset( $bowls[1] ) ? $bowls[1] : "",
                    'score'     => isset( $this->score[$i] ) ? $this->score[$i] : "",
                );

                $i++;
            }

        }

        return $this->formatted_score;
    }


    public function getTotalScore() {

        $this->total_score = end( $this->score );

        return $this->total_score;
    }

    public function calculateScore( $score ) {

        if( !empty( $score ) ) {
            $i = 0;
            $played_frames = explode( ',',  $score );

            foreach( $played_frames as $played_frame ) {

                $previous_score = !empty( $this->score ) ? end( $this->score ) : 0;
                
                if( $i <= 9 ) {        
                    $this->calculateBonusScores( $played_frame, $i );
                    $previous_score = !empty( $this->score ) ? end( $this->score ) : 0;

                    $this->score[] = $this->calculateCurrentFrame( $played_frame ) + $previous_score;
                } else {

                    //Todo: Calculate final frames for perfect score correctly

                    if( $i == 10 ) {
                        $this->score[] = $this->calculateCurrentFrame( $played_frame ) + $previous_score;
                    }
                    if( $i == 11 ) {
                        $this->score[] = $this->calculateCurrentFrame( $played_frame ) + $previous_score;
                    }
                }

                $this->on_a_double = $this->checkForDoubleStrike( $played_frame );
                $this->on_a_strike = $this->checkForStrike( $played_frame );
                $this->on_a_spare = $this->checkForSpare( $played_frame );

                $i++;
            }

        }

        return $this->score;
    }

    private function checkForDoubleStrike( $frame_score ) {

        $double_strike = false;

        if( $this->on_a_strike && $frame_score == 'x' ) {
            // If bowled a double strike, return true
            $double_strike = true;
        }

        return $double_strike;
    }

    private function checkForStrike( $frame_score ) {

        $strike = false;

        if( $frame_score == 'x' ) {
            // If bowled a strike, return true
            $strike = true;
        }

        return $strike;
    }

    private function checkForSpare( $frame_score ) {

        $spare = false;

        if( strpos( $frame_score, '/' ) ) {
            // If bowled a spare, return true
            $spare = true;
        }

        return $spare;
    }

    private function calculateCurrentFrame( $frame_score ) {

        $frame_score = strtolower( $frame_score );
        $calculated_score = 0;

        $bowls = str_split($frame_score);
        $bowl_count = 1;
        
        foreach( $bowls as $bowl ) {
            switch( $bowl ) {
                case "x": 
                    $calculated_score = 10;
                    break;
                case "/": 
                    $calculated_score = 10;
                    break;
                case "-":
                    $calculated_score += 0;
                    break;
                default: 
                    $calculated_score += (int) $bowl;
                    break;
            }

            $bowl_count++;
        }


        return $calculated_score;
    }

    public function updatePreviousFrame( $current_total, $current_score, $additional = 0 ) {

        switch( $current_score ) {
            case "x": 
                $current_score = 10;
                break;
            case "/": 
                $current_score = 10;
                break;
            case "-":
                $current_score = 0;
                break;
        }

        $updated_score = $current_total + $current_score + $additional;

        return $updated_score;
    }

    private function calculateBonusScores( $current_frame, $current_frame_index ) {

        if( $this->on_a_double ) {
            $current_total = 0;
            $current_score = substr( strtolower($current_frame), 0, 1 );

            if( $current_frame_index - 3 >= 0 ) {
                $current_total = $this->score[ $current_frame_index - 3 ];
            }

            if( $current_frame_index < 8 ||  $current_frame_index >= 8 && $current_score == 'x' ) {
                $previous_frame = $current_frame_index - 2;
                $this->score[ $previous_frame ] = $this->updatePreviousFrame( $current_total, $current_score, 20 );
    
                $previous_frame = $current_frame_index - 1;
                $current_total = $this->score[ $current_frame_index - 2];
    
                $current_score = $this->calculateCurrentFrame( $current_frame );
    
                $this->score[ $previous_frame ] = $this->updatePreviousFrame( $current_total, $current_score, 10 );
            }


        } else if( $this->on_a_strike ) {
            $previous_frame = $current_frame_index - 1;

            $current_total = $this->score[ $previous_frame ];
            $current_score = $this->calculateCurrentFrame( $current_frame );

            $this->score[ $previous_frame ] = $this->updatePreviousFrame( $current_total, $current_score, 10 );
        } else if( $this->on_a_spare ) {
            $previous_frame = $current_frame_index - 1;

            $current_total = $this->score[ $previous_frame  - 1 ];
            $current_score = substr( $current_frame, 0, 1 );

            $this->score[ $previous_frame ] = $this->updatePreviousFrame( $current_total, $current_score, 10 );
        }

    }

    public function getTotalFrames() {

        $this->played_frames = count($this->score);

        return $this->played_frames;
    }
}