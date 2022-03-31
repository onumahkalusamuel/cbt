<?php

class Functions {    
    
    static function sanitize($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    static function make_slug($title) {
        $slug = strtolower($title);
        $slug = trim(preg_replace('/[^A-Za-z0-9]+/', '-', $slug),'-');
        return $slug;
    }

    static function prepareUpdateData($data) {
        $updateArray = [];
        foreach($data as $key => $d) 
            if( property_exists($data, $key) && ($key !== 'id') && !empty($d)) $updateArray[] = $key .'="'. $d .'"';
        return implode(', ', $updateArray);
    }
}