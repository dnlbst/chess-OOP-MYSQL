<?php

class Knight extends Piece
{
    protected array $vectors = [[-2,-1],[-2,1],[-1,2],[1,2],[2,1],[2,-1],[1,-2],[-1,-2]];
    protected bool $loopStop = true;

    public function __construct($x, $y, $white = true)
    {
        parent::__construct($x, $y, $white);
        if($white){
            $this->unicode = '&#x2658;';
        } else {
            $this->unicode = '&#x265E;';
        }
    }

}
