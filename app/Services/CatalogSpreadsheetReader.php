<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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

        // минус строки заголовков
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

        // Excel строка, с которой начинаем читать
        // +1 потому что Excel 1-based
        // +headerRows потому что пропускаем заголовки
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
}
