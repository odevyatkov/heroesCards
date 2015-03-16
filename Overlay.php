<?php

class Overlay extends Imagick
{
    protected $width;
    protected $height;
    /**
     * Create \Imagick with path to overlay image
     */
    public function __construct()
    {
        parent::__construct(
            "./background.png"
        );
        $this->width = 290;
        $this->height = 232;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }
}
