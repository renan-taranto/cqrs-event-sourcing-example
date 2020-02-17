fixtures_dir='testing-data/fixtures'
fixtures_tmp_dir='fixtures-dump'
fixtures_db='fixtures'
tests_dump_dir='testing-data/dump'

find $fixtures_dir/*.json | while read -r json_file
 do mongoimport --db $fixtures_db --file "$json_file" --type json --jsonArray --maintainInsertionOrder --drop
done

mongodump --db $fixtures_db -o=$fixtures_tmp_dir
mv $fixtures_tmp_dir/$fixtures_db/* $tests_dump_dir/

rm -rf $fixtures_tmp_dir
mongo $fixtures_db --eval "printjson(db.dropDatabase())"
