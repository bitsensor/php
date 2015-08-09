<?php

namespace BitSensor\View;


class View {

    private $content = '';

    public function show() {
        echo $this->content;
    }

    protected function setContent($content) {
        $this->content = $content;
    }

}