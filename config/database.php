<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'restaurant_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
    }

    public function getRestaurants() {
        $query = 'SELECT * FROM restaurants';
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRestaurantDetails($id) {
        $query = 'SELECT * FROM restaurants WHERE id='.$id;
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addRestaurant($restaurant) {
        $query = 'INSERT INTO restaurants (name, address, phone) VALUES (:name, :address, :phone)';
        $stmt = $this->getConnection()->prepare($query);

        // Bind parameters
        $stmt->bindParam(':name', $restaurant['name']);
        $stmt->bindParam(':address', $restaurant['address']);
        $stmt->bindParam(':phone', $restaurant['phone']);

        // Execute the query
        return $stmt->execute();
    }
}
