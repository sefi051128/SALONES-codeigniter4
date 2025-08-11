<?php
if (!function_exists('time_ago')) {
    /**
     * Convierte una fecha a formato "hace X tiempo"
     * 
     * @param string $date Fecha en formato válido para strtotime
     * @return string Texto descriptivo del tiempo transcurrido
     */
    function time_ago($date) {
        if (empty($date)) {
            return "Fecha no disponible";
        }
        
        $periods = ["segundo", "minuto", "hora", "día", "semana", "mes", "año", "década"];
        $lengths = ["60", "60", "24", "7", "4.35", "12", "10"];
        
        $now = time();
        $unix_date = is_numeric($date) ? $date : strtotime($date);
        
        // Validar fecha
        if (empty($unix_date)) {
            return "Fecha inválida";
        }

        // Determinar si es fecha futura o pasada
        if ($now > $unix_date) {    
            $difference = $now - $unix_date;
            $tense = "hace";
        } else {
            $difference = $unix_date - $now;
            $tense = "dentro de";
        }
        
        // Calcular períodos de tiempo
        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }
        
        $difference = round($difference);
        
        // Pluralizar si es necesario
        if ($difference != 1) {
            $periods[$j] .= "s";
        }
        
        return "$tense $difference $periods[$j]";
    }
}