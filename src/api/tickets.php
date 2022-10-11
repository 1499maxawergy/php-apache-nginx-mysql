<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
header('Content-Type: application/json');
$con = new mysqli("MYSQL", "user", "toor", "appDB");
$answer = array(
    "status" => "",
);
switch($requestMethod){
    case 'GET':
        if (empty(isset($_GET['id']))){
            $result = $con->query("SELECT * FROM tickets;");
            while($row = $result->fetch_assoc()){
                $answer[] = $row;
            }
        } else {
            $result = $con->query("SELECT * FROM tickets WHERE ID = " . $_GET['id'] . ";");
            while($row = $result->fetch_assoc()){
                $answer[] = $row;
            }
        }
        if (count($answer) == 1){
            $answer["status"] = "Error. Ticket(-s) not found.";
        } else {
            $answer["status"] = "Success. Ticket(-s) found";
        }
        echo json_encode($answer);
        break;
    case 'POST':
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json);
        if (!empty($obj->{'price'}) && !empty($obj->{'source'}) && !empty($obj->{'destination'}) && !empty($obj->{'title'})){
            $price = $obj->{'price'};
            $source = $obj->{'source'};
            $destination = $obj->{'destination'};
            $title = $obj->{'title'};
            $query_result = $con->query("SELECT * FROM tickets WHERE title='".$title."'");
            $result=$query_result->fetch_row();
            if (!empty($result)){
                $answer["status"] = "Error. Ticket with this title already exists.";
            } else {
                $stmt = $con->prepare("INSERT INTO tickets (price, source, destination, title) VALUES (?, ?, ?, ?)");
                $stmt->bind_param('ssss', $price, $source, $destination, $title);
                $stmt->execute();
                $answer["status"] = "Success. Ticket created.";
            }
        } else {
            $answer["status"] = "Error. Need price, source, destination and title in JSON BODY.";
        }
        echo json_encode($answer);
        break;
    case 'PATCH':
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json);
        if (!empty($obj->{'price'}) && !empty($obj->{'source'}) && !empty($obj->{'destination'}) && !empty($obj->{'title'})){
            if (empty(isset($_GET['id']))){
                $answer["status"] = "Error. Need ID Param";
            } else {
                $query_result = $con->query("SELECT * FROM tickets WHERE ID='".$_GET['id']."'");
                $result = $query_result->fetch_row();
                if (!empty($result)){
                    $query_result = $con->query("SELECT * FROM tickets WHERE title='".$obj->{'title'}."' AND ID!='".$_GET['id']."'");
                    $result = $query_result->fetch_row();
                    if (!empty($result)){
                    $answer["status"] = "Error. Ticket with this title already exists.";
                    } else {
                    $con->query("UPDATE tickets SET price='".$obj->{'price'}."', source='".$obj->{'source'}."' , destination='".$obj->{'destination'}."' , title='".$obj->{'title'}."' WHERE ID='".$_GET['id']."'");
                    $answer["status"] = "Success. Ticket updated.";
                    }
                } else {
                    $answer["status"] = "Error. Ticket not found.";
                }
            }
        } else {
            $answer["status"] = "Error. Need price, source, destination and title in JSON BODY.";
        }
        echo json_encode($answer);
        break;
    case 'DELETE':
        if (empty(isset($_GET['id']))){
            $answer["status"] = "Error. Need ID Param";
        } else {
            $query_result = $con->query("SELECT * FROM tickets WHERE ID='".$_GET['id']."'");
            $result = $query_result->fetch_row();
            if (!empty($result)){
                $query_result = $con->query("DELETE FROM tickets WHERE ID='".$_GET['id']."'");
                $answer["status"] = "Success. Ticket Deleted.";
            } else {
                $answer["status"] = "Error. Ticket not found.";
            }
        }
        echo json_encode($answer);
        break;
    default:
        $answer["status"] = "This REST Method not allowed.";
        echo json_encode($answer);
        break;
}
?>