<?php

class King extends Piece
{
    protected array $vectors = [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]];
    protected bool $loopStop = true;

    public function __construct($x, $y, $white = true)
    {
        parent::__construct($x, $y, $white);
        if($white){
            $this->unicode = '&#x2654;';
        } else {
            $this->unicode = '&#x265A;';
        }
    }

    public function getPossibleMoves($board) : array
    {
        /** @var Board $board */
        $possibleMoves = parent::getPossibleMoves($board);

        return $possibleMoves;
    }

    public function possibleMovesKing($board) : array
    {
        /** @var Board $board */
        $possibleMovesKing = [];
        if($board->inCheck($this->white)){
            $movesKing = $this->getPossibleMoves($board);
            foreach ($movesKing as $move){
                if(!$board->fieldUnderAttack($move[0], $move[1])){
                    $possibleMovesKing[] = $move;
                }
            }
        }
        return $possibleMovesKing;
    }


}
