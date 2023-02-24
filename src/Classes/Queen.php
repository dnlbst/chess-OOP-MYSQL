<?php

class Queen extends Piece
{
    protected $vectors = [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]];

    public function __construct($x, $y, $white = true)
    {
        parent::__construct($x, $y, $white);
        if($white){
            $this->unicode = '&#x2655;';
        } else {
            $this->unicode = '&#x265B;';
        }
    }
}
