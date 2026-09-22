<?php
namespace App\Services;
class HtmlSanitizer {public function clean(?string $html): string {$html=strip_tags((string)$html,'<p><br><h2><h3><h4><ul><ol><li><strong><em><blockquote>');return preg_replace('/<([a-z0-9]+)\s+[^>]*>/i','<$1>',$html)??'';}}
