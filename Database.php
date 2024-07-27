<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'story';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }

    public function createTable()
    {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS reactions (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                id_story INT(11) NOT NULL,
                `like` INT(11) DEFAULT 0,
                not_like INT(11) DEFAULT 0
            )";
            $this->conn->exec($sql);

            $sql = "CREATE TABLE IF NOT EXISTS joker (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                content TEXT NOT NULL
            )";
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            echo 'Table Creation Error: ' . $e->getMessage();
        }
    }

    public function updateReaction($id_story, $like, $not_like)
    {
        try {
            $sql = "INSERT INTO reactions (id_story, `like`, not_like) VALUES (:id_story, :like, :not_like)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_story', $id_story);
            $stmt->bindParam(':like', $like);
            $stmt->bindParam(':not_like', $not_like);
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Insert Error: ' . $e->getMessage();
        }
    }

    public function getNextJoke($currentJokeId)
    {
        try {
            $sql = "SELECT id, content FROM joker WHERE id > :currentJokeId ORDER BY id ASC LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':currentJokeId', $currentJokeId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Fetch Next Joke Error: ' . $e->getMessage();
        }
    }

    public function insertContent($content)
    {
        try {
            $sql = "SELECT COUNT(*) FROM joker WHERE content = :content";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':content', $content);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                $sql = "INSERT INTO joker (content) VALUES (:content)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':content', $content);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            echo 'Insert Content Error: ' . $e->getMessage();
        }
    }

    public function insertMultipleContents($contents)
    {
        foreach ($contents as $content) {
            $this->insertContent($content);
        }
    }
}
