<?php
function icon(string $n): string
{
    $m = ['plus' => '+', 'edit' => '✎', 'trash' => '×', 'dashboard' => '▦', 'product' => '◇', 'order' => '▤', 'customer' => '♙', 'category' => '▱', 'promo' => '%', 'settings' => '⚙'];
    return '<span class="icon">' . e($m[$n] ?? '•') . '</span>';
}
