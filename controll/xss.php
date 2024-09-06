<?php
// XSS対策
function eh($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
