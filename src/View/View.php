<?php

namespace BitSensor\View;


/**
 * Helper class for outputting HTML content.
 * @package BitSensor\View
 */
class View
{
    /**
     * @var string HTML.
     */
    private $content = '';

    /**
     * Echo the HTML content.
     */
    public function show()
    {
        echo $this->content;
    }

    /**
     * @param string $content
     */
    protected function setContent($content)
    {
        $this->content = $content;
    }

}