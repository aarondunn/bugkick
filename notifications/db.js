var dbConf = require('./config/main').db;
var redis = require('redis');
var db = module.exports = redis.createClient(dbConf.PORT, dbConf.HOST);