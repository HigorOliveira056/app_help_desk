<?php
    namespace DB;
    use Mysqli;

    $config_file = fopen("config.json", "r") or die ("Unable to open file");
    $configs =  fread($config_file, filesize("config.json"));
    $config_db = json_decode($configs);
    fclose($config_file);

    class Connection{
        
        private $servername;
        private $user;
        private $password;
        private $database;
        private $conn = NULL;
        private function retrieve_config(){
            global $config_db;
            $this->servername = $config_db->servername;
            $this->user = $config_db->user;
            $this->password = $config_db->password;
            $this->database = $config_db->database;
        }

        public function connect(){
            $this->retrieve_config();
            $conn = new mysqli($this->servername, $this->user, $this->password, $this->database);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $this->conn = $conn;
            return $conn;
        }
        public function closeConn(){
            if($this->conn != NULL){
                $this->conn->close();
            }
        }
        private function insert($query){
            if($this->conn->query($query) === TRUE){
                echo "inserido com sucesso";
            }
            else{
                echo $this->conn->error;
            }
              
        }
        public function insertUser($name, $email, $password){
            $query = "INSERT INTO users (name, email, password) VALUE('{$name}', '{$email}', '{$password}')";
            $this->insert($query);
        }
        public function insertCalled($id, $title, $category, $description){
            $query = "INSERT INTO called (id, title, category, description) VALUE('{$id}', '{$title}', '{$category}', '{$description}')";
            $this->insert($query);
        }
        public function retrieveUsers(){
            $query = "SELECT id, name, email FROM users";
            $result = [];
            $statement = $this->conn->query($query);
            if($statement->num_rows > 0){
                while($row = $statement->fetch_assoc()){
                    array_push($result, 
                    [
                        "id" => $row["id"], 
                        "name" => $row["name"],
                        "email" => $row["email"],
                        
                        ]
                    );
                }
            }
            return $result;
        }
        public function retrieveCalled(){
            $query = "SELECT * FROM called";
            $result = [];
            $statement = $this->conn->query($query);
            if($statement->num_rows > 0){
                while($row = $statement->fetch_assoc()){
                        array_push($result, 
                        [
                            "id" => $row["id"], 
                            "title" => $row["title"],
                            "category" => $row["category"],
                            "description" => $row["description"]
                        ]
                    );
                }
            }
            return $result;
        }
        private function update($query){
            if( $this->conn->query($query) === TRUE){
            
                echo "update";
                
            }
            echo $this->conn->error;
        }
        public function updateEmail($id, $email){
            $query = "UPDATE users SET email='{$email}' WHERE id=$id";
            $this->update($query);
        }
        public function updatePassword($id, $password){
            $query = "UPDATE users SET password=$password WHERE=$id";
            $this->update($query);
        }
        public function deleteUser($id){
            $query = "DELETE FROM users WHERE id=$id";
            $this->conn->query($query);
        }
        public function retrieveAuthentication(){
            $query = "SELECT email, password FROM users";
            $result = [];
            $statement = $this->conn->query($query);
            if($statement->num_rows > 0){
                while($row = $statement->fetch_assoc()){
                    array_push($result, 
                        [
                            "email" => $row["email"],
                            "password" => $row["password"],
                        ]
                    );
                }
            }
            return $result;
        }
        public function getId($email){
            foreach($this->retrieveUsers() as $user => $item){
                if($email == $item["email"]){
                    return $item["id"];
                }
            }
            
        }
    }
?>