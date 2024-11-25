create database cascofonie;

DROP TABLE texts
DROP TABLE articles
DROP TABLE amendments
DROP TABLE roles
DROP TABLE users

-- TABLE : TEXTS ---------------------------------------------------------

CREATE TABLE cascofonie.texts (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(55) NOT NULL,
    promulgation BOOLEAN NOT NULL,
    CONSTRAINT pk_texts PRIMARY KEY (id)
)

-- TABLE : ARTICLES ------------------------------------------------------

CREATE TABLE cascofonie.articles (
    idText INT NOT NULL,
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(55) NOT NULL,
    text VARCHAR(255) NULL,
    CONSTRAINT pk_articles PRIMARY KEY (idText, id)
    CONSTRAINT fk_articles_texts FOREIGN KEY (idText) REFERENCES texts(id)
)

-- TABLE : AMENDMENTS ----------------------------------------------------

CREATE TABLE cascofonie.amendments (
    idText INT NOT NULL,
    idArticle INT NOT NULL,
    id INT NOT NULL AUTO_INCREMENT,
    label VARCHAR(55) NOT NULL,
    text VARCHAR(255) NULL,
    date DATE NULL,
    CONSTRAINT pk_amendments PRIMARY KEY (idText, idArticle, id)
    CONSTRAINT fk_amendments_texts FOREIGN KEY (idText) REFERENCES texts(id)
    CONSTRAINT fk_amendments_articles FOREIGN KEY (idArticle) REFERENCES articles(id)
)

-- TABLE : ROLES ---------------------------------------------------------

CREATE TABLE cascofonie.roles (
    id INT NOT NULL AUTO_INCREMENT,
    label VARCHAR(55) NOT NULL,
    CONSTRAINT pk_roles PRIMARY KEY (id)
)

-- TABLE : USERS ---------------------------------------------------------

CREATE TABLE cascofonie.users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(55) NOT NULL,
    password VARCHAR (255) NOT NULL,
    role INT DEFAULT NULL,
    CONSTRAINT pk_users PRIMARY KEY (id),
    CONSTRAINT fk_users_roles FOREIGN KEY (role) REFERENCES roles(id)
)