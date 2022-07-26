<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;



$app->get('/expedition_statuses', function (Request $request, Response $response, $args) {
    $sql = "SELECT * FROM expedition_status";

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        $result = array(
            "status" => true,
            "current_records" => count($user),
            "records" => $user,
        );

        return $response->withStatus(200)->withJson($result);
        
    } catch (PDOException $e) {
        $result = array(
            "status" => false,
            "error" => array(
                "msg" => $e->getMessage()
            )
        );

        return $response->withStatus(200)->withJson($result);
    }

});
$app->get('/expedition_status/{status_id}', function (Request $request, Response $response, array $args) {
    $id = $args['status_id'];
    $sql = "SELECT * FROM expedition_status WHERE id = $id";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        $result = array(
            "status" => true,
            "record" => $user,
        );

        return $response->withStatus(200)->withJson($result);
        
    } catch (PDOException $e) {
        $result = array(
            "status" => false,
            "error" => array(
                "msg" => $e->getMessage()
            )
        );

        return $response->withStatus(200)->withJson($result);
    }

});
$app->post('/expedition_status', function (Request $request, Response $response, array $args) {
    
    $id = $_POST["id"];
    $status = $_POST["status"];
    
    

    try {
        $sql = "INSERT INTO expedition_status (id, status) 
        VALUES (:id, :status)";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
       
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':status', $status);
        

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;
    
        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $result = array(
            "status" => false,
            "error" => array(
                "msg" => $e->getMessage()
            )
        );

        return $response->withStatus(200)->withJson($result);
    }
});

$app->put('/expedition_status/{status_id}', function (Request $request, Response $response, array $args) {
    // $response->getBody()->write("this is post user....");
    $id = $args["status_id"];
    $data = $request->getParsedBody();
    $status = $data["status"];


    try {
        $sql = "UPDATE expedition_status SET status = :status WHERE id = $id";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        

        
        $stmt->bindParam(':status', $status);
      

    
        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;
    
        $data = array(
            "status" => "success put",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $result = array(
            "status" => false,
            "error" => array(
                "msg" => $e->getMessage()
            )
        );

        return $response->withStatus(200)->withJson($result);
    }
});

$app->delete('/expedition_status/{status_id}', function (Request $request, Response $response, array $args) {
  
    $id = $args['status_id'];

    try {
        $sql = "DELETE FROM expedition_status WHERE id = $id";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;
    
        $data = array(
            "rowDelete" => $count,
            "status" =>"success delete $id"
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $result = array(
            "status" => false,
            "error" => array(
                "msg" => $e->getMessage()
            )
        );

        return $response->withStatus(200)->withJson($result);
    }

});