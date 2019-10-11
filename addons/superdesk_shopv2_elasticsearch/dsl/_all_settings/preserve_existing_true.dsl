curl -XPUT 'http://localhost:9200/_all/_settings?preserve_existing=true' -d '{
  "index.analysis.analyzer.ik.type" : "ik"
}'


keyword

elasticsearch index.analysis.analyzer.ik.type