<?php

class Pawn extends Piece
{
    protected array $vectors = [[0, -1]];
    protected bool $loopStop = true;

    public function __construct($x, $y, $white = true)
    {
        parent::__construct($x, $y, $white);
        if($white){
            $this->unicode = '&#x2659;';
        } else {
            $this->unicode = '&#x265F;';
            $this->vectors = [[0, 1]];
        }
    }

    public function getPossibleMoves($board) : array
    {
        /** @var Board $board */
        $possibleMoves = parent::getPossibleMoves($board);

        //Am Anfang darf ein Bauer 2 Schritte gehen
        if($this->white && $this->getY() === 6){
            $possibleMoves[] = [$possibleMoves[0][0], $possibleMoves[0][1] - 1];
        } elseif (!$this->white && $this->getY() === 1){
            $possibleMoves[] = [$possibleMoves[0][0], $possibleMoves[0][1] + 1];
        }

        //Gerade schlagen nicht erlaubt
        foreach ($possibleMoves as $key => $possibleMove){
            if($board->getPieceOnGrid($possibleMove[0], $possibleMove[1]) !== null){
                unset($possibleMoves[$key]);
                break;
            }
        }

        //schräg schlagen
//        $whiteR = $board->getPieceOnGrid($this->getX() + 1, $this->getY() - 1);
//        $whiteL = $board->getPieceOnGrid($this->getX() - 1, $this->getY() - 1);
//
//        $blackR = $board->getPieceOnGrid($this->getX() + 1, $this->getY() + 1);
//        $blackL = $board->getPieceOnGrid($this->getX() - 1, $this->getY() + 1);
//
//        //weiss
//        if($whiteR !== null && !$whiteR->getWhite()){
//            $possibleMoves[] = [$this->getX() + 1, $this->getY() - 1];
//        } elseif($whiteL !== null && !$whiteL->getWhite()){
//            $possibleMoves[] = [$this->getX() - 1, $this->getY() - 1];
//        }
//        //black
//        if($blackR !== null && $blackR->getWhite()){
//            $possibleMoves[] = [$this->getX() + 1, $this->getY() + 1];
//        } elseif($blackL !== null && $blackL->getWhite()){
//            $possibleMoves[] = [$this->getX() - 1, $this->getY() + 1];
//        }

        if ($this->white) {
            $left = $board->getPieceOnGrid($this->getX() - 1, $this->getY() - 1);
            $right = $board->getPieceOnGrid($this->getX() + 1, $this->getY() - 1);
        } else {
            $right = $board->getPieceOnGrid($this->getX() + 1, $this->getY() + 1);
            $left = $board->getPieceOnGrid($this->getX() - 1, $this->getY() + 1);
        }
        if ($left !== null && $left->getWhite() !== $this->white) {
            $possibleMoves[] = [$left->getX(), $left->getY()];
        }
        if ($right !== null && $right->getWhite() !== $this->white) {
            $possibleMoves[] = [$right->getX(), $right->getY()];
        }

        return $possibleMoves;
    }

}
