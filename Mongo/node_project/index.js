const {MongoClient} = require("mongodb");
const uri = "mongodb://localhost:27017";
const client = new MongoClient(uri);

async function run() {
    await client.connect();
    console.log("Connected to MongoDB")
    const db = client.db("phonebooks");
    const phones = await db.collection("phones");

    // const result = await phones.find({"display":"145/663200"}).toArray();

    // const result = await phones.find().sort({"components.areaCode":1, "components.phoneNumber": -1}).toArray()

    // const result = await phones.find({}, {projection: {"display":1, "_id":0}}).toArray();

    // const result = await phones.find({"components.areaCode" : {"$gt":299}}, {projection: {"display":1, "_id":0}}).toArray();

    //for regex in mongosh, surround it with /.../

    // const result = await phones.find({"display": {"$regex":"32$"}}, {projection: {"display":1, "_id":0}}).toArray();

    // const result = await phones.find({"display": {"$regex":"5.1"}}, {projection: {"display":1, "_id":0}}).toArray();

    // const result = await phones.find({"display": {"$regex":"\\/1"}}, {projection: {"display":1, "_id":0}}).toArray();

    // const result = await phones.find({"display": {"$regex":"\\/[1]0"}}, {projection: {"display":1, "_id":0}}).toArray();

    //start sequence - end sequence
    // const result = await phones.find({"display": {"$regex":"\\/12(.*)13$"}}, {projection: {"display":1, "_id":0}}).toArray();

    // db.phones.createIndex({"components.areaCode":1})

    // const result = await phones.find().explain("executionStats")


    console.log(result);

}

run()
