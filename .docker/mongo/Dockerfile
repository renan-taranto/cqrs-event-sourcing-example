FROM mongo:4.2

ADD create-indexes.js /docker-entrypoint-initdb.d/

COPY load-fixtures.sh ./