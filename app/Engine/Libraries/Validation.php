<?php namespace App\Engine\Libraries;

/*
 * Avaialble validators
 * alpha
 * numeric
 * alpha_num
 * valid_email
 * valid_url
 * valid_slug
 * min[]
 * max[]
 * required
 * valid_input
 * 
 * To make it work
 * $valiate = $validation
            ->with($body)
            ->rules([
                'name' => 'required|alpha',
                'username' => 'required|min[4]|max[20]alpha_num',
                'email' => 'valid_email|min[5]',
                'password' => 'min[5]'
            ])
            ->validate();
 * 
 */


class Validation {
    
    protected $body;
    protected $rules;
    protected $errors;


    public function __construct() {
        $this->errors = [];
    }
    
    
    private function rulesEncode(string $rule) {
        $decodedRule = explode('|', $rule);
        return $decodedRule;
    }
    
    
    private function makeValid(string $param, string $name, $bodyVal) {
        
        if (preg_match('/min\[.*\]/', $param)) {
            preg_match('/min([\[](.*)[\]])/', $param, $match);
            $num = $match[2];
            
            if (!empty($bodyVal) && mb_strlen($bodyVal) < $num)
                $this->errors[$name][] = "$name field must has at least $num characters.";
        }
        
        if (preg_match('/max\[.*\]/', $param)) {
            preg_match('/max([\[](.*)[\]])/', $param, $match);
            $num = $match[2];
            
            if (!empty($bodyVal) && mb_strlen($bodyVal) > $num)
                $this->errors[$name][] = "$name field maximum characters constraint is - $num.";
        }
        
        if ($param === 'required') {

            if (strlen($bodyVal) < 1) 
                $this->errors[$name][] = "$name field can't be empty.";
        }
        
        
        if ($param === 'valid_email') {
            if (!empty($bodyVal) && !filter_var($bodyVal, FILTER_VALIDATE_EMAIL))
                $this->errors[$name][] = "$name is invalid email address!";
        }
        
        
        if ($param === 'valid_url') {
            
            $urlParts = explode('://', $bodyVal);
            $partOne = $urlParts[0] ?? '';
            $partTwo = $urlParts[1] ?? '';
            $validParts = ['http', 'https', 'ftp'];

            $str = '';
            if (empty($partTwo)) {
                $newUrl = $bodyVal;
                $str = $this->str2url($newUrl);
            } else {
                $newUrl = $partTwo;
                $str = $this->str2url($partTwo);
            }

            if (strcmp($newUrl, $str) < 0) {
                $this->errors[$name][] = 'Url is invalid';
            } else if (!empty($partTwo) && in_array($partOne, $validParts)) {
                
                if (!filter_var($bodyVal, FILTER_VALIDATE_URL)) {
                    $this->errors[$name][] = 'Url is invalid';
                }
            } else if (!empty($partTwo) && !in_array($partOne, $validParts)) {
                $this->errors[$name][] = 'Url is invalid';
            }
        }


        if ($param === 'valid_slug') {

            $str = $this->str2url($bodyVal);

            if (!empty($bodyVal) && $str !== $bodyVal)
                $this->errors[$name][] = "Slug is invalid!";
        }
        
        
        if ($param === 'alpha') {
            if ( !empty($bodyVal) && !preg_match('/^[a-zA-Zა-ჰа-яА-Я()]+$/', $bodyVal))
                $this->errors[$name][] = "$name - Only alphabetical characters are allowed!";
        }
        
        
        if ($param === 'alpha_num') {
            
            if ( !empty($bodyVal) && !preg_match('/^[a-zA-Zა-ჰа-яА-Я0-9()]+$/', $bodyVal))
                $this->errors[$name][] = "$name - Only alphabetical and numeric characters are allowed!";
        }
        
        
        if ($param === 'valid_input') {
            
            $str = strip_tags($bodyVal);
            
            if (!empty($bodyVal) && $str != $bodyVal)
                $this->errors[$name][] = "None secure characters added.";
        }
        
        
        if ($param === 'numeric') {
            
            if (!empty($bodyVal) && !filter_var($bodyVal, FILTER_VALIDATE_INT)) {
                $this->errors[$name][] = "$name - Only numbers are allowed!";
            }
        }
        
        
    }
    
    
    
    // Create url from string
    private function str2url($str, $options = array()) {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
        
        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => false,
        );
        
        // Merge options
        $options = array_merge($defaults, $options);
        
        $char_map = array(
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
            'ß' => 'ss', 
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
            'ÿ' => 'y',

            // Latin symbols
            '©' => '(c)',

            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 

            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',

            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
            'Ž' => 'Z', 
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z', 

            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
            'Ż' => 'Z', 
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',

            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        );
        
        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
        
        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }
        
        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
        
        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
        
        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
        
        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);
        
        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }
    

    
    public function with($param) {
        $this->body = json_decode(json_encode($param), true);
        return $this;
    }
    
    
    public function rules(array $param) {
        $this->rules = $param;
        return $this;
    }
    
    
    public function validate() {
        
        foreach ($this->rules as $name => $rule) {
            $ruleParts = $this->rulesEncode($rule);
            
            foreach ($ruleParts as $rp) {
                $this->makeValid($rp, $name, $this->body[$name]);
            }
        }
        
        return $this->errors;
    }
}