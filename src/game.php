<?php

class GameOfLife
{
    private int $cols;
    private int $rows;

    private array $grid;

    /**
     * Templates
     */
    private array $templates = [
        'glider' => [
            [0, 1, 0],
            [0, 0, 1],
            [1, 1, 1],
        ],
        'pulsar' => [
            [0, 0, 1, 1, 1, 0, 0, 0, 1, 1, 1, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1],
            [1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1],
            [1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1],
            [0, 0, 1, 1, 1, 0, 0, 0, 1, 1, 1, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 1, 1, 0, 0, 0, 1, 1, 1, 0, 0],
            [1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1],
            [1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1],
            [1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 1, 1, 0, 0, 0, 1, 1, 1, 0, 0],
        ]
    ];

    public function __construct(int $cols, int $rows)
    {
        $this->cols = $cols;
        $this->rows = $rows;
    }

    /**
     * Run the simulation
     */
    public function run(?string $template = null): void
    {
        $this->grid = $this->createGrid();

        if (!is_null($template) && array_key_exists($template, $this->templates)) {
            $this->setTemplate($this->templates[$template]);
        } else {
            $this->randomize();
        }

        while (true) {
            $this->tick();
            $this->draw();
            usleep(100000);
        }
    }

    /**
     * Create an empty universe
     */
    private function createGrid(): array
    {
        $grid = [];

        for ($x = 0; $x < $this->cols; $x++) {
            $grid[$x] = [];
            for ($y = 0; $y < $this->rows; $y++) {
                $grid[$x][$y] = 0;
            }
        }

        return $grid;
    }

    /**
     * Generate the new generation
     */
    private function tick(): void
    {
        $grid = $this->createGrid();

        for ($x = 0; $x < $this->cols; $x++) {
            for ($y = 0; $y < $this->rows; $y++) {
                $grid[$x][$y] = $this->getCellNewState($x, $y);
            }
        }

        $this->grid = $grid;
    }

    /**
     * Calculate the cell state for the new generation
     */
    private function getCellNewState(int $x, int $y): int
    {
        $sum = 0;
        for ($i = -1; $i <= 1; $i++) {
            for ($j = -1; $j <= 1; $j++) {
                if ($i == 0 && $j == 0) {
                    continue;
                }

                $col = ($x + $i + $this->cols) % $this->cols;
                $row = ($y + $j + $this->rows) % $this->rows;

                $sum += $this->grid[$col][$row];
            }
        }

        if ($sum < 2 || $sum > 3) {
            return 0;
        } elseif ($sum == 3) {
            return 1;
        }

        return $this->grid[$x][$y];
    }

    /**
     * Populate the universe with random values.
     * Only 1/4 of all cells will be set to 1
     */
    private function randomize(): void
    {
        for ($x = 0; $x < $this->cols; $x++) {
            for ($y = 0; $y < $this->rows; $y++) {
                $this->grid[$x][$y] = (int)(rand(0, 3) == 0);
            }
        }
    }

    /**
     * Populate the universe with the selected template
     */
    private function setTemplate(array $template): void
    {
        // Calculate offset to place template in the middle of the universe
        $xOffset = floor(($this->cols - count($template)) / 2);
        $yOffset = floor(($this->rows - count($template[0])) / 2);
        foreach ($template as $y => $row) {
            foreach ($row as $x => $value) {
                $this->grid[$x + $xOffset][$y + $yOffset] = $value;
            }
        }
    }

    /**
     * Draw the universe
     */
    private function draw(): void
    {
        system('clear');

        for ($y = 0; $y < $this->rows; $y++) {
            for ($x = 0; $x < $this->cols; $x++) {
                echo $this->grid[$x][$y] ? '##' : '  ';
            }
            echo '|', PHP_EOL;
        }
        for ($x = 0; $x < $this->cols; $x++) {
            echo '--';
        }
    }
}

$game = new GameOfLife(25, 25);
$game->run($argv[1]);
