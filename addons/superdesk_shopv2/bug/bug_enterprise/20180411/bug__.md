[2018-04-11T22:09:46,942][WARN ][logstash.outputs.elasticsearch] 


Could not index event to Elasticsearch. 
{
:status=>400, 
:action=>["index", {:_id=>"42760", :_index=>"db_super_desk", :_type=>"ims_superdesk_shop_goods", :_routing=>nil}, #<LogStash::Event:0x5a4259af>], 
:response=>{
"index"=>{"_index"=>"db_super_desk_v20180411", "_type"=>"ims_superdesk_shop_goods", "_id"=>"42760", "status"=>400, 
"error"=>{
"type"=>"mapper_parsing_exception", 
"reason"=>"failed to parse [isrecommand]", 
"caused_by"=>{"type"=>"json_parse_exception", 
"reason"=>"Current token (VALUE_FALSE) not numeric, can not use numeric value accessors\n at [Source: org.elasticsearch.common.bytes.BytesReference$MarkSupportingStreamInputWrapper@2b54799c; line: 1, column: 157]"}}}}}




[2018-04-12T16:10:26,445][ERROR][logstash.pipeline        ] 

Error registering plugin {:pipeline_id=>"main", :plugin=>"#<LogStash::FilterDelegator:0x32e986f6 @metric_events_out=org.jruby.proxy.org.logstash.instrument.metrics.counter.LongCounter$Proxy2 -  name: out value:0, @metric_events_in=org.jruby.proxy.org.logstash.instrument.metrics.counter.LongCounter$Proxy2 -  name: in value:0, @logger=#<LogStash::Logging::Logger:0x3631152b @logger=#<Java::OrgApacheLoggingLog4jCore::Logger:0x711132c>>, @metric_events_time=org.jruby.proxy.org.logstash.instrument.metrics.counter.LongCounter$Proxy2 -  name: duration_in_millis value:0, @id=\"ba18624fb98bebb6cf3a1fcdcf31d1e808a6aaf4cbb54b92ed9ac42429411d71\", @klass=LogStash::Filters::Mutate, @metric_events=#<LogStash::Instrument::NamespacedMetric:0x3732f5c6 @metric=#<LogStash::Instrument::Metric:0x9142646 @collector=#<LogStash::Instrument::Collector:0x693cc3b @agent=nil, @metric_store=#<LogStash::Instrument::MetricStore:0x5f795b69 @store=#<Concurrent::Map:0x00000000000fb0 entries=4 default_proc=nil>, @structured_lookup_mutex=#<Mutex:0x4fa1dafb>, @fast_lookup=#<Concurrent::Map:0x00000000000fb4 entries=72 default_proc=nil>>>>, @namespace_name=[:stats, :pipelines, :main, :plugins, :filters, :ba18624fb98bebb6cf3a1fcdcf31d1e808a6aaf4cbb54b92ed9ac42429411d71, :events]>, @filter=<LogStash::Filters::Mutate convert=>{\"iscomment\"=>\"long\"}, id=>\"ba18624fb98bebb6cf3a1fcdcf31d1e808a6aaf4cbb54b92ed9ac42429411d71\", enable_metric=>true, periodic_flush=>false>>", :error=>"translation missing: en.logstash.agent.configuration.invalid_plugin_register", :thread=>"#<Thread:0xd485183 run>"}
