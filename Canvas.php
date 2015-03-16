<?php
include('./Overlay.php');
class Canvas
{
    /**
     * @var Imagick $canvas empty image
     */
    protected $canvas;

    /**
     * @var Overlay $overlay overlay image which will be composed with users pictures
     */
    protected $overlay;

    /**
     * Creates new UsersCanvas. Initialized canvas, mask and overlay.
     */
    public function __construct()
    {
        $this->overlay = new Overlay();

        $this->canvas = new Imagick();
        $this->canvas->newimage(
            $this->overlay->getWidth(),
            $this->overlay->getHeight(),
            new ImagickPixel('white'),
            'png'
        );
    }

    public function addMainImage($path)
    {
        $x = 17;
        $y = 44;
        $picture = new Imagick($path);
        $this->canvas->compositeimage($picture, Imagick::COMPOSITE_DEFAULT, $x, $y);
    }

    public function addName($text)
    {
        $fontPath = "./fonts/Anonymous_Pro_B.ttf";
        $fontSize = 16;
        $fontColor = "#E5D77B";
        $draw = new ImagickDraw();
        $draw->setfont($fontPath);
        $draw->setfontsize($fontSize);
        $draw->setfillcolor(new ImagickPixel($fontColor));

        $metrics = $this->overlay->queryFontMetrics($draw, $text);
        $draw->annotation(0, $metrics['ascender'], $text);

        //17-272
        $x = round(17 + (255 - $metrics['textWidth'])/2);
        //17-43
        //$y = round(17 + (26 - $metrics['textHeight'])/2);
        $y = 18;

        $picture = new Imagick();
        $picture->newimage($metrics['textWidth'], $metrics['textHeight'], 'none');
        $picture->drawimage($draw);
        $this->canvas->compositeimage(
            $picture,
            Imagick::COMPOSITE_DEFAULT,
            $x,
            $y
        );
    }

    static $rowStep = 19;
    static $rowStart = 45;

    public function addAttack($text, $val)
    {
        $this->addRegularText($text, $val, self::$rowStart);
    }

    public function addDefense($text, $val)
    {
        $this->addRegularText($text, $val, self::$rowStart + self::$rowStep);
    }

    public function addShots($text, $val)
    {
        $this->addRegularText($text, $val, self::$rowStart + 2*self::$rowStep);
    }

    public function addDamage($text, $val)
    {
        $this->addRegularText($text, $val, self::$rowStart + 3*self::$rowStep);
    }

    public function addHealth($text, $val)
    {
        $this->addRegularText($text, $val, self::$rowStart + 4*self::$rowStep);
    }

    public function addSpeed($text, $val)
    {
        $this->addRegularText($text, $val, self::$rowStart + 6*self::$rowStep);
    }

    public function addRegularText($text, $val, $y)
    {
        $fontPath = "./fonts/Anonymous_Pro.ttf";
        $fontSize = 12;
        $fontColor = "#FFFFFF";

        $draw = new ImagickDraw();
        $draw->setfont($fontPath);
        $draw->setfontsize($fontSize);
        $draw->setfillcolor(new ImagickPixel($fontColor));

        $metrics = $this->overlay->queryFontMetrics($draw, $text);
        $draw->annotation(0, $metrics['ascender'], $text);

        $x = 153;
        $picture = new Imagick();
        $picture->newimage($metrics['textWidth'], $metrics['textHeight'], 'none');
        $picture->drawimage($draw);
        $this->canvas->compositeimage(
            $picture,
            Imagick::COMPOSITE_DEFAULT,
            $x,
            $y
        );

        //add val
        $draw = new ImagickDraw();
        $draw->setfont($fontPath);
        $draw->setfontsize($fontSize);
        $draw->setfillcolor(new ImagickPixel($fontColor));

        $metrics = $this->overlay->queryFontMetrics($draw, $val);
        $draw->annotation(0, $metrics['ascender'], $val);

        $x = 270 - $metrics['textWidth'];
        $picture = new Imagick();
        $picture->newimage($metrics['textWidth'], $metrics['textHeight'], 'none');
        $picture->drawimage($draw);
        $this->canvas->compositeimage(
            $picture,
            Imagick::COMPOSITE_DEFAULT,
            $x,
            $y
        );
    }

    public function addDescr($text)
    {
        if (!empty($text)) {
            $fontPath = "./fonts/Anonymous_Pro.ttf";
            $fontSize = 12;
            $fontColor = "#FFFFFF";
            $x = 17;

            $draw = new ImagickDraw();
            $draw->setfont($fontPath);
            $draw->setfontsize($fontSize);
            $draw->setfillcolor(new ImagickPixel($fontColor));

            $lines = [];
            $words = explode(" ", $text);
            if (sizeof($words) > 1) {
                $i = 1;
                $curLine = $words[0];
                $maxLineWidth = 290 - 2 * $x;
                do {
                    $metrics = $this->overlay->queryFontMetrics($draw, $curLine." ".$words[$i]);
                    if ($metrics['textWidth'] <= $maxLineWidth) {
                        $curLine .= " " . $words[$i];
                    } else {
                        $lines[] = $curLine;
                        $curLine = $words[$i];
                    }
                    $i++;
                } while (isset($words[$i]) && sizeof($lines) <= 3);
                $lines[] = $curLine;
                if (sizeof($lines) > 3) {
                    $lines = array_slice($lines, 0, 3);
                    $lines[2] .= "...";
                }
            } else {
                $lines[] = $text;
            }

            $y = 180;
            foreach ($lines as $line) {
                $draw = new ImagickDraw();
                $draw->setfont($fontPath);
                $draw->setfontsize($fontSize);
                $draw->setfillcolor(new ImagickPixel($fontColor));
                $metrics = $this->overlay->queryFontMetrics($draw, $line);
                $draw->annotation(0, $metrics['ascender'], $line);
                $picture = new Imagick();
                $picture->newimage($metrics['textWidth'], $metrics['textHeight'], 'none');
                $picture->drawimage($draw);
                $this->canvas->compositeimage(
                    $picture,
                    Imagick::COMPOSITE_DEFAULT,
                    $x,
                    $y
                );
                $y += 12;
            }
        }
    }

    /**
     * @see Canvas::getImage
     */
    public function getImage()
    {
        return $this->canvas;
    }

    /**
     * Adds overlay to canvas.
     *
     * @return $this to make chain
     */
    public function addOverlay()
    {
        $this->canvas->compositeimage($this->overlay, Imagick::COMPOSITE_DEFAULT, 0, 0);
        return $this;
    }
}
