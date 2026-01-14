<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class CatalogSpreadsheetReader
{
    /**
     * В файле:
     * 1 строка — группы (Пороги / Арки / Пенки)
     * 2 строка — подтипы (Передние / Задние / ...)
     * Данные начинаются с 3 строки (1-based)
     */
    public int $headerRows = 2;

    /**
     * Количество строк с данными (без заголовков)
     */
    public function countDataRows(string $absolutePath): int
    {
        $spreadsheet = IOFactory::load($absolutePath);
        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestDataRow();

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return max(0, $highestRow - $this->headerRows);
    }

    /**
     * Первая строка заголовков (группы: Пороги / Арки / Пенки)
     */
    public function readFirstHeaderRow(string $absolutePath): array
    {
        return $this->readHeaderRow($absolutePath, 1);
    }

    /**
     * Вторая строка заголовков (подтипы)
     */
    public function readSecondHeaderRow(string $absolutePath): array
    {
        return $this->readHeaderRow($absolutePath, 2);
    }

    /**
     * Чтение чанка данных
     *
     * @param string $absolutePath
     * @param int    $offset  0-based offset по данным (НЕ Excel row)
     * @param int    $limit
     *
     * @return array<int, array>
     */
    public function readChunk(string $absolutePath, int $offset, int $limit): array
    {
        $spreadsheet = IOFactory::load($absolutePath);
        $sheet = $spreadsheet->getActiveSheet();

        $startRow = $this->headerRows + 1 + $offset;
        $endRow   = $startRow + $limit - 1;

        $highestRow = $sheet->getHighestDataRow();
        if ($startRow > $highestRow) {
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            return [];
        }

        $endRow = min($endRow, $highestRow);

        $range = "A{$startRow}:" . $sheet->getHighestColumn() . "{$endRow}";

        $rows = $sheet->rangeToArray(
            $range,
            null,
            true,
            false
        );

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $rows;
    }

    /**
     * Универсальное чтение строки заголовка
     */
    private function readHeaderRow(string $absolutePath, int $rowNumber): array
    {
        $spreadsheet = IOFactory::load($absolutePath);
        $sheet = $spreadsheet->getActiveSheet();

        $range = "A{$rowNumber}:" . $sheet->getHighestColumn() . "{$rowNumber}";

        $rowArray = $sheet->rangeToArray(
            $range,
            null,
            true,
            false
        );

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $rowArray[0] ?? [];
    }

    /**
     * ВАЖНО: первая строка заголовков должна быть "точечной" по merge-диапазонам.
     * Если M1:O1 = "Пенка", то "Пенка" должна быть только в M..O.
     * В P..S должно быть пусто, если там пусто в верхней строке.
     */
    public function readFirstHeaderRowResolved(string $absolutePath): array
    {
        $spreadsheet = IOFactory::load($absolutePath);
        $sheet = $spreadsheet->getActiveSheet();

        $highestCol = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        $row = array_fill(0, $highestCol, '');

        $groupRow = 1;

        // 1) merged-группы (M1:O1 => "Пенка" только внутри M..O)
        foreach ($sheet->getMergeCells() as $range) {
            if (!preg_match('/^([A-Z]+)' . $groupRow . ':([A-Z]+)' . $groupRow . '$/', $range, $m)) {
                continue;
            }

            $startCol = Coordinate::columnIndexFromString($m[1]);
            $endCol   = Coordinate::columnIndexFromString($m[2]);

            $topLeftCell = Coordinate::stringFromColumnIndex($startCol) . $groupRow;
            $val = trim((string) $sheet->getCell($topLeftCell)->getValue());
            if ($val === '') continue;

            for ($c = $startCol; $c <= $endCol; $c++) {
                $row[$c - 1] = $val;
            }
        }

        // 2) одиночные заголовки (если есть)
        for ($c = 1; $c <= $highestCol; $c++) {
            if ($row[$c - 1] !== '') continue;

            $cell = Coordinate::stringFromColumnIndex($c) . $groupRow;
            $val = trim((string) $sheet->getCell($cell)->getValue());
            if ($val !== '') {
                $row[$c - 1] = $val;
            }
        }

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $row;
    }
}
