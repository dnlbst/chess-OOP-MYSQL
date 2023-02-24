<?php

class Board
{

    protected $grid = [];
    protected $message = '';

    /**
     * @return array
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @param array $grid
     */
    public function setGrid($grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function initGrid()
    {
        if(!file_exists('pieces.json')){
            $this->setGrid(
                [
//                    'white' => true,
//                    'pieces' => [
//                        new Rook(0, 0, false), new Knight(1, 0, false), new Bishop(2, 0, false), new King(3, 0, false), new Queen(4, 0, false), new Bishop(5, 0, false), new Knight(6, 0, false), new Rook(7, 0, false),
//                        new Pawn(0, 1, false), new Pawn(1, 1, false), new Pawn(2, 1, false), new Pawn(3, 1, false), new Pawn(4, 1, false), new Pawn(5, 1, false), new Pawn(6, 1, false), new Pawn(7, 1, false),
//                        new Pawn(0, 6), new Pawn(1, 6), new Pawn(2, 6), new Pawn(3, 6), new Pawn(4, 6), new Pawn(5, 6), new Pawn(6, 6), new Pawn(7, 6),
//                        new Rook(0, 7), new Knight(1, 7), new Bishop(2, 7), new King(3, 7), new Queen(4, 7), new Bishop(5, 7), new Knight(6, 7), new Rook(7, 7)
//                    ]

                    'white' => true,
                    'pieces' => [
                        new Pawn(2, 5, false),

                        new Pawn(0, 7),
                        new King(3, 7)
                    ]
                ]
            );
        } else {
            $this->loadGrid();
        }
    }

    private $xAxis = [
        'a' => 0, 'b' => 1, 'c' => 2, 'd' => 3, 'e' => 4, 'f' => 5, 'g' => 6, 'h' => 7
    ];

    private $yAxis = [
        '1' => 7, '2' => 6, '3' => 5, '4' => 4, '5' => 3, '6' => 2, '7' => 1, '8' => 0
    ];

    public function getPieceOnGrid($x, $y)
    {
        foreach ($this->grid['pieces'] as $piece) {
            if($piece->getX() === $x && $piece->getY() === $y){
                return $piece;
            }
        }
        return null;
    }

    public function coordinatesInArray($x, $y, $coordinates){
        foreach ($coordinates as $coordinate){
//            var_dump($coordinate);
            if($coordinate[0] === $x && $coordinate[1] === $y){
                return true;
            }
        }
        return false;
    }

    public function findKing($white)
    {
        $location = [];
        $grid = $this->getGrid();
        foreach ($grid['pieces'] as $piece){
            if($piece instanceof \King && $piece->getWhite() === $white){
                $location[] = $piece->getX();
                $location[] = $piece->getY();
            }
        }
        return $location;
    }

    public function fieldUnderAttack($x, $y)
    {
        $fieldUnderAttack = false;
        foreach ($this->grid['pieces'] as $piece) {
            if(!$piece->getWhite()){
                $enemyMoves = $piece->getPossibleMoves($this);
                if($this->coordinatesInArray($x, $y, $enemyMoves)){
                    $fieldUnderAttack = true;
                }
            }
        }
//        var_dump($fieldUnderAttack);
        return $fieldUnderAttack;
    }

//    public function inCheck($white)
//    {
//        $inCheck = false;
//        $kingPos = $this->findKing($white);
//        if($this->fieldUnderAttack($kingPos[0], $kingPos[1])){
//            var_dump($inCheck);
//            $inCheck = true;
//        }
//        return $inCheck;
//    }

    public function moveAction($post)
    {
        if(isset($post['from']) && ($post['to'])){
            $from = str_split($post['from']);
            $xFrom = $this->xAxis[strtolower($from[0])];
            $yFrom = $this->yAxis[$from[1]];

            $to = str_split($post['to']);
            $xTo = $this->xAxis[strtolower($to[0])];
            $yTo = $this->yAxis[$to[1]];

            $piece = $this->getPieceOnGrid($xFrom, $yFrom);
            if($piece !== null){
                if($piece->getWhite() === $this->grid['white']){
                    var_dump($piece->getPossibleMoves($this));
                    if($this->coordinatesInArray($xTo, $yTo, $piece->getPossibleMoves($this))){
                        $this->deletePiece($xTo, $yTo);
                        $this->movePiece($piece, $xTo, $yTo);
                        $this->grid['white'] = !$this->grid['white'];
                        if($this->grid['white']){
                            $this->setMessage("⚪ White's turn!");
                        } else {
                            $this->setMessage("⚫ Black's turn!");
                        }
                    } else {
                        $this->setMessage('not a possible move');
                    }
                } else {
                    $this->setMessage('not your turn');
                }
            } else {
                $this->setMessage('field is empty');
            }
        }
    }

    public function deletePiece($xNew, $yNew)
    {
        foreach ($this->grid['pieces'] as $key => $piece){
            if($xNew === $piece->getX() && $yNew === $piece->getY()){
                unset($this->grid['pieces'][$key]);
                break;
            }
        }
    }

    public function movePiece($piece, $xNew, $yNew)
    {
        $piece->setX($xNew);
        $piece->setY($yNew);
    }

    private function loadGrid()
    {
        $data = json_decode(file_get_contents('pieces.json'), true);
        $this->grid['white'] = $data['white'];
        foreach ($data['pieces'] as $piece){
            switch ($piece['class']){
                case 'Rook':
                    $newPiece = new Rook($piece['x'], $piece['y'], $piece['white']);
                    break;
                case 'Knight':
                    $newPiece = new Knight($piece['x'], $piece['y'], $piece['white']);
                    break;
                case 'Bishop':
                    $newPiece = new Bishop($piece['x'], $piece['y'], $piece['white']);
                    break;
                case 'Queen':
                    $newPiece = new Queen($piece['x'], $piece['y'], $piece['white']);
                    break;
                case 'King':
                    $newPiece = new King($piece['x'], $piece['y'], $piece['white']);
                    break;
                case 'Pawn':
                    $newPiece = new Pawn($piece['x'], $piece['y'], $piece['white']);
                    break;
            }
            $this->grid['pieces'][] = $newPiece;

            if($this->grid['white']){
                $this->setMessage("⚪ White's turn!");
            }
        }
    }

    public function saveGrid()
    {
        $data = [];
        $data['white'] = $this->grid['white'];
        $data['pieces'] = [];
        foreach ($this->grid['pieces'] as $piece){
            $data['pieces'][] = $piece->getProperties();
        }
        file_put_contents('pieces.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public function resetAction($post)
    {
        if (isset($post['reset'])) {
            unlink('pieces.json');
            $this->setMessage("reset, file deleted!"."<br>"."⚪ White's turn!");
        }
    }

    public function showGrid()
    {
        for($i=0; $i <= 7; $i++){
            echo "<div class='rownum'>" . (8-$i). "</div>";
            echo "<div class='row'>";
            for($j=0;$j<=7;$j++){
                $total=$i+$j;
                if($total%2===0){
                    echo "<div class='white'>";
                } else {
                    echo "<div class='black'>";
                }
                $piece = $this->getPieceOnGrid($j, $i);
                if($piece !== null){
                    echo $piece->getUnicode();
                }
                echo '</div>';
            }
            echo "</div>";
        }
    }

}