<?php

class Board
{

    protected bool $white = true;
    protected array $grid = [];
    protected string $message = '';

    /**
     * @return array
     */
    public function getGrid(): array
    {
        return $this->grid;
    }

    /**
     * @param array $grid
     */
    public function setGrid($grid): void
    {
        $this->grid = $grid;
    }

    /**
     * @return bool
     */
    public function getWhite(): bool
    {
        return $this->white;
    }

    /**
     * @param bool $white
     */
    public function setWhite(bool $white): void
    {
        $this->white = $white;
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
// loadBoard
    public function initPieces(): void
    {
        if (!file_exists('pieces.json')) {
            $this->grid = [

//                    'pieces' => [
//                        new Rook(0, 0, false), new Knight(1, 0, false), new Bishop(2, 0, false), new King(3, 0, false), new Queen(4, 0, false), new Bishop(5, 0, false), new Knight(6, 0, false), new Rook(7, 0, false),
//                        new Pawn(0, 1, false), new Pawn(1, 1, false), new Pawn(2, 1, false), new Pawn(3, 1, false), new Pawn(4, 1, false), new Pawn(5, 1, false), new Pawn(6, 1, false), new Pawn(7, 1, false),
//                        new Pawn(0, 6), new Pawn(1, 6), new Pawn(2, 6), new Pawn(3, 6), new Pawn(4, 6), new Pawn(5, 6), new Pawn(6, 6), new Pawn(7, 6),
//                        new Rook(0, 7), new Knight(1, 7), new Bishop(2, 7), new King(3, 7), new Queen(4, 7), new Bishop(5, 7), new Knight(6, 7), new Rook(7, 7)
//                    ]

                    'pieces' => [
                        new Rook(0, 0, false),new Bishop(2, 4, false),new King(3, 0, false),
                        new Pawn(0, 7),new Pawn(2, 2),new King(3, 7)
                    ]

//                    'pieces' => [
//                        new Rook(0, 7),new King(3, 0, false),new Bishop(0, 4, false),new King(3, 7)
//                    ]

            ];
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
            if ($piece->getX() === $x && $piece->getY() === $y) {
                return $piece;
            }
        }
        return null;
    }

    public function coordinatesInArray($x, $y, $coordinates): bool
    {
        foreach ($coordinates as $coordinate) {
            if ($coordinate[0] === $x && $coordinate[1] === $y) {
                return true;
            }
        }
        return false;
    }

    public function findKing($white) : array
    {
        $location = [];
        $grid = $this->getGrid();
        foreach ($grid['pieces'] as $piece) {
            if ($piece instanceof \King && $piece->getWhite() === !$this->grid['white']) {
                $location[] = $piece->getX();
                $location[] = $piece->getY();
            }
        }
        return $location;
    }

    public function fieldUnderAttack($x, $y): bool
    {
        $fieldUnderAttack = false;
        foreach ($this->grid['pieces'] as $piece) {
            if ($piece->getWhite() === $this->grid['white'] ) {
                $enemyMoves = $piece->getPossibleMoves($this);
                if ($this->coordinatesInArray($x, $y, $enemyMoves)) {
                    $fieldUnderAttack = true;
                }
            }
        }
        return $fieldUnderAttack;
    }

    public function inCheck($white) : bool
    {
        $inCheck = false;
        $kingPos = $this->findKing($white);
        if($this->fieldUnderAttack($kingPos[0], $kingPos[1])){
            $inCheck = true;
            if($white){
                $this->grid['check']['white'] = true;
            } else  {
                $this->grid['check']['black'] = true;
            }
        }
        return $inCheck;
    }

//    public function offCheck($white) : array
//    {
//        $offCheck = [];
//        foreach ($this->grid['pieces'] as $piece){
//            if($piece->getWhite() === $white){
//                $fellowMoves[] = $piece->getPossibleMoves($this);
//            }
//
//        }
//
//        return $offCheck;
//    }

    public function moveAction($post): void
    {
        if (isset($post['from']) && ($post['to'])) {
            $from = str_split($post['from']);
            $xFrom = $this->xAxis[strtolower($from[0])];
            $yFrom = $this->yAxis[$from[1]];

            $to = str_split($post['to']);
            $xTo = $this->xAxis[strtolower($to[0])];
            $yTo = $this->yAxis[$to[1]];

            $piece = $this->getPieceOnGrid($xFrom, $yFrom);
            if ($piece !== null) {
                if ($piece->getWhite() === $this->grid['white']) {
                    if ($this->coordinatesInArray($xTo, $yTo, $piece->getPossibleMoves($this))) {
                        $this->deletePiece($xTo, $yTo);
                        $this->movePiece($piece, $xTo, $yTo);
                        if (!$this->grid['white']) {
                            $this->message = ("⚪ White's turn!");
                        } else {
                            $this->message = ("⚫ Black's turn!");
                        }
                        if($this->inCheck($this->white)){
                            $this->message .= ("<br>" . "!!! SCHACH !!!");
                        }
                        $this->grid['white'] = !$this->grid['white'];
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

    public function deletePiece($xNew, $yNew) : void
    {
        foreach ($this->grid['pieces'] as $key => $piece) {
            if ($xNew === $piece->getX() && $yNew === $piece->getY()) {
                var_dump($key);
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

    /**
     * @throws JsonException
     */
    private function loadGrid(): void
    {
        $data = json_decode(file_get_contents('pieces.json'), true);
        $this->grid['white'] = $data['white'];
        $this->grid['check'] = $data['check'];
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
            $this->grid['pieces'][] = $newPiece;  // könnte sein dass, kein case in dem switch erfüllt ist (thorie) könnte man mit default: $newPiece = null oder oben anfangs mit null declarieren
            $this->setMessage("⚪ White's turn!");
        }
    }

    public function saveGrid(): void
    {
        if(!isset($this->grid['white']) && !isset($this->grid['check'])){
            $this->grid['white'] = true;
            $this->grid['check'] = ['white' => false, 'black' => false];
        }
        $data = [
            'check' => $this->grid['check'],
            'white' => $this->grid['white'],
            'pieces' => []
        ];
        foreach ($this->grid['pieces'] as $piece) {
            $data['pieces'][] = $piece->getProperties();
        }
        file_put_contents('pieces.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public function resetAction($post)
    {
        if (isset($post['reset'])) {
            unlink('pieces.json');
            $this->setMessage("reset, file deleted!" . "<br>" . "⚪ White's turn!");
        }
    }

    public function showGrid()
    {
        for ($i = 0; $i <= 7; $i++) {
            echo "<div class='rownum'>" . (8 - $i) . "</div>";
            echo "<div class='row'>";
            for ($j = 0; $j <= 7; $j++) {
                $total = $i + $j;
                if ($total % 2 === 0) {
                    echo "<div class='white'>";
                } else {
                    echo "<div class='black'>";
                }
                $piece = $this->getPieceOnGrid($j, $i);
                if ($piece !== null) {
                    echo $piece->getUnicode();
                }
                echo '</div>';
            }
            echo "</div>";
        }
    }

}