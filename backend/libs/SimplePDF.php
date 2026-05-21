<?php
/**
 * UPTEC Cursos - Generador de PDFs Simple
 * Libreria ligera para generar reportes PDF
 */

class SimplePDF {
    protected $buffer = '';
    protected $objects = [];
    protected $currentObject = 0;
    protected $pages = [];
    protected $fonts = [];
    protected $currentFont = '';
    protected $currentSize = 12;
    protected $x = 0;
    protected $y = 0;
    protected $width = 210;  // A4 mm
    protected $height = 297; // A4 mm
    protected $margin = 20;
    protected $lineHeight = 6;

    public function __construct() {
        $this->init();
    }

    protected function init() {
        // Inicializar documento PDF
        $this->objects[1] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj";
        $this->objects[2] = "2 0 obj\n<< /Type /Pages /Kids [] /Count 0 >>\nendobj";
        
        // Fuentes basicas
        $this->fonts['Helvetica'] = $this->addFont('Helvetica');
        $this->fonts['Helvetica-Bold'] = $this->addFont('Helvetica-Bold');
        $this->currentFont = 'Helvetica';
    }

    protected function addFont($name) {
        $id = count($this->objects) + 1;
        $this->objects[$id] = "$id 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /$name >>\nendobj";
        return $id;
    }

    public function addPage() {
        $pageId = count($this->objects) + 1;
        $contentId = $pageId + 1;
        
        $this->objects[$pageId] = "$pageId 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents $contentId 0 R /Resources << /Font << /F1 {$this->fonts[$this->currentFont]} 0 R >> >> >>\nendobj";
        
        $this->pages[] = $pageId;
        $this->x = $this->margin;
        $this->y = $this->height - $this->margin;
        
        return $contentId;
    }

    public function setFont($font, $size = 12) {
        if (isset($this->fonts[$font])) {
            $this->currentFont = $font;
        }
        $this->currentSize = $size;
        $this->lineHeight = $size * 0.5;
    }

    public function cell($w, $h, $text, $border = 0, $ln = 0, $align = 'L') {
        $this->buffer .= "BT\n";
        $this->buffer .= "/F1 {$this->currentSize} Tf\n";
        $this->buffer .= sprintf("%.2f %.2f Td\n", $this->x * 2.835, ($this->height - $this->y) * 2.835);
        $this->buffer .= "($text) Tj\n";
        $this->buffer .= "ET\n";
        
        if ($ln) {
            $this->y -= $h;
            $this->x = $this->margin;
        } else {
            $this->x += $w;
        }
    }

    public function text($x, $y, $text) {
        $this->buffer .= "BT\n";
        $this->buffer .= "/F1 {$this->currentSize} Tf\n";
        $this->buffer .= sprintf("%.2f %.2f Td\n", $x * 2.835, ($this->height - $y) * 2.835);
        $safeText = str_replace(['(', ')', '\\'], ['\\(', '\\)', '\\\\'], $text);
        $this->buffer .= "($safeText) Tj\n";
        $this->buffer .= "ET\n";
    }

    public function ln($h = null) {
        $this->y -= ($h ?: $this->lineHeight);
        $this->x = $this->margin;
    }

    public function output($filename = 'documento.pdf', $dest = 'D') {
        // Actualizar objeto Pages
        $kids = implode(' ', array_map(fn($p) => "$p 0 R", $this->pages));
        $count = count($this->pages);
        $this->objects[2] = "2 0 obj\n<< /Type /Pages /Kids [$kids] /Count $count >>\nendobj";

        // Generar contenido de pagina
        foreach ($this->pages as $i => $pageId) {
            $contentId = $pageId + 1;
            $content = "q\n" . $this->buffer . "Q";
            $this->objects[$contentId] = "$contentId 0 obj\n<< /Length " . strlen($content) . " >>\nstream\n$content\nendstream\nendobj";
        }

        // Generar PDF
        $pdf = "%PDF-1.4\n";
        $offsets = [];
        $offset = strlen($pdf);

        foreach ($this->objects as $id => $obj) {
            $offsets[$id] = $offset;
            $pdf .= "$obj\n";
            $offset = strlen($pdf);
        }

        // Tabla de referencias cruzadas
        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n";
        $pdf .= "0 " . (count($this->objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        foreach ($offsets as $off) {
            $pdf .= sprintf("%010d 00000 n \n", $off);
        }

        // Trailer
        $pdf .= "trailer\n";
        $pdf .= "<< /Size " . (count($this->objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n";
        $pdf .= "$xrefOffset\n";
        $pdf .= "%%EOF";

        if ($dest === 'D') {
            header('Content-Type: application/pdf');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Content-Length: ' . strlen($pdf));
            echo $pdf;
        }
        
        return $pdf;
    }
}
