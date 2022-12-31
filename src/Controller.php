<?php

class Controller
{
    public function __construct(private MovieGateWay $gateway)
    {
    }
    public function processRequest(string $method, ?string $id): void
    {
        // var_dump($method, $id);
        if ($id) {

            $this->processResourceRequest($method, $id);
        } else {

            $this->processCollectionRequest($method);
        }
    }
    private function processResourceRequest(string $method, string $id): void
    {
        $movie = $this->gateway->get($id);

        if (!$movie) {
            http_response_code(404);
            echo json_encode(["message" => "Movie not found"]);
            return;
        }

        switch ($method) {
            case "GET":
                echo json_encode($movie);
                break;
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);


                $errors = $this->getValidationErrors($data, false);

                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }


                $rows = $this->gateway->update($movie, $data);

                echo json_encode([
                    "message" => "Movie $id updated",
                    "rows" => $rows
                ]);
                break;
            case 'DELETE':
                $rows = $this->gateway->delete($id);
                echo json_encode([
                    "message" => "movie $id deleted",
                    "rows" => $rows
                ]);
                break;
            default:
                $this->responseMethodNotAllowed("GET, PATCH, DELETE");
        }
    }


    private function responseMethodNotAllowed(string $allowed_methods): void
    {
        Header("Allow: $allowed_methods");
        // http_response_code(405);
        echo json_encode(["message" => "Invalid Request"]);
    }

    private function processCollectionRequest(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data);

                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $id = $this->gateway->create($data);

                http_response_code(201);

                echo json_encode([
                    "message" => "Movie created",
                    "id" => $id
                ]);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        if ($is_new && empty($data["title"])) {

            $errors[] = "title is required";
        }

        if (array_key_exists("year", $data)) {
            if (filter_var($data["year"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "year must be an integer";
            }
        } else if ($is_new) {
            $errors[] = "year is required";
        }

        if ($is_new && empty($data["genre"])) {

            $errors[] = "genre is required";
        }

        if ($is_new && empty($data["poster"])) {

            $errors[] = "The poster link is required";
        }

        if ($is_new && empty($data["imdbRating"])) {

            $errors[] = "Imdb Rating is required";
        }
        return $errors;
    }
}
