<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


// GET ALL ITEMS
$app->get('/items', function( Request $request, Response $response){
    $sql = "SELECT * FROM ITEM";

    try {
        $db = new db();
        $db = $db->connect();

        $response = $db->query( $sql );
        $data = $response->fetchAll( PDO::FETCH_OBJ );
        $db = null;
        
        // Modify the data to include the user description //
        for( $i = 0; $i < count( $data ); $i++ ){
            $user_id = $data[$i]->userid;
            if($user_id != null ) {
                $get_user = "SELECT * FROM USER WHERE ID = $user_id";
            
                try {
                    $db = new db();
                    $db = $db->connect();
                    
                    $response = $db->query( $get_user );
                    $user = $response->fetchAll( PDO::FETCH_OBJ );
                    $db = null;
                    
                    if(count($user) == 1){
                        $data[$i]->user = [
                            'id' => $user_id,
                            'name' => $user[0]->name,
                            'email' => $user[0]->email,
                        ];
                    } else {
                        $data[$i]->user = [
                            'id' => $user_id,
                            'name' => 'Unknown',
                            'email' => 'Unknown',
                        ];
                    }
                    
                } catch( PDOException $e ) {
                    echo '{"error": {"msg": ' . $e->getMessage() . '}';
                }
            } else {
                $data[$i]->user = [
                    'id' => $user_id,
                    'name' => 'Unknown',
                    'email' => 'Unknown',
                ];
            }
        }
        ////////////////////////////////////////////////////////
        
        // Modify the data to include the status description //
        for( $i = 0; $i < count( $data ); $i++ ){
            $status_id = $data[$i]->statusid;
            if($status_id != null ) {
                $get_status = "SELECT * FROM EXPEDITION_STATUS WHERE ID = $status_id";
            
                try {
                    $db = new db();
                    $db = $db->connect();
                    
                    $response = $db->query( $get_status );
                    $status = $response->fetchAll( PDO::FETCH_OBJ );
                    $db = null;
                    
                    if(count($status) == 1){
                        $data[$i]->status = [
                            'id' => $status_id,
                            'name' => $status[0]->status
                        ];
                    } else {
                        $data[$i]->status = [
                            'id' => $status_id,
                            'name' => 'Undefined'
                        ];
                    }
                    
                } catch( PDOException $e ) {
                    echo '{"error": {"msg": ' . $e->getMessage() . '}';
                }
            } else {
                $data[$i]->status = [
                    'id' => $status_id,
                    'name' => 'Undefined'
                ];
            }
        }
        ////////////////////////////////////////////////////////
        
        // Modify the data to include the courier description //
        for( $i = 0; $i < count( $data ); $i++ ){
            $courier_id = $data[$i]->courierid;
            if($courier_id != null ) {
                $get_courier = "SELECT * FROM COURIER WHERE ID = $courier_id";
        
                try {
                    $db = new db();
                    $db = $db->connect();
                    
                    $response = $db->query( $get_courier );
                    $courier = $response->fetchAll( PDO::FETCH_OBJ );
                    $db = null;
                    
                    if(count($courier) == 1){
                        $data[$i]->courier = [
                            'id' => $courier_id,
                            'name' => $courier[0]->name
                        ];
                    } else {
                        $data[$i]->courier = [
                            'id' => $courier_id,
                            'name' => 'Unknown'
                        ];
                    }
                    
                } catch( PDOException $e ) {
                    echo '{"error": {"msg": ' . $e->getMessage() . '}';
                }
            } else {
                $data[$i]->courier = [
                    'id' => $courier_id,
                    'name' => 'Unknown'
                ];
            }
        }
        ////////////////////////////////////////////////////////
        
        echo '{
            "total_records":' . json_encode(count($data)) . ',
            "records":' . json_encode($data) . '
        }'; 
        
    } catch( PDOException $e ) {
        echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
});

