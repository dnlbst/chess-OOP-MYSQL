<?php

class Rook extends Piece
{
    protected $vectors = [[-1,0],[0,1],[1,0],[0,-1]];

    public function __construct($x, $y, $white = true)
    {
        parent::__construct($x, $y, $white);
        if($white){
            $this->unicode = '&#x2656;';
        } else {
            $this->unicode = '&#x265C;';
        }
    }
}
