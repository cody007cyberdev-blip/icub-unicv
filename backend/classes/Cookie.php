<?php 

/**
 * Classe Para gerenciar cookies do navegador
 * escencnial para lembrar das acoes do utilizador
 * usado em REMEMBER ME
 */

class Cookie {
    
    public static function set($name, $value, $expirationDay = 1) {
        setcookie($name, $value, time() + 3600 * 24 * $expirationDay, "/");
    }

    public static function get($name) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public static function delete($name) {
        setcookie($name, "", time() - 3600);
        unset($_COOKIE[$name]);
    }
}