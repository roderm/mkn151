
-- Categorie Datatable
CREATE TABLE quizCategories
(
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
bezeichnung VARCHAR(50) NOT NULL,
beschreibung VARCHAR(250)
);

-- Quiz Questions
CREATE TABLE quizQuestions
(
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
categorie BIGINT NOT NULL,
question VARCHAR(100) NOT NULL,
answer0 VARCHAR(30),
answer1 VARCHAR(30),
answer2 VARCHAR(30),
answer3 VARCHAR(30)
);

-- Quiz Games
CREATE TABLE quizGames
(
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
guest VARCHAR(30),
userid BIGINT,
score INT,
duration INT
);

-- Quiz 
CREATE TABLE quizTurns
(
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
question BIGINT NOT NULL,
game BIGINT NOT NULL,
passed BIT,
INDEX (question, game)
);

-------------------------
-- FOREIGN KEYS
-------------------------
ALTER TABLE quizQuestions
ADD FOREIGN KEY (categorie) REFERENCES quizCategories(id) ON DELETE CASCADE;

ALTER TABLE quizTurns
ADD FOREIGN KEY (question) REFERENCES quizQuestions(id) ON DELETE CASCADE;

ALTER TABLE quizTurns
ADD FOREIGN KEY (game) REFERENCES quizGames(id) ON DELETE CASCADE;

-------------------------
-- modifying
-------------------------
-- Add joker span to turns
ALTER TABLE `quizTurns` CHANGE `passed` `selectedAnswer` TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE `quizTurns` ADD `joker` BIT NULL DEFAULT NULL AFTER `selectedAnswer`;


-- GAME->duration is computed-Field
ALTER TABLE `quizGames` DROP `duration`;
ALTER TABLE `quizGames` ADD `start` DATETIME NULL FIRST, ADD `stop` DATETIME NULL AFTER `start`;
ALTER TABLE `quizGames` CHANGE `start` `start` DATETIME NULL DEFAULT NULL AFTER `score`;
ALTER TABLE `quizGames` CHANGE `stop` `stop` DATETIME NULL DEFAULT NULL AFTER `start`;

-- View erstellen
CREATE VIEW `ViewQuestionQuote` AS
SELECT quizQuestions.id,
	quizQuestions.categorie,
	quizQuestions.question,
	quizQuestions.answer0,
	quizQuestions.answer1,
	quizQuestions.answer2,
	quizQuestions.answer3,
	COUNT(quizTurns.id) as counts,
	SUM(CASE WHEN selectedAnswer=0 THEN 1 ELSE 0 END) as counts_ans0,
	SUM(CASE WHEN selectedAnswer=1 THEN 1 ELSE 0 END) as counts_ans1,
	SUM(CASE WHEN selectedAnswer=2 THEN 1 ELSE 0 END) as counts_ans2,
	SUM(CASE WHEN selectedAnswer=3 THEN 1 ELSE 0 END) as counts_ans3,
    SUM(CASE WHEN selectedAnswer IS NULL THEN 1 ELSE 0 END)-1 as counts_joker
FROM quizQuestions
LEFT JOIN quizTurns ON quizTurns.question = quizQuestions.id
GROUP BY quizQuestions.id, quizQuestions.question

CREATE VIEW `ViewHighscore` AS
SELECT 
    SUM(CASE WHEN quizTurns.selectedAnswer != 0 THEN 1 ELSE 0 END) AS faildGames,
    quizGames.id as GameID,
    guest as GamerName,
    DATE_FORMAT(quizGames.start,'%e.%c.%Y') as GameStart,
    TIME_TO_SEC(TIMEDIFF(quizGames.stop, quizGames.start)) as GameDuration,
    SUM(CASE WHEN quizTurns.selectedAnswer = 0 then 30 ELSE 0 END) as GamerPoints,
    ROUND(SUM(CASE WHEN quizTurns.selectedAnswer = 0 then 30 ELSE 0 END) / 
    	TIME_TO_SEC(TIMEDIFF(quizGames.stop, quizGames.start)), 2) as GamerScore,
    ROUND((SUM(CASE WHEN quizTurns.selectedAnswer = 0 then 30 ELSE 0 END) / 
    	TIME_TO_SEC(TIMEDIFF(quizGames.stop, quizGames.start))) *
      	SUM(CASE WHEN quizTurns.selectedAnswer = 0 then 30 ELSE 0 END), 0) as GamerMainScore,
	group_concat(quizCategories.bezeichnung) as GameCategories
FROM quizGames 
	INNER JOIN quizTurns ON quizTurns.game = quizGames.id 
    INNER JOIN quizQuestions ON quizQuestions.id = quizTurns.question 
    INNER JOIN quizCategories ON quizCategories.id = quizQuestions.categorie 
WHERE quizGames.stop IS NOT NULL
GROUP BY quizGames.id
HAVING faildGames = 0
