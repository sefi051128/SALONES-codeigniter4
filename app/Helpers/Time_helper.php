<?php
if (!function_exists('time_ago')) {
    function time_ago($date) {
        if (empty($date)) {
            return "Fecha no disponible";
        }
        
        $periods = ["segundo", "minuto", "hora", "día", "semana", "mes", "año", "década"];
        $lengths = ["60", "60", "24", "7", "4.35", "12", "10"];
        
        $now = time();
        $unix_date = is_numeric($date) ? $date : strtotime($date);
        
        if (empty($unix_date)) {
            return "Fecha inválida";
        }

        if ($now > $unix_date) {    
            $difference = $now - $unix_date;
            $tense = "hace";
        } else {
            $difference = $unix_date - $now;
            $tense = "dentro de";
        }
        
        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }
        
        $difference = round($difference);
        
        if ($difference != 1) {
            $periods[$j] .= "s";
        }
        
        return "$tense $difference $periods[$j]";
    }
}