db = db.getSiblingDB('appdb');

db.boards.createIndex({id: 1});

db.boards.createIndex({'lists.id': 1});
db.boards.createIndex({'lists.items.id': 1});
db.boards.createIndex({'lists.archivedItems.id': 1});

db.boards.createIndex({'archivedLists.id': 1});
db.boards.createIndex({'archivedLists.items.id': 1});
db.boards.createIndex({'archivedLists.archivedItems.id': 1});
