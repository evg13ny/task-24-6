<?php

class CutMetaTags implements Iterator
{
    public $tagsToCut = 'meta';
    private $content = [];

    public function __construct($file)
    {
        $this->content = file($file);
    }

    public function current()
    {
        return current($this->content);
    }

    public function key()
    {
        return key($this->content);
    }

    public function next()
    {
        next($this->content);
    }

    public function rewind()
    {
        reset($this->content);
    }

    public function valid()
    {
        return current($this->content) !== false;
    }
}

$html = new CutMetaTags('oldcode.html');

foreach ($html as $key => $row) {
    if (strpos($row, $html->tagsToCut) === false) {
        $new = fopen('newcode.html', 'a');
        fwrite($new, $row);
        fclose($new);
    }
}
