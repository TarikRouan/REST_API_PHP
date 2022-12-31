<?php

class MovieGateWay
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll() : array
    {
        $sql = "SELECT * From movies";

        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    public function create (array $data): string
    {
        $sql = "INSERT INTO movies (title, year, genre, poster, imdbRating)
                VALUES (:title, :year, :genre, :poster, :imdbRating)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":title", $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(":year", $data["year"], PDO::PARAM_INT);
        $stmt->bindValue(":genre", $data["genre"], PDO::PARAM_STR);
        $stmt->bindValue(":poster", $data["poster"], PDO::PARAM_STR);
        $stmt->bindValue(":imdbRating", $data["imdbRating"], PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();

    }
    public function get(string $id): array | false
    {
        $sql = "SELECT * FROM movies
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    public function update(array $current, array $new): int
    {
        $sql = "UPDATE movies
                SET title = :title, year = :year, genre = :genre, poster = :poster, imdbRating = :imdbRating
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":title", $new["title"] ?? $current["title"], PDO::PARAM_STR);
        $stmt->bindValue(":year", $new["year"] ?? $current["year"], PDO::PARAM_INT);
        $stmt->bindValue(":genre", $new["genre"] ?? $current["genre"], PDO::PARAM_STR);
        $stmt->bindValue(":poster", $new["poster"] ?? $current["poster"], PDO::PARAM_STR);
        $stmt->bindValue(":imdbRating", $new["imdbRating"] ?? $current["imdbRating"], PDO::PARAM_STR);

        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM movies 
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();
        
        return $stmt->rowCount();
    } 
}


