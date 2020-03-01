FROM mongo:latest

ADD create-indexes.js /docker-entrypoint-initdb.d/

COPY load-fixtures.sh ./