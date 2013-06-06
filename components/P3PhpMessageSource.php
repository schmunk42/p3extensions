<?php

class P3PhpMessageSource extends CPhpMessageSource
{
    public $mappings = array();

    protected function getMessageFile($category, $language)
    {
        // find default file
        $messageFile = parent::getMessageFile($category, $language);

        // look for fallbacks if no file is found
        if (!is_file($messageFile)) {
            if (isset($this->mappings[$language])) {
                // find file according to mapping
                $messageFile = parent::getMessageFile($category, $this->mappings[$language]);
            } else {
                // find file based on the string before _
                $messageFile = parent::getMessageFile($category, substr($language,0,strpos($language,'_')));
            }
        }
        return $messageFile;
    }
}