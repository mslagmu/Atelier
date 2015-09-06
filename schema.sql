CREATE TABLE users(
	trigramme varchar[3],
	name varchar[50],
	email varchar,
	password varchar
	);
	
CREATE TABLE workshops(
		creator varchar[3],
		date    integer,
		location varchar,
		topic varchar,
		persons TEXT,
		followers  TEXT,
		cr	integer,
		comments TEXT
	);

CREATE TABLE config (
	date integer,
	admpwd TEXT
);

