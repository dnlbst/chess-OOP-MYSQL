<?php

class Piece
{
    protected $vectors = [];
    protected $unicode;
    protected $white = true;
    protected $x = 0;
    protected $y = 0;
    protected $loopStop = false;

    public function __construct($x, $y, $white = true)
    {
        $this->white = $white;
        $this->x = $x;
        $this->y = $y;
    }

    public function getProperties()
    {
        $properties['class'] = get_class($this);
        $properties['unicode'] = $this->getUnicode();
        $properties['white'] = $this->getWhite();
        $properties['x'] = $this->getX();
        $properties['y'] = $this->getY();
        return $properties;
    }

    public function getPossibleMoves($board)
    {
        $grid = $board->getGrid();
        $possibleMoves = [];
        foreach ($this->vectors as $vector){
            $xToTest = $this->getX() + $vector[0];
            $yToTest = $this->getY() + $vector[1];
            while($yToTest >= 0 && $yToTest <= 7 && $xToTest >= 0 && $xToTest <= 7){
                $piece = $board->getPieceOnGrid($xToTest, $yToTest);
                if($piece === null || ($piece instanceof Piece && $grid['white'] !== $piece->getWhite()))
                {
                    $possibleMoves[] = [$xToTest, $yToTest];
                }
                if($piece !== null || $this->loopStop === true){
                    break;
                }
                $xToTest += $vector[0];
                $yToTest += $vector[1];
            }
        }
        return $possibleMoves;
    }

    /**
     * @return bool|mixed
     */
    public function getWhite()
    {
        return $this->white;
    }

    /**
     * @param bool|mixed $white
     */
    public function setWhite($white)
    {
        $this->white = $white;
    }

    /**
     * @return mixed
     */
    public function getUnicode()
    {
        return $this->unicode;
    }

    /**
     * @param mixed $unicode
     */
    public function setUnicode($unicode)
    {
        $this->unicode = $unicode;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

}
