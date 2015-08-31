<?php

namespace Jsvrcek\ICS;

use Jsvrcek\ICS\Constants;

class CalendarStream
{
    //length of line in bytes
    const LINE_LENGTH = 70;
    
    
    /**
     * 
     * @var string
     */
    private $stream = '';
    
    /**
     * resets stream to blank string
     */
    public function reset()
    {
        $this->stream = '';
    }
    
    /**
     * @return string
     */
    public function getStream()
    {
        return $this->stream;
    }
    
    /**
     * splits item into new lines if necessary
     * @param string $item
     * @return CalendarStream
     */
    public function addItem($item)
    {
        $lines = array();
        $parts = explode(PHP_EOL, trim($item));
        $top = array_shift($parts);

        $lines[] = $top;

        if(empty($parts) === false)
        {
            $lines[0] .= "\\n";

            foreach ($parts as $line)
            {
                $block = ' ';
                //get number of bytes
                $length = strlen($line);
                if ($length > 75)
                {
                    $start = 0;
                    while ($start < $length)
                    {
                        $block .= mb_strcut($line, $start, self::LINE_LENGTH, 'UTF-8');
                        $start = $start + self::LINE_LENGTH;
                        
                        //add space if not last line
                        if ($start < $length) $block .= Constants::CRLF.' ';
                    }
                }
                else
                {
                    $block = ' '.$line;
                }
        
                $lines[] = $block."\\n";
            }
        }

        $item = trim(implode(Constants::CRLF, $lines));

        $this->stream .= $item.Constants::CRLF;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getStream();
    }
}
