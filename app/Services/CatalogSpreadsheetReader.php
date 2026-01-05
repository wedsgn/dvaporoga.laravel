<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;

class CatalogSpreadsheetReader
{
    // В файле 2 строки заголовков, данные начинаются с 3-й строки (1-based)
    public int $headerRows = 2;

    public function countDataRows(string $absolutePath): int
    {
        $spreadsheet = IOFactory::load($absolutePath);
        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = (int)$sheet->getHighestRow(); // 1-based
        $dataRows = max(0, $highestRow - $this->headerRows);

        // освобождаем память
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $dataRows;
    }

    /**
     * Читает кусок строк данных.
     * $offsetDataRow: 0-based индекс по данным (0 = первая строка данных)
     * Возвращает массив строк, где каждая строка — массив по колонкам (0-based).
     */
    public function readChunk(string $absolutePath, int $offsetDataRow, int $limit): array
    {
        $spreadsheet = IOFactory::load($absolutePath);
        $sheet = $spreadsheet->getActiveSheet();

        $startRow = $this->headerRows + 1 + $offsetDataRow; // переводим в 1-based row excel
        $endRow = $startRow + $limit - 1;

        $result = [];
        for ($r = $startRow; $r <= $endRow; $r++) {
            $rowArray = $sheet->rangeToArray(
                "A{$r}:" . $sheet->getHighestColumn() . "{$r}",
                null,
                true,
                false
            );
            $values = $rowArray[0] ?? [];
            // если строка пустая — всё равно отдаём, решать будет процессор
            $result[] = $values;
        }

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $result;
    }

    /**
     * Вторая строка заголовков (индекс 2 в Excel, 1-based).
     */
    public function readSecondHeaderRow(string $absolutePath): array
    {
        $spreadsheet = IOFactory::load($absolutePath);
        $sheet = $spreadsheet->getActiveSheet();

        $r = 2;
        $rowArray = $sheet->rangeToArray(
            "A{$r}:" . $sheet->getHighestColumn() . "{$r}",
            null,
            true,
            false
        );

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $rowArray[0] ?? [];
    }
}
