const {MongoClient} = require("mongodb");
const uri = "mongodb://localhost:27017";
const client = new MongoClient(uri);

async function insertCustomer() {
    await client.connect();
    console.log("Connected to MongoDB")
    const db = client.db("TelecomContracts_19071997");
    const phones = await db.collection("customers");

    await phones.insertMany([
            {
                "cid": 101,
                "lastname": "Smith",
                "firstname": "John",
                "street": "123 Maple Street",
                "postcode": "10001",
                "city": "New York"
            },
            {
                "cid": 102,
                "lastname": "Müller",
                "firstname": "Anna",
                "street": "45 Berliner Straße",
                "postcode": "10115",
                "city": "Berlin"
            },
            {
                "cid": 103,
                "lastname": "Wong",
                "firstname": "David",
                "street": "678 Orchard Road",
                "postcode": "238841",
                "city": "Singapore"
            }
        ]
    )

}

insertCustomer()
