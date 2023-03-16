<?php

class Piece
{
    protected array $vectors = [];
    protected $unicode;
    protected $white = true;
    protected int $x = 0;
    protected int $y = 0;
    protected bool $loopStop = false;

    public function __construct($x, $y, $white = true)
    {
        $this->white = $white;
        $this->x = $x;
        $this->y = $y;
    }

    public function getProperties(): array
    {
        $properties['class'] = get_class($this);
        $properties['unicode'] = $this->unicode;
        $properties['white'] = $this->white;
        $properties['x'] = $this->x;
        $properties['y'] = $this->y;
        return $properties;
    }

    /**
     * @param $board
     * @return array
     */
    public function getPossibleMoves($board, $sim = false): array
    {
        /** @var Board $board */
        $possibleMoves = [];
        foreach ($this->vectors as $vector){
            $xToTest = $this->x + $vector[0];
            $yToTest = $this->y + $vector[1];
            while($yToTest >= 0 && $yToTest <= 7 && $xToTest >= 0 && $xToTest <= 7){
                $piece = $board->getPieceOnGrid($xToTest, $yToTest);

                if($piece === null || ($piece instanceof Piece && $this->white === $board->getWhite()))
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
