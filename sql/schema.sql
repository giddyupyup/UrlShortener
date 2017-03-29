CREATE TABLE IF NOT EXISTS short_urls (
  id SERIAL NOT NULL,
  long_url VARCHAR(255) NOT NULL,
  short_code CHAR(6) NOT NULL,
  date_created TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP NOT NULL,

  PRIMARY KEY (id)
)