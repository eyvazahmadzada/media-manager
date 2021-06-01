<?php
    class Item {
        // DB config
        private $connection;
        private $table = 'items';

        //POST Properties
        public $id;
        public $img;
        public $x;
        public $y;
        public $width;
        public $height;

        // Constructor
        public function __construct($db) {
            $this->connection = $db;
        }

        // Get Items
        public function getItems() {
            // Query
            $query = 'SELECT * FROM ' . $this->table;

            // Prepare statement
            $stmt = $this->connection->prepare($query);

            // Execute
            $stmt->execute();

            return $stmt;
        }

        // Get single item
        public function getItem() {
            // Query
            $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';

            // Prepare statement
            $stmt = $this->connection->prepare($query);

            // Security measures
            $this->id = htmlspecialchars(strip_tags($this->id));

            //Bind params
            $stmt->bindParam(':id', $this->id);

            // Execute
            $stmt->execute();

            return $stmt;
        }

        // Create Item
        public function createItem() {
            // Query
            $query = 'INSERT INTO ' . $this->table . ' VALUES(:id, :img, :x, :y, :width, :height)';

            // Prepare statement
            $stmt = $this->connection->prepare($query);

            // Security measures
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->img = htmlspecialchars(strip_tags($this->img));
            $this->x = htmlspecialchars(strip_tags($this->x));
            $this->y = htmlspecialchars(strip_tags($this->y));
            $this->width = htmlspecialchars(strip_tags($this->width));
            $this->height = htmlspecialchars(strip_tags($this->height));

            //Bind params
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':img', $this->img);
            $stmt->bindParam(':x', $this->x);
            $stmt->bindParam(':y', $this->y);
            $stmt->bindParam(':width', $this->width);
            $stmt->bindParam(':height', $this->height);

            // Execute
            if($stmt->execute()) {
                return true;
            }
            
            // Return error
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Update Item
        public function updateItem() {
            // Query
            $query = 'UPDATE ' . $this->table . ' 
                SET x = :x, y = :y, width = :width, height = :height
                WHERE id = :id';

            // Prepare statement
            $stmt = $this->connection->prepare($query);

            // Security measures
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->x = htmlspecialchars(strip_tags($this->x));
            $this->y = htmlspecialchars(strip_tags($this->y));
            $this->width = htmlspecialchars(strip_tags($this->width));
            $this->height = htmlspecialchars(strip_tags($this->height));

            //Bind params
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':x', $this->x);
            $stmt->bindParam(':y', $this->y);
            $stmt->bindParam(':width', $this->width);
            $stmt->bindParam(':height', $this->height);

            // Execute
            if($stmt->execute()) {
                return true;
            }
            
            // Return error
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete Item
        public function deleteItem() {
            // Query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // Prepare statement
            $stmt = $this->connection->prepare($query);

            // Security measures
            $this->id = htmlspecialchars(strip_tags($this->id));

            //Bind params
            $stmt->bindParam(':id', $this->id);

            // Execute
            if($stmt->execute()) {
                return true;
            }
            
            // Return error
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }