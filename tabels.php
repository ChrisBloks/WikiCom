<?php
require_once "./src/tools/utils/HtmlUtils.php";
class Table
{


    public function makeTable()
    {
        $this->startTable();
        $this->buildHeadRow();
        $this->buildRow();
        $this->endTable();
    }



    protected function startTable()
    {
        echo "<table>";
    }

    protected function buildHeadRow()
    {

        $columns_headers = [
            "id" => "first_cellTableHead",
            "title" => "articletitleTableHead",
            "last_edited" => "lastEditTableHead"
        ];
        echo '<tr' . HtmlUtils::addClassAttr("header_class").'>';
        foreach ($columns_headers as $key => $value) {
            echo '<th' . HtmlUtils::addClassAttr($value) . '>' . $key . '</th>';
        }
        echo '</tr>';

    }

    protected function buildRow()
    {
        $rows = [
            ['id' => 1, 'title' => 'PHP OOP Basics', 'last_edited' => '2026-07-12',],
            ['id' => 2, 'title' => 'Factory Pattern Deep Dive', 'last_edited' => '2026-08-01'],
            ['id' => 3, 'title' => 'Draft: Untitled', 'last_edited' => '2026-08-10'],
        ];

        $display_types = [
            "id" => "first_cell",
            "title" => "string",
            "last_edited" => "date"
        ];

        $class_types = [
            "id" => "first_cell",
            "title" => "articletitle",
            "last_edited" => "lastEdit"
        ];
        foreach ($rows as $row) {
            echo '<tr>';

            foreach ($row as $key => $value) {
                $display_type = $display_types[$key];
                switch ($display_type) {
                    case 'first_cell':
                        echo '<td>' .
                            $this->_actionLink($value, '&#10000;', 'Update') .
                            $this->_actionLink('-' . $value, '&#10060;', 'Delete') .
                            '</td>';
                        break;
                    case "integer":
                        echo '<td' . HtmlUtils::addClassAttr($class_types[$key]) . '> integer:' . $value . '</td>';
                        break;
                    case "string":
                        echo '<td' . HtmlUtils::addClassAttr($class_types[$key]) . '> string:' . $value . '</td>';
                        break;
                    case "date":
                        echo '<td' . HtmlUtils::addClassAttr($class_types[$key]) . '> date:' . $value . '</td>';
                        break;
                }
            }
            echo '</tr>';

        }

    }

    protected function endTable()
    {
        echo '</table>';
    }

    private function _actionLink(string $record_id, string $title, string $hint)
    {
        return '<span' . HtmlUtils::addClassAttr("dashboard_column1")
            . ' data-gw-record-id="' . $record_id . '"'
            . ' title="' . $hint . '">'
            . $title
            . '</span>';
    }



}

$table = new Table();
$table->makeTable();