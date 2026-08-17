<?php
require_once "./src/views/containers/Table.php";
require_once "./src/views/containers/TableRow.php";
require_once "./src/views/containers/TableCell.php";
require_once "./src/views/containers/AtomicElement.php";
require_once "./src/views/containers/ArticleActions.php";

class TableFactory
{
    public function createTable(array $columns, array $rows, ?string $tableClass = null): Table
    {
        $table = new Table($tableClass);
        $table->addElement($this->buildHeaderRow($columns));

        foreach ($rows as $row_data) {
            $table->addElement($this->buildRow($columns, $row_data));
        }

        return $table;
    }

    private function buildHeaderRow(array $columns): TableRow
    {
        $headerRow = new TableRow();

        foreach ($columns as $column) {
            $cell = new TableCell(isHeader: true, class: $column['class'] ?? null);
            $cell->addElement(new AtomicElement(htmlspecialchars($column['label'])));
            $headerRow->addElement($cell);
        }

        return $headerRow;
    }

    private function buildRow(array $columns, array $row_data): TableRow
    {
        $row = new TableRow();

        foreach ($columns as $column) {
            $value = $row_data[$column['key']] ?? null;
            $row->addElement($this->buildCell($column, $value, $row_data));
        }

        return $row;
    }

    private function buildCell(array $column, mixed $value, array $row_data): TableCell
    {
        $cell = new TableCell(class: $column['class'] ?? null);
        switch ($column['type']) {
            case 'actions':
                $cell->addElement(new ArticleActions($row_data['id']));
                break;
            case 'text':
            default:
                $cell->addElement(new AtomicElement(htmlspecialchars((string)$value)));
                break;
        }

        return $cell;
    }
}