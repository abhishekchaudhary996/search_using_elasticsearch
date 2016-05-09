
bin=/home/abhishek/app/elasticsearch-jdbc-2.3.1.0/bin
lib=/home/abhishek/app/elasticsearch-jdbc-2.3.1.0/lib

curl -XDELETE 'localhost:9200/people'

curl -XPUT 'http://localhost:9200/people/'

echo '
{
    "type" : "jdbc",
    "jdbc" : {
        "url" : "jdbc:mysql://localhost:3306/portea",
        "user" : "root",
        "password" : "abhishek",
        "locale" : "en_US",
        "sql" : "select \"people\" as _index, \"details\" as _type, id as _id, name, phone, email from person_details",
        "elasticsearch" : {
             "cluster" : "elasticsearch",
             "host" : "localhost",
             "port" : 9300
        },
        "index" : "people",
        "type" : "details",
        "index_settings" : {
            "index" : {
                "number_of_shards" : 1
            }
        }
    }
}
' | java \
    -cp "${lib}/*" \
    -Dlog4j.configurationFile=${bin}/log4j2.xml \
    org.xbib.tools.Runner \
    org.xbib.tools.JDBCImporter
