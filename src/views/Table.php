<?php

namespace Wiki\views;

use Wiki\tools\utils\HtmlUtils, Wiki\config, Wiki\views\containers\Rating;

/**
 * View class dedicated to make tables
 * @var array $columns [string => string] 
 *              [column_title] => name that shows up in cell
 *              [display_type] => decider on type of cell
 *              [class_types] => should be class type for entire column
 *              [column_headers] => should be class type specifically for the table head
 * @var array $rows [strings => string] Contains the row values for a table
 *                  dependant on display_type of the $columns
 * @var string $page_value Value of target page for possible href redirect
 */
class Table
{
    protected array $columns;
    protected array $rows;
    public string $page_value;

    public function __construct(array $columns, array $rows)
    {
        $this->columns = $columns;
        $this->rows = $rows;
    }

    /**
     * Calls the functions for each table element in a row to construct table
     * @param mixed $tableClass Adds html class to table element
     * @return string string containing the table element
     */
    public function createTable(?string $tableClass = null): string
    {
        $str = $this->startTable($tableClass);
        $str .= $this->buildHeadRow();
        $str .= $this->buildRows();
        $str .= $this->endTable();
        return $str;
    }

    /**
     * Starts the table element
     * @param mixed $tableClass Adds html class to table element
     * @return string string containing the <table> string
     */
    protected function startTable(?string $tableClass): string
    {
        return '<table' . HtmlUtils::addClassAttr($tableClass) . '>';
    }

    /**
     * ends the table element
     * @return string
     */
    protected function endTable(): string
    {
        return '</table>';
    }

    /**
     * Builds the header row of a table
     * @return string containing the header row for a table
     */
    protected function buildHeadRow(): string
    {
        $str = '<thead class="table-dark"><tr>';

        // add a column to the head of a table
        foreach ($this->columns as $column) {
            $str .= '<th' . HtmlUtils::addClassAttr($column['column_headers'] ?? null) . '>'
                . htmlspecialchars($column['column_title'])
                . '</th>';
        }

        $str .= '</tr></thead>';
        return $str;
    }

    /**
     * Builds each row of a table iteratively
     * @return string with all the rows for a table
     */
    protected function buildRows(): string
    {
        $str = '<tbody class="table-group-divider"';

        // for each array item in $rows
        foreach ($this->rows as $row_data) {
            $str .= '<tr>';

            //for columns, each row gets an identifier of that column
            foreach ($this->columns as $identifier => $column) {
                $value = $row_data[$identifier] ?? null;
                $str .= $this->buildCell($column, $value, $row_data);
            }

            $str .= '</tr>';
        }
        $str.= '</tbody>';
        return $str;
    }

    /**
     * Builds one cell of a table in HTML or returns an object
     * @param array $column one entry(array) from $this->columns
     * @param mixed $value contains diffferent values that can be passed onto cell types 
     * @param array $row_data array of rows for the cells to fit in
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function buildCell(array $column, mixed $value, array $row_data): string
    {
        // add class
        $classAttr = HtmlUtils::addClassAttr($column['class_types'] ?? null);

        switch ($column['display_type']) 
        {
            // cell contains Date formatting
            case 'date':
                $formatted = $value !== null ? date('Y-m-d', strtotime((string) $value)) : '';
                return "<td$classAttr>" . htmlspecialchars($formatted) . '</td>';
            // cell should contain a rating (5stars)
            case 'rating':
                return "<td$classAttr>" . (new Rating(
                                rating: (float) $value,
                                article_id: $row_data['id'],
                                display_only: true
                            ))->show() . '</td>';
            // cell contains the first cell actions
            case 'first_cell':
                return "<td$classAttr>" . (new FirstCell(
                    page_id: $row_data['id'],
                    target_page: \CONFIG::FIRST_CELL_TARGET,
                    delete_page: $row_data['id']
                ))->returnFirstCellOptions()
                    . '</td>';
            // cell contains string
            case 'string':
                return "<td$classAttr>" . htmlspecialchars((string) $value) . '</td>';
            // cell contains title
            case 'Title':
                return '<td'.$classAttr.'><a href="main.php?page=article&id='.$row_data['id'].'">' . htmlspecialchars((string) $value) . '</a></td>';

            default:
                throw new \InvalidArgumentException('Unknown display_type: '. $column['display_type']);
        }
    }
}
