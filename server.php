<?php
require_once 'database_manager.php';

header('Content-Type: application/json; charset=utf-8');

abstract Class ErrorType{

    // Abstrakta klase, kas simulē Enum objektu priekš error tipiem, kas ir attiecināmi JS definētajiem error tipiem

    // Error tips lietotājvārdu kļūdām
    const USERNAME = "username_error";

    // Error tips paroļu kļūdām
    const PASSWORD = "password_error";
    
    // Error tips nenoteiktām kļūdām
    const NONE = "none";
}

function out($message, bool $is_error=false, string $type=ErrorType::NONE){

    /*
                            out() - izvades funkcija

    Šo funkciju jālieto gadījumos ja serveris izvada informāciju klientam.
    Katru reizi kad kaut kas tiek izvadīts ar šo funkciju, skripts apstājas, tādā
    veidā samazinot if/else statement lietošanu.

    @author: CracX
    @example: out("Invalid username", $is_error=true, ErrorType::USERNAME);

    */

    switch($is_error){
        
        // Ja izvade ir kļūda
        case true:

            // Sūtam jebkādu error kodu lai frontend AJAX funkcija nofeilo
            http_response_code(404);

            // izveidojam ziņojumu attiecīgā formātā
            $out = array(
                "error" => true,
                "message" => $message,
                "type" => $type
            );
            break;
        
        // Ja izvade ir parasts ziņojums
        case false:

            // Sūtam success kodu
            http_response_code(200);

            // izveidojam ziņojumu attiecīgā formātā
            $out = array(
                "success" => true,
                "message" => $message
            );
            break;

        default:
            
            // Noklusējuma rezultātā izvadām parastu ziņojumu
            http_response_code(200);
            $out = array(
                "success" => true,
                "message" => $message
            );
    }

    // Liekam skriptam "nomirt" un neturpināt tālāk, izvadot ziņojumu
    die(json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// Ja action neeksistē, tad php izvada faila pilno lokāciju, tāpēc nosūtīsim lietotājam 404 error lapu.
if (!isset($_POST['action'])){
    http_response_code(404);
    die();
}

// Iniciējam klase
$db = new DatabaseManager();

// Ja klients vēlas pievienot lietotāju
if($_POST['action'] == "insert_user"){
  
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_ARGON2I);

    // Pārbaudam, vai lietotājvārds ir derīgs. Saglabājam funkcijas izvadi $res mainīgajā un skatamies kas pa error tiek izmests.
    if(!($res = $db->check_username($username))[0]){
        out($res[1] == 0 ? "Lietotājvārdam jābūt vismaz 5 simbolus garam" : "Lietotājvārds jau ir aizņemts", $is_error=true, $type=ErrorType::USERNAME);
    }

    // Pārbaudam, vai parole ir derīga. Šeit mums nevajag saglabāt izvadi, jo kļūdas gadījumā tiks izvadīts tikai viens ziņojums.
    if(!$db->check_password($password)[0]){
        out("Parolei jābūt vismaz 5 simbolus garai", $is_error=true, $type=ErrorType::PASSWORD);
    }

    // Ja viss kārtībā, reģistrējam lietotāju.
    if($db->register($username, $password)){
        out("Lietotājs veiksmīgi reģistrēts");
    }

    // Ja tomēr reģistrācija nebija veiksmīga, nosūtam 503. kodu signalozējot, ka kļūda ar datubāzi (šai līnijai nevajadzētu tikt sasniegtai).
    http_response_code(503);
}
?>