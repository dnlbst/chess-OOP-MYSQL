<?php

class King extends Piece
{
    protected array $vectors = [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]];
    protected bool $loopStop = true;
    protected bool $check = false;

    public function __construct($x, $y, $white = true, $check = false)
    {
        $this->check = $check;
        parent::__construct($x, $y, $white);
        if($white){
            $this->unicode = '&#x2654;';
        } else {
            $this->unicode = '&#x265A;';
        }
    }

    public function getProperties(): array
    {
        $properties = parent::getProperties();
        $properties['check'] = $this->check;
        return $properties;
    }

    public function getPossibleMoves($board, $sim = false) : array
    {
        /** @var Board $board */
        $possibleMoves = parent::getPossibleMoves($board);
        foreach ($possibleMoves as $key => $move){
            if(!$sim && $board->fieldUnderAttack($move[0], $move[1], $board->getWhite())){
                unset($possibleMoves[$key]);
            }
        }
        return $possibleMoves;
    }

    public function check($board): bool
    {
        return $board->fieldUnderAttack($this->x, $this->y, $this->white);
    }


    /**
     * @return bool
     */
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * @param $check
     */
    public function setCheck($check)
    {
        $this->check = $check;
    }
}
