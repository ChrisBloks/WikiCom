<?php

namespace Wiki\api\actions;

use Wiki\api\actions\BaseAsyncAction,
Wiki\tools\traits\tErrorMessageCollector,
Mankind\tools\dev\Logger;
use XMLWriter;
use Exception;

abstract class BaseXmlAction extends BaseAsyncAction
{
    use tErrorMessageCollector;

    protected XMLWriter $writer;

    abstract protected function _getRootName(): string;
    abstract protected function _getItemName(): string;
    abstract protected function _getDownloadName(): string;
    protected function _getCdataFields(): array { return []; }

    // ===============================================================================
    // Files
    protected function _getFileFields(): array  { return []; }
    protected function _getFileBaseDir(): string { return ''; }

    protected function _getFilePath(string $value): string
    {
        return rtrim($this->_getFileBaseDir(), '/') . '/' . basename($value);
    }

    //================================================================================
    final protected function _sendData(): bool
    {
        if (!$this->_xmlEncode()) {
            return false;
        }
        header('Content-Type: application/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $this->_getDownloadName() . '"');
        header('Content-Length: ' . strlen($this->data));
        echo $this->data;
        return true;
    }

    protected function _xmlEncode(): bool
    {
        $this->writer = new XMLWriter();
        $this->writer->openMemory();
        $this->writer->setIndent(true);
        $this->writer->startDocument('1.0', 'UTF-8');
        $this->writer->startElement($this->_getRootName());

        $cdataFields = $this->_getCdataFields();
        $fileFields  = $this->_getFileFields();

        foreach ($this->data as $item) {
            $this->writer->startElement($this->_getItemName());
            foreach ($item as $field => $value) {
                if (in_array($field, $fileFields, true)) {
                    $this->_writeFile($field, $value);
                } elseif (in_array($field, $cdataFields, true)) {
                    $this->_writeCdataField($field, $value);
                } else {
                    $this->_writeField($field, $value);
                }
            }
            $this->writer->endElement(); // item
        }

        $this->writer->endElement(); // root
        $this->writer->endDocument();

        $xml = $this->writer->outputMemory();
        if ($xml === '' || $xml === false) {
            $this->logError("XML RAW DATA ", print_r($this->data, true));
            throw new Exception("XML BUILD ERROR - could not generate output");
        }

        $this->data = $xml;
        return true;
    }

    private function _writeFile(string $field, ?string $value): void
    {
        $this->writer->startElement($field);

        if ($value !== null && $value !== '') {
            $path    = $this->_getFilePath($value);
            $content = file_get_contents($path);

            if ($content === false) {
                $this->logError("XML FILE NOT READABLE", $path);
            } else {
                $this->writer->text(base64_encode($content));
            }
        }

        $this->writer->endElement();
    }

    private function _writeField(string $field, $value): void
    {
        $this->writer->startElement($field);
        $this->writer->text((string) $value);
        $this->writer->endElement();
    }

    private function _writeCdataField(string $field, $value): void
    {
        $this->writer->startElement($field);
        $this->writer->writeCdata($value);
        $this->writer->endElement();
    }
}