// GET ITEM BY
$app->get('/item/{item_id}', function( Request $request, Response $response){
    $id = $request->getAttribute('item_id');
    
    $sql = "SELECT * FROM ITEM WHERE ID = '$id'";
  
   try {
     $db = new db();
  
     $db = $db->connect();
  
     $response = $db->query( $sql );
     $data = $response->fetchAll( PDO::FETCH_OBJ );
     $db = null;
     
        // Modify the data to include the user description //
        $user_id = $data[0]->userid;
        if($user_id != null ) {
            $get_user = "SELECT * FROM USER WHERE ID = $user_id";
        
            try {
                $db = new db();
                $db = $db->connect();
                
                $response = $db->query( $get_user );
                $user = $response->fetchAll( PDO::FETCH_OBJ );
                $db = null;
                
                if(count($user) == 1){
                    $data[0]->user = [
                        'id' => $user_id,
                        'name' => $user[0]->name,
                        'email' => $user[0]->email,
                    ];
                } else {
                    $data[0]->user = [
                        'id' => $user_id,
                        'name' => 'Unknown',
                        'email' => 'Unknown',
                    ];
                }
                
            } catch( PDOException $e ) {
                echo '{"error": {"msg": ' . $e->getMessage() . '}';
            }
        } else {
            $data[0]->user = [
                'id' => $user_id,
                'name' => 'Unknown',
                'email' => 'Unknown',
            ];
        }
        ////////////////////////////////////////////////////////
  
        // Modify the data to include the status description //
        $status_id = $data[0]->statusid;
        if($status_id != null ) {
            $get_status = "SELECT * FROM EXPEDITION_STATUS WHERE ID = $status_id";
        
            try {
                $db = new db();
                $db = $db->connect();
                
                $response = $db->query( $get_status );
                $status = $response->fetchAll( PDO::FETCH_OBJ );
                $db = null;
                
                if(count($status) == 1){
                    $data[0]->status = [
                        'id' => $status_id,
                        'name' => $status[0]->status
                    ];
                } else {
                    $data[0]->status = [
                        'id' => $status_id,
                        'name' => 'Undefined'
                    ];
                }
                
            } catch( PDOException $e ) {
                echo '{"error": {"msg": ' . $e->getMessage() . '}';
            }
        } else {
            $data[0]->status = [
                'id' => $status_id,
                'name' => null
            ];
        }
    ////////////////////////////////////////////////////////
    
    // Modify the data to include the courier description //
        $courier_id = $data[0]->courierid;
        if($courier_id != null ) {
            $get_courier = "SELECT * FROM COURIER WHERE ID = $courier_id";
    
            try {
                $db = new db();
                $db = $db->connect();
                
                $response = $db->query( $get_courier );
                $courier = $response->fetchAll( PDO::FETCH_OBJ );
                $db = null;
                
                if(count($courier) == 1){
                    $data[0]->courier = [
                        'id' => $courier_id,
                        'name' => $courier[0]->name
                    ];
                } else {
                    $data[0]->courier = [
                        'id' => $courier_id,
                        'name' => 'Unknown'
                    ];
                }
                
            } catch( PDOException $e ) {
                echo '{"error": {"msg": ' . $e->getMessage() . '}';
            }
        } else {
            $data[0]->courier = [
                'id' => $courier_id,
                'name' => null
            ];
        }
    ////////////////////////////////////////////////////////
            
    echo '{
        "record":' . json_encode($data[0]) . '
    }'; 
       
   } catch( PDOException $e ) {
     echo '{"error": {"msg": ' . $e->getMessage() . '}';
   }
  });
  
  
  // POST ITEM
  $app->post('/item', function( Request $request, Response $response){
    $id = $request->getParam("id");
    
    $userid = $request->getParam("userid");
    $item_description = $request->getParam("item_description");
    $weight = $request->getParam("weight");
    $price = $request->getParam("price");
    $sender_address = $request->getParam("sender_address");
    $receiver_address = $request->getParam("receiver_address");
    $receiver_name = $request->getParam("receiver_name");
    $sender_contact = $request->getParam("sender_contact");
    $receiver_contact = $request->getParam("receiver_contact");
    $statusid = $request->getParam("statusid");
    $courierid = $request->getParam("courierid");
    
    $sql = "INSERT INTO ITEM (id, userid, item_description, weight, price, sender_address, receiver_address, receiver_name, sender_contact, receiver_contact, statusid, courierid)
            VALUES (:id, :userid, :item_description, :weight, :price, :sender_address, :receiver_address, :receiver_name, :sender_contact, :receiver_contact, :statusid, :courierid)";
  
    try {
        $db = new db();
        $db = $db->connect();
        $request = $db->prepare( $sql );
  
        $request->bindParam(':id', $id);
        $request->bindParam(':userid', $userid);
        $request->bindParam(':item_description', $item_description);
        $request->bindParam(':weight', $weight);
        $request->bindParam(':price', $price);
        $request->bindParam(':sender_address', $sender_address);
        $request->bindParam(':receiver_address', $receiver_address);
        $request->bindParam(':receiver_name', $receiver_name);
        $request->bindParam(':sender_contact', $sender_contact);
        $request->bindParam(':receiver_contact', $receiver_contact);
        $request->bindParam(':statusid', $statusid);
        $request->bindParam(':courierid', $courierid);
        
    
        $request->execute();
        
        echo '{"msg" : "Successfully added new item"}';
  
    } catch( PDOException $e ) {
        echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
  });
  
// UPDATE ITEM
$app->put('/item/{item_id}', function( Request $request, Response $response){
    $id = $request->getAttribute('item_id');
    
    $userid = $request->getParam("userid");
    $item_description = $request->getParam("item_description");
    $weight = $request->getParam("weight");
    $price = $request->getParam("price");
    $sender_address = $request->getParam("sender_address");
    $receiver_address = $request->getParam("receiver_address");
    $receiver_name = $request->getParam("receiver_name");
    $sender_contact = $request->getParam("sender_contact");
    $receiver_contact = $request->getParam("receiver_contact");
    $statusid = $request->getParam("statusid");
    $courierid = $request->getParam("courierid");
    
    $sql = "UPDATE ITEM SET userid = :userid, item_description = :item_description, weight = :weight, price = :price, sender_address = :sender_address, receiver_address = :receiver_address, receiver_name = :receiver_name, sender_contact = :sender_contact, receiver_contact = :receiver_contact, statusid = :statusid, courierid = :courierid WHERE id = '$id'";
  
    try {
        $db = new db();
        $db = $db->connect();
        $request = $db->prepare( $sql );
  
        $request->bindParam(':userid', $userid);
        $request->bindParam(':item_description', $item_description);
        $request->bindParam(':weight', $weight);
        $request->bindParam(':price', $price);
        $request->bindParam(':sender_address', $sender_address);
        $request->bindParam(':receiver_address', $receiver_address);
        $request->bindParam(':receiver_name', $receiver_name);
        $request->bindParam(':sender_contact', $sender_contact);
        $request->bindParam(':receiver_contact', $receiver_contact);
        $request->bindParam(':statusid', $statusid);
        $request->bindParam(':courierid', $courierid);
        
        $request->execute();
        
        echo '{"msg" : "Successfully updated item with id ' . $id . '"}';
    } catch( PDOException $e ) {
        echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
});

// DELETE ITEM
$app->delete('/item/{item_id}', function( Request $request, Response $response){
    $id = $request->getAttribute('item_id');
    
    $sql = "DELETE FROM ITEM WHERE id = :id";
  
    try {
        $db = new db();
        $db = $db->connect();
        $request = $db->prepare( $sql );
  
        $request->bindParam(':id', $id);
        
        $request->execute();
        
        echo '{"msg" : "Successfully deleted item with id ' . $id . '"}';
    } catch( PDOException $e ) {
        echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
});
