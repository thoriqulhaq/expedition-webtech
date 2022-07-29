<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


// GET ALL USERS
$app->get('/users', function( Request $request, Response $response){
    $custom_limit = $request->getParam("limit");
    $custom_page = $request->getParam("page");
    
    // Pagination
    $limit = 10;
    $currentPage = 1;
   
    
    if ($custom_limit) {
        $limit = (int)$custom_limit;
    }
    
    if ($custom_page) {
        $currentPage = (int)$custom_page;
    }
    
    $offset = $limit * ($currentPage - 1);

    $sql = "SELECT * FROM user LIMIT $limit OFFSET $offset";
    $sql_count = "SELECT COUNT(*) FROM user";

    try {
        $db = new db();
        $db = $db->connect();

        $response_data = $db->query( $sql );
        $response_count = $db->query( $sql_count );
        
        $data = $response_data->fetchAll( PDO::FETCH_OBJ );
        $count = $response_count->fetchColumn();
        $db = null;
        
        if (0 >= $currentPage ||$currentPage > ceil($count / $limit)) {
            echo '{"msg" : "Reached the maximum page number"}';
        } else {
            echo '{
                "current_records":' . json_encode(count($data)) . ',
                "total_records":' . json_encode((int)$count) . ',
                "records":' . json_encode($data) . ',
                "current_page":' . json_encode($currentPage) . ',
                "total_pages":' . json_encode(ceil($count / $limit)) . ',
                "limit":' . json_encode($limit) . '
            }'; 
        }

        
    } catch( PDOException $e ) {
        echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
});

// GET USERS BY ID
$app->get('/users/{user_id}', function( Request $request, Response $response){
    $id = $request->getAttribute('user_id');
    
    $sql = "SELECT * FROM user WHERE ID = '$id'";
  
   try {
     $db = new db();
  
     $db = $db->connect();
  
     $response = $db->query( $sql );
     $data = $response->fetchAll( PDO::FETCH_OBJ );
     $db = null;
     
    echo json_encode($data);

   } catch( PDOException $e ) {
     echo '{"error": {"msg": ' . $e->getMessage() . '}';
   }
  });

  // REGISTER USERS
  $app->post('/register', function( Request $request, Response $response){

    $name = $request->getParam("name");
    $email = $request->getParam("email");
    $password = $request->getParam("password");
    $userType = $request->getParam("userType");
    
    $sql = "INSERT INTO user (name, email, password, userType)
            VALUES (:name, :email, :password, :userType)";
  
    try {
        $db = new db();
        $db = $db->connect();
        $request = $db->prepare( $sql );
  
        if (empty($name) || empty($email) || empty($password) || empty($userType)) {
            $result = array(
                "status" => false,
                "msg" => "Please Fill All Fields",
                "data" => [],
            );
            return $response->withStatus(200)->withJson($result);
        } 

        else{
            $request->bindParam(':name', $name);
            $request->bindParam(':email', $email);
            $request->bindParam(':password', $password);
            $passwordEncrypt = password_hash($password, PASSWORD_DEFAULT);
            $password = $passwordEncrypt;
            $request->bindParam(':userType', $userType);
            $request->execute();

            $result = array(
                "status" => true,
                "message" => "Successfully Registered"
            );

            return $response->withStatus(200)->withJson($result);
        }
  
    } catch( PDOException $e ) {
        echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
  });

//PUT USERS
$app->put('/users/{user_id}', function( Request $request, Response $response){
    $id = $request->getAttribute('user_id');
    $name = $request->getParam("name");
    $email = $request->getParam("email");
    $password = $request->getParam("password");
    
    $sql = "UPDATE user SET name = '$name', email = '$email', password = '$password' WHERE ID = '$id'";
  
    try {
        $db = new db();
        $db = $db->connect();
        $request = $db->prepare( $sql );
  
        $request->bindParam(':id', $id);
        $request->bindParam(':name', $name);
        $request->bindParam(':email', $email);
        $request->bindParam(':password', $password);   
    
        $request->execute();
        
        echo '{"msg" : "Profile Successfully Updated"}';
  
    } catch( PDOException $e ) {
        echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
  });

  // DELETE USERS
    $app->delete('/users/{user_id}', function( Request $request, Response $response){
        $id = $request->getAttribute('user_id');
        
        $sql = "DELETE FROM user WHERE ID = '$id'";
    
        try {
            $db = new db();
            $db = $db->connect();
            $request = $db->prepare( $sql );

        
            $request->execute();
            
            echo '{"msg" : "User Successfully Deleted"}';
    
        } catch( PDOException $e ) {
            echo '{"error": {"msg": ' . $e->getMessage() . '}';
        }
    });

    // User Login
    $app->post('/login', function( Request $request, Response $response){
        $email = $request->getParam("email");
        $password = $request->getParam("password");
        

        $sql = "SELECT * FROM user WHERE email = '$email'";
    
        try {
            $db = new db();
            $db = $db->connect();
            $request = $db->query( $sql );
            $data = $request->fetchAll( PDO::FETCH_OBJ );
            $db = null;
            
            if (empty($email) || empty($password)) {
                $result = array(
                    "status" => false,
                    "msg" => "Form can not be empty",
                    "data" => [],
                );
                return $response->withStatus(200)->withJson($result);
            }


            else {
            $count = count($data);

                if ($count > 0) {
                $users = $data;
                foreach ($users as $user) {
                $passwordHash = $user->password;
                    if (password_verify($password, $passwordHash)) {
                        $result = array(
                            "status" => true,
                            "msg" => "Login Success",
                            "data" => $users[0],
                        );
                        return $response->withStatus(200)->withJson($result);
                    } else {
                        $result = array(
                            "status" => false,
                            "msg" => "Wrong Password"
                          
                        );
                        return $response->withStatus(200)->withJson($result);
                }
            }
        } else {
            $result = array(
                "status" => false,
                "msg" => "Email not registered"
            );
            return $response->withStatus(200)->withJson($result);
        }
    }
    
        } catch( PDOException $e ) {
            echo '{"error": {"msg": ' . $e->getMessage() . '}';
        }
    });
