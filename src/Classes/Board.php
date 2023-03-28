<?php

class Board
{

    protected bool $white = true;
    protected array $grid = [];
    protected string $message = '';

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
     * @return bool
     */
    public function getCheckWhite(): bool
    {
        return $this->checkWhite;
    }

    /**
     * @param bool $checkWhite
     */
    public function setCheckWhite(bool $checkWhite): void
    {
        $this->checkWhite = $checkWhite;
    }

    /**
     * @return bool
     */
    public function getCheckBlack(): bool
    {
        return $this->checkBlack;
    }

    /**
     * @param bool $checkBlack
     */
    public function setCheckBlack(bool $checkBlack): void
    {
        $this->checkBlack = $checkBlack;
    }

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
    public function setGrid(array $grid): void
    {
        $this->grid = $grid;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

// loadBoard
    public function initPieces(): void
    {
        if (!file_exists('pieces.json')) {
            $this->grid = [


//                        new Rook(0, 0, false), new Knight(1, 0, false), new Bishop(2, 0, false), new King(3, 0, false), new Queen(4, 0, false), new Bishop(5, 0, false), new Knight(6, 0, false), new Rook(7, 0, false),
//                        new Pawn(0, 1, false), new Pawn(1, 1, false), new Pawn(2, 1, false), new Pawn(3, 1, false), new Pawn(4, 1, false), new Pawn(5, 1, false), new Pawn(6, 1, false), new Pawn(7, 1, false),
//                        new Pawn(0, 6), new Pawn(1, 6), new Pawn(2, 6), new Pawn(3, 6), new Pawn(4, 6), new Pawn(5, 6), new Pawn(6, 6), new Pawn(7, 6),
//                        new Rook(0, 7), new Knight(1, 7), new Bishop(2, 7), new King(3, 7), new Queen(4, 7), new Bishop(5, 7), new Knight(6, 7), new Rook(7, 7)
//

//                        new Rook(0, 6),new knight(2, 5, false),new King(3, 0),
//                        new King(2, 7, false)

                        new King(3, 0, false),
                        new Rook(0, 6),
                        new Bishop(1,1,false), new pawn(3, 1, false),new pawn(4, 1, false), new pawn(2, 1, false),
                        new King(4, 7)

//                        new Rook(0, 7),new King(3, 0, false),new Bishop(0, 4, false),new King(3, 7)

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
        foreach ($this->grid as $piece) {
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

    public function fieldUnderAttack($x, $y, $white): bool
    {
        $fieldUnderAttack = false;
        foreach ($this->grid as $piece) {

            if($piece->getWhite() !== $white) {
                $enemyMoves = $piece->getPossibleMoves($this, true);
                if ($this->coordinatesInArray($x, $y, $enemyMoves)) {
                    $fieldUnderAttack = true;
                }
            }
        }
        return $fieldUnderAttack;
    }

    public function findKing($white) : ?King
    {
        $king = null;
        foreach ($this->getGrid() as $piece){
            if($white === $piece->getWhite() && $piece instanceof King){
                $king = $piece;
            }
        }
        return $king;
    }

    public function unsetInCheckMoves($piece, $moves, $king, $board, $x, $y) : array
    {
        foreach ($moves as $key => $move){
            $this->movePiece($piece, $move[0], $move[1]);
            if($king->check($board)){
                unset($moves[$key]);
            }
            $this->movePiece($piece, $x, $y);
        }
        return $moves;
    }

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
                if ($piece->getWhite() === $this->white) {
                    $possibleMoves = $piece->getPossibleMoves($this, false);
                    $king = $this->findKing($this->white);
//                    $this->unsetInCheckMoves($piece, $possibleMoves, $king, $this, $xFrom, $yFrom);
                    if($king->getCheck()){
                        $this->white = !$this->white;
                        foreach ($possibleMoves as $key => $move){
                            $this->movePiece($piece, $move[0], $move[1]);
                            if($king->check($this)){
                                unset($possibleMoves[$key]);
                            }
                        }
                        $this->white = !$this->white;
                        $this->movePiece($piece, $xFrom, $yFrom);
                    }

                    if ($this->coordinatesInArray($xTo, $yTo, $possibleMoves)) {
                        $this->deletePiece($xTo, $yTo);
                        $this->movePiece($piece, $xTo, $yTo);
                        /** @var King $enemyKing */
                        $enemyKing = $this->findKing(!$this->white);

                        // Message Part :
                        if (!$this->white) {
                            $this->message = ("⚪ White's turn!");
                        } else {
                            $this->message = ("⚫ Black's turn!");
                        }

                        foreach ($this->getGrid() as $piece){
                            if($piece instanceof King && $piece->getWhite() !== $this->white){
                                var_dump($piece->getPossibleMoves($this, false));
                            }

//                        if ($enemyKing->check($this)) {
//                            $unCheckMoves = [];
//                            foreach ($this->getGrid() as $piece){
//                                if($piece->getWhite() !== $this->white){
//                                    $x = $piece->getX();
//                                    $y = $piece->getY();
//                                    $unCheckMoves[] = $piece->getPossibleMoves($this, false);
//                                    foreach ($unCheckMoves as $i => $pieceMoves){
////                                        unsetInCheckMoves($piece, $pieceMoves, $enemyKing, $this, $x, $y);
//                                        foreach ($pieceMoves as $key => $move){
//                                            $this->movePiece($piece, $move[0], $move[1]);
//                                            if($enemyKing->check($this)){
//                                                unset($unCheckMoves[$i][$key]);
//                                            }
//                                            $this->movePiece($piece, $x, $y);
//                                        }
//                                    }
//                                }
//                            }
//                            $sum = 0;
//                            foreach ($unCheckMoves as $unCheckMove){
//                                foreach ($unCheckMove as $coordinates){
//                                    $sum += array_sum($coordinates);
//                                }
//                            }
//                            if($sum === 0){
//                                $this->setMessage('!!! CHECK MATE !!!');
//                            } else {
//                                $this->setMessage('!!! CHECK !!!');
//                            }
//                            $enemyKing->setCheck(true);
                        }
                        $this->white = !$this->white;
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
        foreach ($this->grid as $key => $piece) {
            if ($xNew === $piece->getX() && $yNew === $piece->getY()) {
                unset($this->grid[$key]);
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
        $this->white = $data['white'];
        foreach ($data['grid'] as $piece){
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
                    $newPiece = new King($piece['x'], $piece['y'], $piece['white'], $piece['check']);
                    break;
                case 'Pawn':
                    $newPiece = new Pawn($piece['x'], $piece['y'], $piece['white']);
                    break;
            }
            $this->grid[] = $newPiece;  // könnte sein dass, kein case in dem switch erfüllt ist (thorie) könnte man mit default: $newPiece = null oder oben anfangs mit null declarieren
            //$this->setMessage("⚪ White's turn!");
        }
    }

    public function saveGrid(): void
    {
        $data = [
            'white' => $this->white,
            'grid' => []
        ];
        foreach ($this->grid as $piece) {
            $data['grid'][] = $piece->getProperties();
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