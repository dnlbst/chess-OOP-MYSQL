<?php

class King extends Piece
{
    protected $vectors = [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]];
    protected $loopStop = true;

    public function __construct($x, $y, $white = true)
    {
        parent::__construct($x, $y, $white);
        if($white){
            $this->unicode = '&#x2654;';
        } else {
            $this->unicode = '&#x265A;';
        }
    }

//    public function getPossibleMoves($board)
//    {
//        $possibleMoves = parent::getPossibleMoves($board);
//        //
//        return $possibleMoves;
//    }


}
