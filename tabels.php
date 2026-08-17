<?php
require_once "./src/tools/utils/HtmlUtils.php";
class TableFactoryV2
{
    protected array $rows;
    protected array $display_types;
    protected array $class_types;
    protected array $columnheaders;


    public function __construct($columnsdata, $rowsdata)
    {
        $this->rows = $rowsdata;
        $this->display_types = $columnsdata["types"];
        $this->class_types = $columnsdata["classes"];
        $this->columnheaders = $columnsdata["headers"];
    }

    public function createTable()
    {
        $str = "";
        $str .= $this->startTable();
        $str .= $this->buildHeadRow();
        $str .= $this->buildRows();
        $str .= $this->endTable();
        return $str;
    }

    protected function startTable()
    {
        return "<table>";
    }

    protected function buildHeadRow()
    {
        $str = "";
        $str .= '<tr' . HtmlUtils::addClassAttr("table_header") . '>';
        foreach ($this->columnheaders as $key => $value) {
            $str .= '<th' . HtmlUtils::addClassAttr($value) . '>' . $key . '</th>';
        }
        $str .= '</tr>';
        return $str;

    }

    protected function buildRows()
    {
        $str = '';
        foreach ($this->rows as $row) {
            $str .= '<tr>';

            foreach ($row as $key => $value) {
                $display_type = $this->display_types[$key];
                switch ($display_type) {
                    case 'first_cell':
                        // ToDo: implement ArticleActions
                        $str .= '<td>' .
                            '<a href=\"edit.php?id=$id\">' . $this->_actionLink($value, '&#10000;', 'EditPage', ) . '</a>'
                            . $this->_actionLink('-' . $value, '&#10060;', 'Delete') .
                            '</td>';
                        break;
                    case "integer":
                        $str .= '<td' . HtmlUtils::addClassAttr($this->class_types[$key]) . '> ' . $value . '</td>';
                        break;
                    case "string":
                        $str .= '<td' . HtmlUtils::addClassAttr($this->class_types[$key]) . '> ' . $value . '</td>';
                        break;
                    case "date":
                        $str .= '<td' . HtmlUtils::addClassAttr($this->class_types[$key]) . '> ' . $value . '</td>';
                        break;
                    case 'rating':
                        break;
                    default:
                        // throw exception
                }
            }
            $str .= '</tr>';

        }
        return $str;
    }

    protected function endTable()
    {
        return '</table>';
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

