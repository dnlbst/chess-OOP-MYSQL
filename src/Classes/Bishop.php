<?php

class Bishop extends Piece
{
    protected array $vectors = [[-1,-1],[-1,1],[1,-1],[1,1]];

    public function __construct($x, $y, $white = true)
    {
        parent::__construct($x, $y, $white);
        if($white){
            $this->unicode = '&#x2657;';
        } else {
            $this->unicode = '&#x265D;';
        }
    }

}
