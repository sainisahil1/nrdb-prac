const {MongoClient} = require("mongodb");
const uri = "mongodb://localhost:27017";
const client = new MongoClient(uri);

async function populatePhonebook(areaCode, quantity){

    await client.connect();
    console.log("Connected to MongoDB")
    const db = client.db("phonebooks");
    const phones = await db.collection("phones");

    try {
        for (var i = 0; i < quantity; i++) {
            var phoneNumberLength = 3 + ((Math.random() * 6) << 0);
            var phoneNumber = 1 + ((Math.random() * Math.pow(10, phoneNumberLength)) << 0);
            phoneNumberLength = 1 + Math.floor(Math.log(phoneNumber) / Math.log(10))
            if (phoneNumberLength < 3) {
                continue
            }
            var num = areaCode * Math.pow(10, phoneNumberLength) + phoneNumber;
            phones.insertOne({
                _id: num,
                components: {
                    areaCode: areaCode,
                    phoneNumber: phoneNumber,
                },
                display: areaCode + "/" + phoneNumber,
            })

        }
    } catch (e){
        console.error(e);
    }
}

populatePhonebook(332, 6);
populatePhonebook(234, 3);
populatePhonebook(145, 8);
populatePhonebook(987, 5);
