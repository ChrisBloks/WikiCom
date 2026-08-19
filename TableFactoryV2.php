<?php
require_once "./src/tools/utils/HtmlUtils.php";
require_once "./src/views/containers/ArticleActions.php";
require_once "./src/views/Rating.php";
require_once "./src/views/FirstCell.php";

class TableFactoryV2
{
    protected array $columns;
    protected array $rows;
    public string $page_value;

    public function __construct(array $columns, array $rows)
    {
        $this->columns = $columns;
        $this->rows = $rows;
    }

    public function createTable(?string $tableClass = null): string
    {
        $str = $this->startTable($tableClass);
        $str .= $this->buildHeadRow();
        $str .= $this->buildRows();
        $str .= $this->endTable();
        return $str;
    }

    protected function startTable(?string $tableClass): string
    {
        return '<table' . HtmlUtils::addClassAttr($tableClass) . '>';
    }

    protected function endTable(): string
    {
        return '</table>';
    }

    protected function buildHeadRow(): string
    {
        $str = '<tr>';

        foreach ($this->columns as $column) {
            $str .= '<th' . HtmlUtils::addClassAttr($column['css_class'] ?? null) . '>'
                . htmlspecialchars($column['column_title'])
                . '</th>';
        }

        $str .= '</tr>';
        return $str;
    }

    protected function buildRows(): string
    {
        $str = '';

        foreach ($this->rows as $row_data) {
            $str .= '<tr>';

            //special loop for column rows
            foreach ($this->columns as $identifier => $column) {
                $value = $row_data[$identifier] ?? null;
                $str .= $this->buildCell($column, $value, $row_data);
            }

            $str .= '</tr>';
        }

        return $str;
    }

    protected function buildCell(array $column, mixed $value, array $row_data): string
    {
        $classAttr = HtmlUtils::addClassAttr($column['class_type'] ?? null);

        switch ($column['display_type']) {
            case 'date':
                $formatted = $value !== null ? date('Y-m-d', strtotime((string) $value)) : '';
                return "<td$classAttr>" . htmlspecialchars($formatted) . '</td>';

            case 'rating':
                return "<td$classAttr>" . (new Rating((float) $value))->show() . '</td>';

            // replace editarticle with const from config file
            case 'first_cell':
                return "<td$classAttr>" . (new FirstCell($row_data['id'], "editArticle", $row_data['id']))->returnFirstCellOptions() . '</td>';
            
                case 'string':
                return "<td$classAttr>" . htmlspecialchars((string) $value) . '</td>';

            default:
                throw new \InvalidArgumentException("Unknown display_type: '{$column['display_type']}'");
        }
    }
}