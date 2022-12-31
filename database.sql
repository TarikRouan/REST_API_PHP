CREATE DATABASE rest_api_php;

use rest_api_php;

CREATE TABLE IF NOT EXISTS movies(
id int(30)  PRIMARY KEY NOT NULL AUTO_INCREMENT,
title varchar(255) NOT NULL,
year int NOT NULL,
genre varchar(255) NOT NULL,
poster text NOT NULL,
imdbRating varchar(4) NOT NULL
);

INSERT INTO movies  (title, year, Genre,poster,imdbRating)
VALUES
( "Avatar", 2009,"Action, Adventure, Fantasy","https://m.media-amazon.com/images/M/MV5BNjA3NGExZDktNDlhZC00NjYyLTgwNmUtZWUzMDYwMTZjZWUyXkEyXkFqcGdeQXVyMTU1MDM3NDk0._V1_FMjpg_UX1037_.jpg","7.8"),
( "I Am Legend", 2007,"Drama, Horror, Sci-Fi","https://m.media-amazon.com/images/M/MV5BYTE1ZTBlYzgtNmMyNS00ZTQ2LWE4NjEtZjUxNDJkNTg2MzlhXkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_.jpg","7.2"),
( "300", 2006,"Action, Drama, Fantasy","http://ia.media-imdb.com/images/M/MV5BMjAzNTkzNjcxNl5BMl5BanBnXkFtZTYwNDA4NjE3._V1_SX300.jpg","7.7");