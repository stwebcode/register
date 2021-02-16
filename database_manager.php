<?php


class DatabaseManager{

    /*
                    DatabaseManager() - Datubāzes datu apstrādes klase

    Šī klase ir paredzēta datu apstrādei izmantojot datubāzi. Šī klase piedāvā arī datu
    drošības pārbaudes funkcijas kā, piemēram, check_username(), kas pārbauda vai lietotājvārds
    ir pareizi noformulēts. Vairāk: <ENTER WIKI URL HERE>

    @author: CracX

    */

    // Klasi iniciējot, saglabājam dažas lietiņas par datubāzi no config.php un uzsākam savienojumu
    function __construct(){
        require_once 'config.php';

        $this->DB_HOST = $DB_HOST;
        $this->DB_DATABASE = $DB_DATABASE;
        $this->DB_USER = $DB_USER;
        $this->DB_PASS = $DB_PASS;

        $this->CONN = $this->_init_db_connection();
    }

    // Privāta funckija, kas izveido savienojumu ar datubāzi, izveidojot PDO instanci
    private function _init_db_connection(){
        try {
            $dbh = new PDO("mysql:host=$this->DB_HOST;dbname=$this->DB_DATABASE;charset=utf8", 
                            $this->DB_USER, 
                            $this->DB_PASS);
        } catch (PDOException $e) {
            http_response_code(500);
            die("Database error");
        }

        return $dbh;
    }

    // Publiska funkcija, kas iegūst lietotāju (ja tāds eksistē) pēc lietotājvārda
    public function get_user(string $username){
        $sql = "SELECT * FROM users WHERE username=?;";
        $stmt = $this->CONN->prepare($sql);

        $stmt->execute(array($username));

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$result){
            return false;
        }

        return $result;
    }

    // Publiska funkcija, kas pārbauda vai lietotājvārds sader ar formātu
    public function check_username(string $username){
        if (strlen($username) < 5){
            return array(
                "success" => false, 
                "error_id" => 0
            );
        }

        $user = $this->get_user($username);

        if ($user){
            return array(
                "success" => false, 
                "error_id" => 1
            );
        }

        return array(
            "success" => true 
        );
    }

    // Publiska funkcija, kas pārbauda vai prole sader ar formātu
    public function check_password(string $password){
        if (strlen($password) < 5){
            return array(
                "success" => false, 
                "error_id" => 0
            );
        }
        return array(
            "success" => true
        );
    }

    // Publiska funckijas, kas reģistrē lietotāju
    public function register(string $username, string $password, string $basename){

        if(!$this->check_username($username)){
            return false;
        }

        $sql = "INSERT INTO users(username, password, image) VALUES (?, ?, ?);";
        $stmt = $this->CONN->prepare($sql);

        $hash = password_hash($password, PASSWORD_ARGON2I);

        $results = $stmt->execute(array($username, $hash, $basename));

        if(!$results){
            return false;
        }

        return true;
    }

    // Publiska funkcija, kas reģistrē lietotāju bez attēla, ja nav lietotājs nav piekritis privātuma politikai
    public function registerWithoutPicture(string $username, string $password){

        if(!$this->check_username($username)){
            return false;
        }

        $sql = "INSERT INTO users(username, password) VALUES (?, ?);";
        $stmt = $this->CONN->prepare($sql);

        $hash = password_hash($password, PASSWORD_ARGON2I);

        $results = $stmt->execute(array($username, $hash));

        if(!$results){
            return false;
        }

        return true;
    }

    // Publiska funckijas, kas pārbauda lietotāja autentifikācijas datus un saderības gadījumā atgriež lietotāja datus
    public function login(string $username, string $password){
        $user = $this->get_user($username);
        if(!$user){
            return false;
        }

        $real_hash = $user['password'];
        if(!password_verify($password, $real_hash)){
            return false;
        }

        return $user;
    }
}

?>