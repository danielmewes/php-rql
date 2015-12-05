---
layout: api
title: "ReQL command reference"
active: api
no_footer: true
permalink: api/javascript/
alias: api/
language: JavaScript
---

{% apisection Accessing ReQL %}
All ReQL queries begin from the top-level module.

## [r](r/) ##

{% apibody %}
r &rarr; r
{% endapibody %}

The top-level ReQL namespace.

__Example:__ Set up your top-level namespace.

```js
var r = require('rethinkdb');
```

## [connect](connect/) ##

{% apibody %}
r.connect(options, callback)
r.connect(host, callback)
r.connect(options) &rarr; promise
r.connect(host) &rarr; promise
{% endapibody %}

Create a new connection to the database server.

__Example:__ Open a connection using the default host and port, specifying the default database.

```js
r.connect({
    db: 'marvel'
}, function(err, conn) {
    // ...
});
```

If no callback is provided, a promise will be returned.

```js
var promise = r.connect({db: 'marvel'});
```

[Read more about this command &rarr;](connect/)

## [close](close/) ##

{% apibody %}
conn.close([{noreplyWait: true}, ]callback)
conn.close([{noreplyWait: true}]) &rarr; promise
{% endapibody %}

Close an open connection.

If no callback is provided, a promise will be returned.

__Example:__ Close an open connection, waiting for noreply writes to finish.

```js
conn.close(function(err) { if (err) throw err; })
```

[Read more about this command &rarr;](close/)

## [reconnect](reconnect/) ##

{% apibody %}
conn.reconnect([{noreplyWait: true}, ]callback)
conn.reconnect([{noreplyWait: true}]) &rarr; promise
{% endapibody %}

Close and reopen a connection.

If no callback is provided, a promise will be returned.

__Example:__ Cancel outstanding requests/queries that are no longer needed.

```js
conn.reconnect({noreplyWait: false}, function(error, connection) { ... })
```

[Read more about this command &rarr;](reconnect/)

## [use](use/) ##

{% apibody %}
conn.use(dbName)
{% endapibody %}

Change the default database on this connection.

__Example:__ Change the default database so that we don't need to
specify the database when referencing a table.

```js
conn.use('marvel')
r.table('heroes').run(conn, ...) // refers to r.db('marvel').table('heroes')
```

## [run](run/) ##

{% apibody %}
query.run(conn[, options], callback)
query.run(conn[, options]) &rarr; promise
{% endapibody %}

Run a query on a connection. 

The callback will get either an error, a single JSON result, or a
cursor, depending on the query.

__Example:__ Run a query on the connection `conn` and log each row in
the result to the console.

```js
r.table('marvel').run(conn, function(err, cursor) { cursor.each(console.log); })
```

[Read more about this command &rarr;](run/)

## [noreplyWait](noreply_wait/) ##

{% apibody %}
conn.noreplyWait(callback)
conn.noreplyWait() &rarr; promise
{% endapibody %}

`noreplyWait` ensures that previous queries with the `noreply` flag have been processed
by the server. Note that this guarantee only applies to queries run on the given connection.

__Example:__ We have previously run queries with the `noreply` argument set to `true`. Now
wait until the server has processed them.

```js
conn.noreplyWait(function(err) { ... })
```

## [server](server/) ##

{% apibody %}
conn.server(callback)
conn.server() &rarr; promise
{% endapibody %}

Return the server name and server UUID being used by a connection.

__Example:__ Return the server name and UUID.

```js
conn.server(callback);

// Result passed to callback
{ "id": "404bef53-4b2c-433f-9184-bc3f7bda4a15", "name": "amadeus" }
```

If no callback is provided, a promise will be returned.


## [EventEmitter (connection)](event_emitter/) ##

{% apibody %}
connection.addListener(event, listener)
connection.on(event, listener)
connection.once(event, listener)
connection.removeListener(event, listener)
connection.removeAllListeners([event])
connection.setMaxListeners(n)
connection.listeners(event)
connection.emit(event, [arg1], [arg2], [...])
{% endapibody %}

Connections implement the same interface as Node's [EventEmitter](http://nodejs.org/api/events.html#events_class_events_eventemitter).
This allows you to listen for changes in connection state.

[Read more about this command &rarr;](event_emitter/)

{% endapisection %}

{% apisection Cursors %}

## [next](next/) ##

{% apibody %}
cursor.next(callback)
array.next(callback)
cursor.next() &rarr; promise
array.next() &rarr; promise
{% endapibody %}

Get the next element in the cursor.

__Example:__ Retrieve the next element.

```js
cursor.next(function(err, row) {
    if (err) throw err;
    processRow(row);
});
```

[Read more about this command &rarr;](next/)


## [each](each/) ##

{% apibody %}
cursor.each(callback[, onFinishedCallback])
array.each(callback[, onFinishedCallback])
feed.each(callback)
{% endapibody %}

Lazily iterate over the result set one element at a time.

__Example:__ Let's process all the elements!

```js
cursor.each(function(err, row) {
    if (err) throw err;
    processRow(row);
});
```

[Read more about this command &rarr;](each/)


## [eachAsync](each_async/) ##

{% apibody %}
cursor.eachAsync(function) &rarr; promise
array.eachAsync(function) &rarr; promise
feed.eachAsync(function) &rarr; promise
{% endapibody %}

Lazily iterate over a result set one element at a time in an identical fashion to [each](/api/javascript/each/), returning a Promise that will be resolved once all rows are returned.

__Example:__ Process all the elements in a stream.

```js
cursor.eachAsync(function(row) {
    // if a Promise is returned, it will be processed before the cursor
    // continues iteration.
    return asyncRowHandler(row);
}).then(function () {
    console.log("done processing"); 
});
```

[Read more about this command &rarr;](each_async/)

## [toArray](to_array/) ##

{% apibody %}
cursor.toArray(callback)
array.toArray(callback)
cursor.toArray() &rarr; promise
array.toArray() &rarr; promise
{% endapibody %}

Retrieve all results and pass them as an array to the given callback.

__Example:__ For small result sets it may be more convenient to process them at once as
an array.

```js
cursor.toArray(function(err, results) {
    if (err) throw err;
    processResults(results);
});
```

[Read more about this command &rarr;](to_array/)


## [close](close-cursor/) ##

{% apibody %}
cursor.close()
{% endapibody %}


Close a cursor. Closing a cursor cancels the corresponding query and frees the memory
associated with the open request.

__Example:__ Close a cursor.

```js
cursor.close()
```


## [EventEmitter (cursor)](event_emitter-cursor/) ##

{% apibody %}
cursor.addListener(event, listener)
cursor.on(event, listener)
cursor.once(event, listener)
cursor.removeListener(event, listener)
cursor.removeAllListeners([event])
cursor.setMaxListeners(n)
cursor.listeners(event)
cursor.emit(event, [arg1], [arg2], [...])
{% endapibody %}

Cursors and feeds implement the same interface as Node's [EventEmitter](http://nodejs.org/api/events.html#events_class_events_eventemitter).

[Read more about this command &rarr;](event_emitter-cursor/)

{% endapisection %}

{% apisection Manipulating databases %}

## [dbCreate](db_create/) ##

{% apibody %}
r.dbCreate(dbName) &rarr; object
{% endapibody %}

Create a database. A RethinkDB database is a collection of tables, similar to
relational databases.

If successful, the operation returns an object: `{created: 1}`. If a database with the
same name already exists the operation throws `ReqlRuntimeError`.

Note: that you can only use alphanumeric characters and underscores for the database name.

__Example:__ Create a database named 'superheroes'.

```js
r.dbCreate('superheroes').run(conn, callback)
```


## [dbDrop](db_drop/) ##

{% apibody %}
r.dbDrop(dbName) &rarr; object
{% endapibody %}

Drop a database. The database, all its tables, and corresponding data will be deleted.

If successful, the operation returns the object `{dropped: 1}`. If the specified database
doesn't exist a `ReqlRuntimeError` is thrown.

__Example:__ Drop a database named 'superheroes'.

```js
r.dbDrop('superheroes').run(conn, callback)
```


## [dbList](db_list/) ##

{% apibody %}
r.dbList() &rarr; array
{% endapibody %}

List all database names in the system. The result is a list of strings.

__Example:__ List all databases.

```js
r.dbList().run(conn, callback)
```

{% endapisection %}




{% apisection Manipulating tables %}
## [tableCreate](table_create/) ##

{% apibody %}
db.tableCreate(tableName[, options]) &rarr; object
r.tableCreate(tableName[, options]) &rarr; object
{% endapibody %}

Create a table. A RethinkDB table is a collection of JSON documents.

__Example:__ Create a table named 'dc_universe' with the default settings.

```js
r.db('heroes').tableCreate('dc_universe').run(conn, callback)
```

[Read more about this command &rarr;](table_create/)

## [tableDrop](table_drop/) ##

{% apibody %}
db.tableDrop(tableName) &rarr; object
{% endapibody %}

Drop a table. The table and all its data will be deleted.

__Example:__ Drop a table named 'dc_universe'.

```js
r.db('test').tableDrop('dc_universe').run(conn, callback)
```

[Read more about this command &rarr;](table_drop/)

## [tableList](table_list/) ##

{% apibody %}
db.tableList() &rarr; array
{% endapibody %}

List all table names in a database. The result is a list of strings.

__Example:__ List all tables of the 'test' database.

```js
r.db('test').tableList().run(conn, callback)
```

## [indexCreate](index_create/) ##

{% apibody %}
table.indexCreate(indexName[, indexFunction][, {multi: false, geo: false}]) &rarr; object
{% endapibody %}

Create a new secondary index on a table.

__Example:__ Create a simple index based on the field `postId`.

```js
r.table('comments').indexCreate('postId').run(conn, callback)
```

[Read more about this command &rarr;](index_create/)

## [indexDrop](index_drop/) ##

{% apibody %}
table.indexDrop(indexName) &rarr; object
{% endapibody %}

Delete a previously created secondary index of this table.

__Example:__ Drop a secondary index named 'code_name'.

```js
r.table('dc').indexDrop('code_name').run(conn, callback)
```

## [indexList](index_list/) ##

{% apibody %}
table.indexList() &rarr; array
{% endapibody %}

List all the secondary indexes of this table.

__Example:__ List the available secondary indexes for this table.

```js
r.table('marvel').indexList().run(conn, callback)
```

## [indexRename](index_rename/) ##

{% apibody %}
table.indexRename(oldIndexName, newIndexName[, {overwrite: false}]) &rarr; object
{% endapibody %}

Rename an existing secondary index on a table. If the optional argument `overwrite` is specified as `true`, a previously existing index with the new name will be deleted and the index will be renamed. If `overwrite` is `false` (the default) an error will be raised if the new index name already exists.

__Example:__ Rename an index on the comments table.

```js
r.table('comments').indexRename('postId', 'messageId').run(conn, callback)
```


## [indexStatus](index_status/) ##

{% apibody %}
table.indexStatus([, index...]) &rarr; array
{% endapibody %}

Get the status of the specified indexes on this table, or the status
of all indexes on this table if no indexes are specified.

__Example:__ Get the status of all the indexes on `test`:

```js
r.table('test').indexStatus().run(conn, callback)
```

__Example:__ Get the status of the `timestamp` index:

```js
r.table('test').indexStatus('timestamp').run(conn, callback)
```

## [indexWait](index_wait/) ##

{% apibody %}
table.indexWait([, index...]) &rarr; array
{% endapibody %}

Wait for the specified indexes on this table to be ready, or for all
indexes on this table to be ready if no indexes are specified.

__Example:__ Wait for all indexes on the table `test` to be ready:

```js
r.table('test').indexWait().run(conn, callback)
```

__Example:__ Wait for the index `timestamp` to be ready:

```js
r.table('test').indexWait('timestamp').run(conn, callback)
```

## [changes](changes/) ##

{% apibody %}
stream.changes([options]) &rarr; stream
singleSelection.changes([options]) &rarr; stream
{% endapibody %}

Return a changefeed, an infinite stream of objects representing changes to a query. A changefeed may return changes to a table or an individual document (a "point" changefeed), and document transformation commands such as `filter` or `map` may be used before the `changes` command to affect the output.

__Example:__ Subscribe to the changes on a table.

```js
r.table('games').changes().run(conn, function(err, cursor) {
  cursor.each(console.log)
})
```

[Read more about this command &rarr;](changes/)

{% endapisection %}


{% apisection Writing data %}

## [insert](insert/) ##

{% apibody %}
table.insert(object | [object1, object2, ...][, {durability: "hard", returnChanges: false, conflict: "error"}]) &rarr; object
{% endapibody %}

Insert JSON documents into a table. Accepts a single JSON document or an array of
documents.

__Example:__ Insert a document into the table `posts`.

```js
r.table("posts").insert({
    id: 1,
    title: "Lorem ipsum",
    content: "Dolor sit amet"
}).run(conn, callback)
```


[Read more about this command &rarr;](insert/)

## [update](update/) ##

{% apibody %}
table.update(object | function
    [, {durability: "hard", returnChanges: false, nonAtomic: false}])
        &rarr; object
selection.update(object | function
    [, {durability: "hard", returnChanges: false, nonAtomic: false}])
        &rarr; object
singleSelection.update(object | function
    [, {durability: "hard", returnChanges: false, nonAtomic: false}])
        &rarr; object
{% endapibody %}

Update JSON documents in a table. Accepts a JSON document, a ReQL expression, or a
combination of the two. You can pass options like `returnChanges` that will return the old
and new values of the row you have modified.

__Example:__ Update the status of the post with `id` of `1` to `published`.

```js
r.table("posts").get(1).update({status: "published"}).run(conn, callback)
```


[Read more about this command &rarr;](update/)


## [replace](replace/) ##

{% apibody %}
table.replace(object | function
    [, {durability: "hard", returnChanges: false, nonAtomic: false}])
        &rarr; object
selection.replace(object | function
    [, {durability: "hard", returnChanges: false, nonAtomic: false}])
        &rarr; object
singleSelection.replace(object | function
    [, {durability: "hard", returnChanges: false, nonAtomic: false}])
        &rarr; object

{% endapibody %}

Replace documents in a table. Accepts a JSON document or a ReQL expression, and replaces
the original document with the new one. The new document must have the same primary key
as the original document.

__Example:__ Replace the document with the primary key `1`.

```js
r.table("posts").get(1).replace({
    id: 1,
    title: "Lorem ipsum",
    content: "Aleas jacta est",
    status: "draft"
}).run(conn, callback)
```

[Read more about this command &rarr;](replace/)

## [delete](delete/) ##

{% apibody %}
table.delete([{durability: "hard", returnChanges: false}])
    &rarr; object
selection.delete([{durability: "hard", returnChanges: false}])
    &rarr; object
singleSelection.delete([{durability: "hard", returnChanges: false}])
    &rarr; object
{% endapibody %}

Delete one or more documents from a table.

__Example:__ Delete a single document from the table `comments`.

```js
r.table("comments").get("7eab9e63-73f1-4f33-8ce4-95cbea626f59").delete().run(conn, callback)
```

[Read more about this command &rarr;](delete/)

## [sync](sync/) ##

{% apibody %}
table.sync()
    &rarr; object
{% endapibody %}

`sync` ensures that writes on a given table are written to permanent storage. Queries
that specify soft durability (`{durability: 'soft'}`) do not give such guarantees, so
`sync` can be used to ensure the state of these queries. A call to `sync` does not return
until all previous writes to the table are persisted.


__Example:__ After having updated multiple heroes with soft durability, we now want to wait
until these changes are persisted.

```js
r.table('marvel').sync().run(conn, callback)
```

{% endapisection %}


{% apisection Selecting data %}

## [db](db/) ##

{% apibody %}
r.db(dbName) &rarr; db
{% endapibody %}

Reference a database.

__Example:__ Explicitly specify a database for a query.

```js
r.db('heroes').table('marvel').run(conn, callback)
```

[Read more about this command &rarr;](db/)

## [table](table/) ##

{% apibody %}
db.table(name[, {readMode: 'single', identifierFormat: 'name'}]) &rarr; table
{% endapibody %}

Select all documents in a table. This command can be chained with other commands to do
further processing on the data.

__Example:__ Return all documents in the table 'marvel' of the default database.

```js
r.table('marvel').run(conn, callback)
```

[Read more about this command &rarr;](table/)

## [get](get/) ##

{% apibody %}
table.get(key) &rarr; singleRowSelection
{% endapibody %}

Get a document by primary key.

If no document exists with that primary key, `get` will return `null`.

__Example:__ Find a document by UUID.

```js
r.table('posts').get('a9849eef-7176-4411-935b-79a6e3c56a74').run(conn, callback)
```

[Read more about this command &rarr;](get/)

## [getAll](get_all/) ##

{% apibody %}
table.getAll(key[, key2...], [, {index:'id'}]) &rarr; selection
{% endapibody %}

Get all documents where the given value matches the value of the requested index.

__Example:__ Secondary index keys are not guaranteed to be unique so we cannot query via [get](/api/javascript/get/) when using a secondary index.

```js
r.table('marvel').getAll('man_of_steel', {index:'code_name'}).run(conn, callback)
```

[Read more about this command &rarr;](get_all/)


## [between](between/) ##

{% apibody %}
table.between(lowerKey, upperKey[, options]) &rarr; table_slice
table_slice.between(lowerKey, upperKey[, options]) &rarr; table_slice
{% endapibody %}

Get all documents between two keys. Accepts three optional arguments: `index`,
`left_bound`, and `right_bound`. If `index` is set to the name of a secondary index,
`between` will return all documents where that index's value is in the specified range
(it uses the primary key by default). `left_bound` or `right_bound` may be set to `open`
or `closed` to indicate whether or not to include that endpoint of the range (by default,
`left_bound` is closed and `right_bound` is open).

__Example:__ Find all users with primary key >= 10 and < 20 (a normal half-open interval).

```js
r.table('marvel').between(10, 20).run(conn, callback)
```

[Read more about this command &rarr;](between/)

## [filter](filter/) ##

{% apibody %}
selection.filter(predicate_function[, {default: false}]) &rarr; selection
stream.filter(predicate_function[, {default: false}]) &rarr; stream
array.filter(predicate_function[, {default: false}]) &rarr; array
{% endapibody %}

Get all the documents for which the given predicate is true.

`filter` can be called on a sequence, selection, or a field containing an array of
elements. The return type is the same as the type on which the function was called on.

The body of every filter is wrapped in an implicit `.default(false)`, which means that
if a non-existence errors is thrown (when you try to access a field that does not exist
in a document), RethinkDB will just ignore the document.
The `default` value can be changed by passing an object with a `default` field.
Setting this optional argument to `r.error()` will cause any non-existence errors to
return a `ReqlRuntimeError`.

__Example:__ Get all the users that are 30 years old.

```js
r.table('users').filter({age: 30}).run(conn, callback)
```

[Read more about this command &rarr;](filter/)

{% endapisection %}


{% apisection Joins %}
These commands allow the combination of multiple sequences into a single sequence

## [innerJoin](inner_join/) ##

{% apibody %}
sequence.innerJoin(otherSequence, predicate_function) &rarr; stream
array.innerJoin(otherSequence, predicate_function) &rarr; array
{% endapibody %}

Returns an inner join of two sequences.

__Example:__ Return a list of all matchups between Marvel and DC heroes in which the DC hero could beat the Marvel hero in a fight.

```js
r.table('marvel').innerJoin(r.table('dc'), function(marvelRow, dcRow) {
    return marvelRow('strength').lt(dcRow('strength'))
}).zip().run(conn, callback)
```

[Read more about this command &rarr;](inner_join/)

## [outerJoin](outer_join/) ##

{% apibody %}
sequence.outerJoin(otherSequence, predicate_function) &rarr; stream
array.outerJoin(otherSequence, predicate_function) &rarr; array
{% endapibody %}

Returns a left outer join of two sequences.

__Example:__ Return a list of all Marvel heroes, paired with any DC heroes who could beat them in a fight.

```js
r.table('marvel').outerJoin(r.table('dc'), function(marvelRow, dcRow) {
    return marvelRow('strength').lt(dcRow('strength'))
}).run(conn, callback)
```

[Read more about this command &rarr;](outer_join/)

## [eqJoin](eq_join/) ##

{% apibody %}
sequence.eqJoin(leftField, rightTable[, {index:'id'}]) &rarr; sequence
sequence.eqJoin(predicate_function, rightTable[, {index:'id'}]) &rarr; sequence
{% endapibody %}

Join tables using a field or function on the left-hand sequence matching primary keys or secondary indexes on the right-hand table. `eqJoin` is more efficient than other ReQL join types, and operates much faster. Documents in the result set consist of pairs of left-hand and right-hand documents, matched when the field on the left-hand side exists and is non-null and an entry with that field's value exists in the specified index on the right-hand side.

**Example:** Match players with the games they've played against one another.

```js
r.table('players').eqJoin('gameId', r.table('games')).run(conn, callback)
```

[Read more about this command &rarr;](eq_join/)


## [zip](zip/) ##

{% apibody %}
stream.zip() &rarr; stream
array.zip() &rarr; array
{% endapibody %}

Used to 'zip' up the result of a join by merging the 'right' fields into 'left' fields of each member of the sequence.

__Example:__ 'zips up' the sequence by merging the left and right fields produced by a join.

```
r.table('marvel').eqJoin('main_dc_collaborator', r.table('dc'))
    .zip().run(conn, callback)
```



{% endapisection %}

{% apisection Transformations %}
These commands are used to transform data in a sequence.

## [map](map/) ##

{% apibody %}
sequence1.map([sequence2, ...], function) &rarr; stream
array1.map([array2, ...], function) &rarr; array
r.map(sequence1[, sequence2, ...], function) &rarr; stream
r.map(array1[, array2, ...], function) &rarr; array
{% endapibody %}

Transform each element of one or more sequences by applying a mapping function to them. If `map` is run with two or more sequences, it will iterate for as many items as there are in the shortest sequence.

__Example:__ Return the first five squares.

```js
r.expr([1, 2, 3, 4, 5]).map(function (val) {
    return val.mul(val);
}).run(conn, callback);
// Result passed to callback
[1, 4, 9, 16, 25]
```

[Read more about this command &rarr;](map/)

## [withFields](with_fields/) ##

{% apibody %}
sequence.withFields([selector1, selector2...]) &rarr; stream
array.withFields([selector1, selector2...]) &rarr; array
{% endapibody %}

Plucks one or more attributes from a sequence of objects, filtering out any objects in the sequence that do not have the specified fields. Functionally, this is identical to `hasFields` followed by `pluck` on a sequence.

__Example:__ Get a list of users and their posts, excluding any users who have not made any posts.

```js
r.table('users').withFields('id', 'username', 'posts').run(conn, callback)
```

[Read more about this command &rarr;](with_fields/)

## [concatMap](concat_map/) ##

{% apibody %}
stream.concatMap(function) &rarr; stream
array.concatMap(function) &rarr; array
{% endapibody %}

Concatenate one or more elements into a single sequence using a mapping function.

__Example:__ Construct a sequence of all monsters defeated by Marvel heroes. The field "defeatedMonsters" is an array of one or more monster names.

```js
r.table('marvel').concatMap(function(hero) {
    return hero('defeatedMonsters')
}).run(conn, callback)
```

[Read more about this command &rarr;](concat_map/)

## [orderBy](order_by/) ##

{% apibody %}
table.orderBy([key | function...], {index: index_name}) &rarr; table_slice
selection.orderBy(key | function[, ...]) &rarr; selection<array>
sequence.orderBy(key | function[, ...]) &rarr; array
{% endapibody %}

Sort the sequence by document values of the given key(s). To specify
the ordering, wrap the attribute with either `r.asc` or `r.desc`
(defaults to ascending).

Sorting without an index requires the server to hold the sequence in
memory, and is limited to 100,000 documents (or the setting of the `arrayLimit` option for [run](/api/javascript/run)). Sorting with an index can
be done on arbitrarily large tables, or after a `between` command
using the same index.

__Example:__ Order all the posts using the index `date`.   

```js
r.table('posts').orderBy({index: 'date'}).run(conn, callback)
```

The index must have been previously created with [indexCreate](/api/javascript/index_create/).

```js
r.table('posts').indexCreate('date').run(conn, callback)
```

You can also select a descending ordering:

```js
r.table('posts').orderBy({index: r.desc('date')}).run(conn, callback)
```


[Read more about this command &rarr;](order_by/)

## [skip](skip/) ##

{% apibody %}
sequence.skip(n) &rarr; stream
array.skip(n) &rarr; array
{% endapibody %}

Skip a number of elements from the head of the sequence.

__Example:__ Here in conjunction with `orderBy` we choose to ignore the most successful heroes.

```js
r.table('marvel').orderBy('successMetric').skip(10).run(conn, callback)
```


## [limit](limit/) ##

{% apibody %}
sequence.limit(n) &rarr; stream
array.limit(n) &rarr; array
{% endapibody %}


End the sequence after the given number of elements.

__Example:__ Only so many can fit in our Pantheon of heroes.

```js
r.table('marvel').orderBy('belovedness').limit(10).run(conn, callback)
```

## [slice](slice/) ##

{% apibody %}
selection.slice(startIndex[, endIndex, {leftBound:'closed', rightBound:'open'}]) &rarr; selection
stream.slice(startIndex[, endIndex, {leftBound:'closed', rightBound:'open'}]) &rarr; stream
array.slice(startIndex[, endIndex, {leftBound:'closed', rightBound:'open'}]) &rarr; array
binary.slice(startIndex[, endIndex, {leftBound:'closed', rightBound:'open'}]) &rarr; binary
{% endapibody %}

Return the elements of a sequence within the specified range.

**Example:** Return the fourth, fifth and sixth youngest players. (The youngest player is at index 0, so those are elements 3&ndash;5.)

```js
r.table('players').orderBy({index: 'age'}).slice(3,6).run(conn, callback)
```

## [nth](nth/) ##

{% apibody %}
sequence.nth(index) &rarr; object
selection.nth(index) &rarr; selection&lt;object&gt;
{% endapibody %}

Get the *nth* element of a sequence, counting from zero. If the argument is negative, count from the last element.

__Example:__ Select the second element in the array.

```js
r.expr([1,2,3]).nth(1).run(conn, callback)
```


## [offsetsOf](offsets_of/) ##

{% apibody %}
sequence.offsetsOf(datum | predicate_function) &rarr; array
{% endapibody %}

Get the indexes of an element in a sequence. If the argument is a predicate, get the indexes of all elements matching it.

__Example:__ Find the position of the letter 'c'.

```js
r.expr(['a','b','c']).offsetsOf('c').run(conn, callback)
```

[Read more about this command &rarr;](offsets_of/)


## [isEmpty](is_empty/) ##

{% apibody %}
sequence.isEmpty() &rarr; bool
{% endapibody %}

Test if a sequence is empty.

__Example:__ Are there any documents in the marvel table?

```js
r.table('marvel').isEmpty().run(conn, callback)
```

## [union](union/) ##

{% apibody %}
stream.union(sequence[, sequence, ...]) &rarr; stream
array.union(sequence[, sequence, ...]) &rarr; array
{% endapibody %}

Merge two or more sequences. (Note that ordering is not guaranteed by `union`.)

__Example:__ Construct a stream of all heroes.

```js
r.table('marvel').union(r.table('dc')).run(conn, callback);
```


## [sample](sample/) ##

{% apibody %}
sequence.sample(number) &rarr; selection
stream.sample(number) &rarr; array
array.sample(number) &rarr; array
{% endapibody %}

Select a given number of elements from a sequence with uniform random distribution. Selection is done without replacement.

__Example:__ Select 3 random heroes.

```js
r.table('marvel').sample(3).run(conn, callback)
```


{% endapisection %}


{% apisection Aggregation %}
These commands are used to compute smaller values from large sequences.


## [group](group/) ##

{% apibody %}
sequence.group(field | function..., [{index: <indexname>, multi: false}]) &rarr; grouped_stream
{% endapibody %}

Takes a stream and partitions it into multiple groups based on the
fields or functions provided.  Commands chained after `group` will be
called on each of these grouped sub-streams, producing grouped data.

__Example:__ What is each player's best game?

```js
r.table('games').group('player').max('points').run(conn, callback)
```

[Read more about this command &rarr;](group/)


## [ungroup](ungroup/) ##

{% apibody %}
grouped_stream.ungroup() &rarr; array
grouped_data.ungroup() &rarr; array
{% endapibody %}

Takes a grouped stream or grouped data and turns it into an array of
objects representing the groups.  Any commands chained after `ungroup`
will operate on this array, rather than operating on each group
individually.  This is useful if you want to e.g. order the groups by
the value of their reduction.

__Example:__ What is the maximum number of points scored by each
player, with the highest scorers first?

```js
r.table('games')
    .group('player').max('points')('points')
    .ungroup().orderBy(r.desc('reduction')).run(conn)
```

[Read more about this command &rarr;](ungroup/)




## [reduce](reduce/) ##

{% apibody %}
sequence.reduce(function) &rarr; value
{% endapibody %}

Produce a single value from a sequence through repeated application of a reduction
function.

__Example:__ Return the number of documents in the table `posts.

```js
r.table("posts").map(function(doc) {
    return 1
}).reduce(function(left, right) {
    return left.add(right)
}).run(conn, callback);
```

[Read more about this command &rarr;](reduce/)

## [count](count/) ##

{% apibody %}
sequence.count([value | predicate_function]) &rarr; number
binary.count() &rarr; number
{% endapibody %}

Count the number of elements in the sequence. With a single argument, count the number
of elements equal to it. If the argument is a function, it is equivalent to calling
filter before count.

__Example:__ Just how many super heroes are there?

```js
r.table('marvel').count().add(r.table('dc').count()).run(conn, callback)
```

[Read more about this command &rarr;](count/)



## [sum](sum/) ##

{% apibody %}
sequence.sum([field | function]) &rarr; number
{% endapibody %}

Sums all the elements of a sequence.  If called with a field name,
sums all the values of that field in the sequence, skipping elements
of the sequence that lack that field.  If called with a function,
calls that function on every element of the sequence and sums the
results, skipping elements of the sequence where that function returns
`null` or a non-existence error.

__Example:__ What's 3 + 5 + 7?

```js
r.expr([3, 5, 7]).sum().run(conn, callback)
```

[Read more about this command &rarr;](sum/)


## [avg](avg/) ##

{% apibody %}
sequence.avg([field | function]) &rarr; number
{% endapibody %}

Averages all the elements of a sequence.  If called with a field name,
averages all the values of that field in the sequence, skipping
elements of the sequence that lack that field.  If called with a
function, calls that function on every element of the sequence and
averages the results, skipping elements of the sequence where that
function returns `null` or a non-existence error.


__Example:__ What's the average of 3, 5, and 7?

```js
r.expr([3, 5, 7]).avg().run(conn, callback)
```

[Read more about this command &rarr;](avg/)


## [min](min/) ##

{% apibody %}
sequence.min(field | function) &rarr; element
sequence.min({index: <indexname>}) &rarr; element
{% endapibody %}

Finds the minimum element of a sequence.

__Example:__ Return the minimum value in the list `[3, 5, 7]`.

```js
r.expr([3, 5, 7]).min().run(conn, callback);
```


[Read more about this command &rarr;](min/)



## [max](max/) ##

{% apibody %}
sequence.max(field | function) &rarr; element
sequence.max({index: <indexname>}) &rarr; element
{% endapibody %}

Finds the maximum element of a sequence.

__Example:__ Return the maximum value in the list `[3, 5, 7]`.

```js
r.expr([3, 5, 7]).max().run(conn, callback);
```

[Read more about this command &rarr;](max/)



## [distinct](distinct/) ##

{% apibody %}
sequence.distinct() &rarr; array
table.distinct([{index: <indexname>}]) &rarr; stream
{% endapibody %}

Remove duplicate elements from the sequence.

__Example:__ Which unique villains have been vanquished by marvel heroes?

```js
r.table('marvel').concatMap(function(hero) {
    return hero('villainList')
}).distinct().run(conn, callback)
```

[Read more about this command &rarr;](distinct/)


## [contains](contains/) ##

{% apibody %}
sequence.contains([value | predicate_function, ...]) &rarr; bool
{% endapibody %}

Returns whether or not a sequence contains all the specified values, or if functions are
provided instead, returns whether or not a sequence contains values matching all the
specified functions.

__Example:__ Has Iron Man ever fought Superman?

```js
r.table('marvel').get('ironman')('opponents').contains('superman').run(conn, callback)
```

[Read more about this command &rarr;](contains/)



{% endapisection %}


{% apisection Document manipulation %}

## [row](row/) ##

{% apibody %}
r.row &rarr; value
{% endapibody %}

Returns the currently visited document.

__Example:__ Get all users whose age is greater than 5.

```js
r.table('users').filter(r.row('age').gt(5)).run(conn, callback)
```

[Read more about this command &rarr;](row/)


## [pluck](pluck/) ##

{% apibody %}
sequence.pluck([selector1, selector2...]) &rarr; stream
array.pluck([selector1, selector2...]) &rarr; array
object.pluck([selector1, selector2...]) &rarr; object
singleSelection.pluck([selector1, selector2...]) &rarr; object
{% endapibody %}

Plucks out one or more attributes from either an object or a sequence of objects
(projection).

__Example:__ We just need information about IronMan's reactor and not the rest of the
document.

```js
r.table('marvel').get('IronMan').pluck('reactorState', 'reactorPower').run(conn, callback)
```

[Read more about this command &rarr;](pluck/)

## [without](without/) ##

{% apibody %}
sequence.without([selector1, selector2...]) &rarr; stream
array.without([selector1, selector2...]) &rarr; array
singleSelection.without([selector1, selector2...]) &rarr; object
object.without([selector1, selector2...]) &rarr; object
{% endapibody %}

The opposite of pluck; takes an object or a sequence of objects, and returns them with
the specified paths removed.

__Example:__ Since we don't need it for this computation we'll save bandwidth and leave
out the list of IronMan's romantic conquests.

```js
r.table('marvel').get('IronMan').without('personalVictoriesList').run(conn, callback)
```

[Read more about this command &rarr;](without/)

## [merge](merge/) ##

{% apibody %}
singleSelection.merge([object | function, object | function, ...]) &rarr; object
object.merge([object | function, object | function, ...]) &rarr; object
sequence.merge([object | function, object | function, ...]) &rarr; stream
array.merge([object | function, object | function, ...]) &rarr; array
{% endapibody %}

Merge two or more objects together to construct a new object with properties from all. When there is a conflict between field names, preference is given to fields in the rightmost object in the argument list.

__Example:__ Equip Thor for battle.

```js
r.table('marvel').get('thor').merge(
    r.table('equipment').get('hammer'),
    r.table('equipment').get('pimento_sandwich')
).run(conn, callback)
```

[Read more about this command &rarr;](merge/)


## [append](append/) ##

{% apibody %}
array.append(value) &rarr; array
{% endapibody %}

Append a value to an array.

__Example:__ Retrieve Iron Man's equipment list with the addition of some new boots.

```js
r.table('marvel').get('IronMan')('equipment').append('newBoots').run(conn, callback)
```


## [prepend](prepend/) ##

{% apibody %}
array.prepend(value) &rarr; array
{% endapibody %}

Prepend a value to an array.

__Example:__ Retrieve Iron Man's equipment list with the addition of some new boots.

```js
r.table('marvel').get('IronMan')('equipment').prepend('newBoots').run(conn, callback)
```


## [difference](difference/) ##

{% apibody %}
array.difference(array) &rarr; array
{% endapibody %}

Remove the elements of one array from another array.

__Example:__ Retrieve Iron Man's equipment list without boots.

```js
r.table('marvel').get('IronMan')('equipment').difference(['Boots']).run(conn, callback)
```


## [setInsert](set_insert/) ##

{% apibody %}
array.setInsert(value) &rarr; array
{% endapibody %}

Add a value to an array and return it as a set (an array with distinct values).

__Example:__ Retrieve Iron Man's equipment list with the addition of some new boots.

```js
r.table('marvel').get('IronMan')('equipment').setInsert('newBoots').run(conn, callback)
```


## [setUnion](set_union/) ##

{% apibody %}
array.setUnion(array) &rarr; array
{% endapibody %}

Add a several values to an array and return it as a set (an array with distinct values).

__Example:__ Retrieve Iron Man's equipment list with the addition of some new boots and an arc reactor.

```js
r.table('marvel').get('IronMan')('equipment').setUnion(['newBoots', 'arc_reactor']).run(conn, callback)
```


## [setIntersection](set_intersection/) ##

{% apibody %}
array.setIntersection(array) &rarr; array
{% endapibody %}

Intersect two arrays returning values that occur in both of them as a set (an array with
distinct values).

__Example:__ Check which pieces of equipment Iron Man has from a fixed list.

```js
r.table('marvel').get('IronMan')('equipment').setIntersection(['newBoots', 'arc_reactor']).run(conn, callback)
```


## [setDifference](set_difference/) ##

{% apibody %}
array.setDifference(array) &rarr; array
{% endapibody %}

Remove the elements of one array from another and return them as a set (an array with
distinct values).

__Example:__ Check which pieces of equipment Iron Man has, excluding a fixed list.

```js
r.table('marvel').get('IronMan')('equipment').setDifference(['newBoots', 'arc_reactor']).run(conn, callback)
```

## [() (bracket)](bracket/) ##

{% apibody %}
sequence(attr) &rarr; sequence
singleSelection(attr) &rarr; value
object(attr) &rarr; value
array(index) &rarr; value
{% endapibody %}

Get a single field from an object or a single element from a sequence.

__Example:__ What was Iron Man's first appearance in a comic?

```js
r.table('marvel').get('IronMan')('firstAppearance').run(conn, callback)
```

[Read more about this command &rarr;](bracket/)

## [getField](get_field/) ##

{% apibody %}
sequence.getField(attr) &rarr; sequence
singleSelection.getField(attr) &rarr; value
object.getField(attr) &rarr; value
{% endapibody %}

Get a single field from an object. If called on a sequence, gets that field from every
object in the sequence, skipping objects that lack it.

__Example:__ What was Iron Man's first appearance in a comic?

```js
r.table('marvel').get('IronMan').getField('firstAppearance').run(conn, callback)
```


## [hasFields](has_fields/) ##

{% apibody %}
sequence.hasFields([selector1, selector2...]) &rarr; stream
array.hasFields([selector1, selector2...]) &rarr; array
object.hasFields([selector1, selector2...]) &rarr; boolean
{% endapibody %}

Test if an object has one or more fields. An object has a field if it has that key and the key has a non-null value. For instance, the object `{'a': 1,'b': 2,'c': null}` has the fields `a` and `b`.

__Example:__ Return the players who have won games.

```js
r.table('players').hasFields('games_won').run(conn, callback)
```

[Read more about this command &rarr;](has_fields/)


## [insertAt](insert_at/) ##

{% apibody %}
array.insertAt(index, value) &rarr; array
{% endapibody %}

Insert a value in to an array at a given index. Returns the modified array.

__Example:__ Hulk decides to join the avengers.

```js
r.expr(["Iron Man", "Spider-Man"]).insertAt(1, "Hulk").run(conn, callback)
```


## [spliceAt](splice_at/) ##

{% apibody %}
array.spliceAt(index, array) &rarr; array
{% endapibody %}

Insert several values in to an array at a given index. Returns the modified array.

__Example:__ Hulk and Thor decide to join the avengers.

```js
r.expr(["Iron Man", "Spider-Man"]).spliceAt(1, ["Hulk", "Thor"]).run(conn, callback)
```


## [deleteAt](delete_at/) ##

{% apibody %}
array.deleteAt(index [,endIndex]) &rarr; array
{% endapibody %}

Remove one or more elements from an array at a given index. Returns the modified array.

__Example:__ Delete the second element of an array.

```js
> r(['a','b','c','d','e','f']).deleteAt(1).run(conn, callback)
// result passed to callback
['a', 'c', 'd', 'e', 'f']
```

[Read more about this command &rarr;](delete_at/)

## [changeAt](change_at/) ##

{% apibody %}
array.changeAt(index, value) &rarr; array
{% endapibody %}

Change a value in an array at a given index. Returns the modified array.

__Example:__ Bruce Banner hulks out.

```js
r.expr(["Iron Man", "Bruce", "Spider-Man"]).changeAt(1, "Hulk").run(conn, callback)
```

## [keys](keys/) ##

{% apibody %}
singleSelection.keys() &rarr; array
object.keys() &rarr; array
{% endapibody %}

Return an array containing all of an object's keys. Note that the keys will be sorted as described in [ReQL data types](/docs/data-types/#sorting-order) (for strings, lexicographically).

__Example:__ Get all the keys from a table row.

```js
// row: { id: 1, mail: "fred@example.com", name: "fred" }

r.table('users').get(1).keys().run(conn, callback);
// Result passed to callback
[ "id", "mail", "name" ]
```

## [values](values/) ##

# Command syntax #

{% apibody %}
singleSelection.values() &rarr; array
object.values() &rarr; array
{% endapibody %}

Return an array containing all of an object's values. `values()` guarantees the values will come out in the same order as [keys](/api/javascript/keys).

__Example:__ Get all of the values from a table row.

```js
// row: { id: 1, mail: "fred@example.com", name: "fred" }

r.table('users').get(1).values().run(conn, callback);
// Result passed to callback
[ 1, "fred@example.com", "fred" ]
```

## [literal](literal/) ##

{% apibody %}
r.literal(object) &rarr; special
{% endapibody %}

Replace an object in a field instead of merging it with an existing object in a `merge` or `update` operation.

```js
r.table('users').get(1).update({ data: r.literal({ age: 19, job: 'Engineer' }) }).run(conn, callback)
```

[Read more about this command &rarr;](literal/)

## [object](object/) ##

{% apibody %}
r.object([key, value,]...) &rarr; object
{% endapibody %}

Creates an object from a list of key-value pairs, where the keys must
be strings.  `r.object(A, B, C, D)` is equivalent to
`r.expr([[A, B], [C, D]]).coerce_to('OBJECT')`.

__Example:__ Create a simple object.

```js
r.object('id', 5, 'data', ['foo', 'bar']).run(conn, callback)
```

{% endapisection %}


{% apisection String manipulation %}
These commands provide string operators.

## [match](match/) ##

{% apibody %}
string.match(regexp) &rarr; null/object
{% endapibody %}

Matches against a regular expression. If there is a match, returns an object with the fields:

- `str`: The matched string
- `start`: The matched string's start
- `end`: The matched string's end
- `groups`: The capture groups defined with parentheses

If no match is found, returns `null`.

__Example:__ Get all users whose name starts with "A". 

```js
r.table('users').filter(function(doc){
    return doc('name').match("^A")
}).run(conn, callback)
```



[Read more about this command &rarr;](match/)

## [split](split/) ##

{% apibody %}
string.split([separator, [max_splits]]) &rarr; array
{% endapibody %}

Splits a string into substrings.  Splits on whitespace when called
with no arguments.  When called with a separator, splits on that
separator.  When called with a separator and a maximum number of
splits, splits on that separator at most `max_splits` times.  (Can be
called with `null` as the separator if you want to split on whitespace
while still specifying `max_splits`.)

Mimics the behavior of Python's `string.split` in edge cases, except
for splitting on the empty string, which instead produces an array of
single-character strings.

__Example:__ Split on whitespace.

```js
r.expr("foo  bar bax").split().run(conn, callback)
```

[Read more about this command &rarr;](split/)

## [upcase](upcase/) ##

{% apibody %}
string.upcase() &rarr; string
{% endapibody %}


Uppercases a string.

__Example:__

```js
r.expr("Sentence about LaTeX.").upcase().run(conn, callback)
```

## [downcase](downcase/) ##

{% apibody %}
string.downcase() &rarr; string
{% endapibody %}

Lowercases a string.

__Example:__

```js
r.expr("Sentence about LaTeX.").downcase().run(conn, callback)
```

{% endapisection %}


{% apisection Math and logic %}

## [add](add/) ##

{% apibody %}
value.add(value[, value, ...]) &rarr; value
time.add(number[, number, ...]) &rarr; time
{% endapibody %}

Sum two or more numbers, or concatenate two or more strings or arrays.

__Example:__ It's as easy as 2 + 2 = 4.

```js
r.expr(2).add(2).run(conn, callback)
```


[Read more about this command &rarr;](add/)

## [sub](sub/) ##

{% apibody %}
number.sub(number[, number, ...]) &rarr; number
time.sub(number[, number, ...]) &rarr; time
time.sub(time) &rarr; number
{% endapibody %}

Subtract two numbers.

__Example:__ It's as easy as 2 - 2 = 0.

```js
r.expr(2).sub(2).run(conn, callback)
```

[Read more about this command &rarr;](sub/)


## [mul](mul/) ##

{% apibody %}
number.mul(number[, number, ...]) &rarr; number
array.mul(number[, number, ...]) &rarr; array
{% endapibody %}

Multiply two numbers, or make a periodic array.

__Example:__ It's as easy as 2 * 2 = 4.

```js
r.expr(2).mul(2).run(conn, callback)
```

[Read more about this command &rarr;](mul/)


## [div](div/) ##

{% apibody %}
number.div(number[, number ...]) &rarr; number
{% endapibody %}

Divide two numbers.

__Example:__ It's as easy as 2 / 2 = 1.

```js
r.expr(2).div(2).run(conn, callback)
```



## [mod](mod/) ##

{% apibody %}
number.mod(number) &rarr; number
{% endapibody %}

Find the remainder when dividing two numbers.

__Example:__ It's as easy as 2 % 2 = 0.

```js
r.expr(2).mod(2).run(conn, callback)
```

## [and](and/) ##

{% apibody %}
bool.and([bool, bool, ...]) &rarr; bool
r.and([bool, bool, ...]) &rarr; bool
{% endapibody %}

Compute the logical "and" of one or more values.

__Example:__ Return whether both `a` and `b` evaluate to true.

```js
var a = true, b = false;
r.expr(a).and(b).run(conn, callback);
// result passed to callback
false
```

## [or](or/) ##

{% apibody %}
bool.or([bool, bool, ...]) &rarr; bool
r.or([bool, bool, ...]) &rarr; bool
{% endapibody %}

Compute the logical "or" of one or more values.

__Example:__ Return whether either `a` or `b` evaluate to true.

```js
var a = true, b = false;
r.expr(a).or(b).run(conn, callback);
// result passed to callback
true
```

## [eq](eq/) ##

{% apibody %}
value.eq(value[, value, ...]) &rarr; bool
{% endapibody %}

Test if two or more values are equal.

__Example:__ See if a user's `role` field is set to `administrator`. 

```js
r.table('users').get(1)('role').eq('administrator').run(conn, callback);
```


## [ne](ne/) ##

{% apibody %}
value.ne(value[, value, ...]) &rarr; bool
{% endapibody %}

Test if two or more values are not equal.

__Example:__ See if a user's `role` field is not set to `administrator`. 

```rb
r.table('users').get(1)('role').ne('administrator').run(conn, callback);
```


## [gt](gt/) ##

{% apibody %}
value.gt(value[, value, ...]) &rarr; bool
{% endapibody %}

Compare values, testing if the left-hand value is greater than the right-hand.

__Example:__ Test if a player has scored more than 10 points.

```js
r.table('players').get(1)('score').gt(10).run(conn, callback);
```

## [ge](ge/) ##

{% apibody %}
value.ge(value[, value, ...]) &rarr; bool
{% endapibody %}

Compare values, testing if the left-hand value is greater than or equal to the right-hand.

__Example:__ Test if a player has scored 10 points or more.

```js
r.table('players').get(1)('score').ge(10).run(conn, callback);
```

## [lt](lt/) ##

{% apibody %}
value.lt(value[, value, ...]) &rarr; bool
{% endapibody %}

Compare values, testing if the left-hand value is less than the right-hand.

__Example:__ Test if a player has scored less than 10 points.

```js
r.table('players').get(1)('score').lt(10).run(conn, callback);
```

## [le](le/) ##

{% apibody %}
value.le(value[, value, ...]) &rarr; bool
{% endapibody %}

Compare values, testing if the left-hand value is less than or equal to the right-hand.

__Example:__ Test if a player has scored 10 points or less.

```js
r.table('players').get(1)('score').le(10).run(conn, callback);
```

## [not](not/) ##

{% apibody %}
bool.not() &rarr; bool
not(bool) &rarr; bool
{% endapibody %}

Compute the logical inverse (not) of an expression.

`not` can be called either via method chaining, immediately after an expression that evaluates as a boolean value, or by passing the expression as a parameter to `not`.

__Example:__ Not true is false.

```js
r(true).not().run(conn, callback)
r.not(true).run(conn, callback)
```

[Read more about this command &rarr;](not/)

## [random](random/) ##

{% apibody %}
r.random() &rarr; number
r.random(number[, number], {float: true}) &rarr; number
r.random(integer[, integer]) &rarr; integer
{% endapibody %}

Generate a random number between given (or implied) bounds. `random` takes zero, one or two arguments.

__Example:__ Generate a random number in the range `[0,1)`

```js
r.random().run(conn, callback)
```

[Read more about this command &rarr;](random/)

## [round](round/) ##

{% apibody %}
r.round(number) &rarr; number
number.round() &rarr; number
{% endapibody %}

Rounds the given value to the nearest whole integer.

__Example:__ Round 12.345 to the nearest integer.

```js
> r.round(12.345).run(conn, callback);

12.0
```

## [ceil](ceil/) ##

{% apibody %}
r.ceil(number) &rarr; number
number.ceil() &rarr; number
{% endapibody %}

Rounds the given value up, returning the smallest integer value greater than or equal to the given value (the value's ceiling).

__Example:__ Return the ceiling of 12.345.

```js
> r.ceil(12.345).run(conn, callback);

13.0
```

## [floor](floor/) ##

{% apibody %}
r.floor(number) &rarr; number
number.floor() &rarr; number
{% endapibody %}

Rounds the given value down, returning the largest integer value less than or equal to the given value (the value's floor).

__Example:__ Return the floor of 12.345.

```js
> r.floor(12.345).run(conn, callback);

12.0
```

{% endapisection %}


{% apisection Dates and times %}

## [now](now/) ##

{% apibody %}
r.now() &rarr; time
{% endapibody %}

Return a time object representing the current time in UTC. The command now() is computed once when the server receives the query, so multiple instances of r.now() will always return the same time inside a query.

__Example:__ Add a new user with the time at which he subscribed.

```js
r.table("users").insert({
    name: "John",
    subscription_date: r.now()
}).run(conn, callback)
```

## [time](time/) ##

{% apibody %}
r.time(year, month, day[, hour, minute, second], timezone)
    &rarr; time
{% endapibody %}

Create a time object for a specific time.

A few restrictions exist on the arguments:

- `year` is an integer between 1400 and 9,999.
- `month` is an integer between 1 and 12.
- `day` is an integer between 1 and 31.
- `hour` is an integer.
- `minutes` is an integer.
- `seconds` is a double. Its value will be rounded to three decimal places
(millisecond-precision).
- `timezone` can be `'Z'` (for UTC) or a string with the format `±[hh]:[mm]`.

__Example:__ Update the birthdate of the user "John" to November 3rd, 1986 UTC.

```js
r.table("user").get("John").update({birthdate: r.time(1986, 11, 3, 'Z')})
    .run(conn, callback)
```



## [epochTime](epoch_time/) ##

{% apibody %}
r.epochTime(number) &rarr; time
{% endapibody %}

Create a time object based on seconds since epoch. The first argument is a double and
will be rounded to three decimal places (millisecond-precision).

__Example:__ Update the birthdate of the user "John" to November 3rd, 1986.

```js
r.table("user").get("John").update({birthdate: r.epochTime(531360000)})
    .run(conn, callback)
```


## [ISO8601](iso8601/) ##

{% apibody %}
r.ISO8601(string[, {defaultTimezone:''}]) &rarr; time
{% endapibody %}

Create a time object based on an ISO 8601 date-time string (e.g. '2013-01-01T01:01:01+00:00'). RethinkDB supports all valid ISO 8601 formats except for week dates. Read more about the ISO 8601 format at [Wikipedia](http://en.wikipedia.org/wiki/ISO_8601).

If you pass an ISO 8601 string without a time zone, you must specify the time zone with the `defaultTimezone` argument.

__Example:__ Update the time of John's birth.

```js
r.table("user").get("John").update({birth: r.ISO8601('1986-11-03T08:30:00-07:00')}).run(conn, callback)
```


## [inTimezone](in_timezone/) ##

{% apibody %}
time.inTimezone(timezone) &rarr; time
{% endapibody %}

Return a new time object with a different timezone. While the time stays the same, the results returned by methods such as hours() will change since they take the timezone into account. The timezone argument has to be of the ISO 8601 format.

__Example:__ Hour of the day in San Francisco (UTC/GMT -8, without daylight saving time).

```js
r.now().inTimezone('-08:00').hours().run(conn, callback)
```



## [timezone](timezone/) ##

{% apibody %}
time.timezone() &rarr; string
{% endapibody %}

Return the timezone of the time object.

__Example:__ Return all the users in the "-07:00" timezone.

```js
r.table("users").filter( function(user) {
    return user("subscriptionDate").timezone().eq("-07:00")
})
```


## [during](during/) ##

{% apibody %}
time.during(startTime, endTime[, options]) &rarr; bool
{% endapibody %}

Return if a time is between two other times (by default, inclusive for the start, exclusive for the end).

__Example:__ Retrieve all the posts that were posted between December 1st, 2013 (inclusive) and December 10th, 2013 (exclusive).

```js
r.table("posts").filter(
    r.row('date').during(r.time(2013, 12, 1), r.time(2013, 12, 10))
).run(conn, callback)
```

[Read more about this command &rarr;](during/)



## [date](date/) ##

{% apibody %}
time.date() &rarr; time
{% endapibody %}

Return a new time object only based on the day, month and year (ie. the same day at 00:00).

__Example:__ Retrieve all the users whose birthday is today.

```js
r.table("users").filter(function(user) {
    return user("birthdate").date().eq(r.now().date())
}).run(conn, callback)
```



## [timeOfDay](time_of_day/) ##

{% apibody %}
time.timeOfDay() &rarr; number
{% endapibody %}

Return the number of seconds elapsed since the beginning of the day stored in the time object.

__Example:__ Retrieve posts that were submitted before noon.

```js
r.table("posts").filter(
    r.row("date").timeOfDay().le(12*60*60)
).run(conn, callback)
```


## [year](year/) ##

{% apibody %}
time.year() &rarr; number
{% endapibody %}

Return the year of a time object.

__Example:__ Retrieve all the users born in 1986.

```js
r.table("users").filter(function(user) {
    return user("birthdate").year().eq(1986)
}).run(conn, callback)
```


## [month](month/) ##

{% apibody %}
time.month() &rarr; number
{% endapibody %}

Return the month of a time object as a number between 1 and 12. For your convenience, the terms r.january, r.february etc. are defined and map to the appropriate integer.

__Example:__ Retrieve all the users who were born in November.

```js
r.table("users").filter(
    r.row("birthdate").month().eq(11)
)
```

[Read more about this command &rarr;](month/)


## [day](day/) ##

{% apibody %}
time.day() &rarr; number
{% endapibody %}

Return the day of a time object as a number between 1 and 31.

__Example:__ Return the users born on the 24th of any month.

```js
r.table("users").filter(
    r.row("birthdate").day().eq(24)
).run(conn, callback)
```



## [dayOfWeek](day_of_week/) ##

{% apibody %}
time.dayOfWeek() &rarr; number
{% endapibody %}

Return the day of week of a time object as a number between 1 and 7 (following ISO 8601 standard). For your convenience, the terms r.monday, r.tuesday etc. are defined and map to the appropriate integer.

__Example:__ Return today's day of week.

```js
r.now().dayOfWeek().run(conn, callback)
```

[Read more about this command &rarr;](day_of_week/)



## [dayOfYear](day_of_year/) ##

{% apibody %}
time.dayOfYear() &rarr; number
{% endapibody %}

Return the day of the year of a time object as a number between 1 and 366 (following ISO 8601 standard).

__Example:__ Retrieve all the users who were born the first day of a year.

```js
r.table("users").filter(
    r.row("birthdate").dayOfYear().eq(1)
)
```


## [hours](hours/) ##

{% apibody %}
time.hours() &rarr; number
{% endapibody %}

Return the hour in a time object as a number between 0 and 23.

__Example:__ Return all the posts submitted after midnight and before 4am.

```js
r.table("posts").filter(function(post) {
    return post("date").hours().lt(4)
})
```


## [minutes](minutes/) ##

{% apibody %}
time.minutes() &rarr; number
{% endapibody %}

Return the minute in a time object as a number between 0 and 59.

__Example:__ Return all the posts submitted during the first 10 minutes of every hour.

```js
r.table("posts").filter(function(post) {
    return post("date").minutes().lt(10)
})
```



## [seconds](seconds/) ##

{% apibody %}
time.seconds() &rarr; number
{% endapibody %}

Return the seconds in a time object as a number between 0 and 59.999 (double precision).

__Example:__ Return the post submitted during the first 30 seconds of every minute.

```js
r.table("posts").filter(function(post) {
    return post("date").seconds().lt(30)
})
```

## [toISO8601](to_iso8601/) ##

{% apibody %}
time.toISO8601() &rarr; string
{% endapibody %}

Convert a time object to a string in ISO 8601 format.

__Example:__ Return the current ISO 8601 time.

```js
r.now().toISO8601().run(conn, callback)
// Result passed to callback
"2015-04-20T18:37:52.690+00:00"
```


## [toEpochTime](to_epoch_time/) ##

{% apibody %}
time.toEpochTime() &rarr; number
{% endapibody %}

Convert a time object to its epoch time.

__Example:__ Return the current time in seconds since the Unix Epoch with millisecond-precision.

```js
r.now().toEpochTime()
```



{% endapisection %}


{% apisection Control structures %}

## [args](args/) ##

{% apibody %}
r.args(array) &rarr; special
{% endapibody %}

`r.args` is a special term that's used to splice an array of arguments
into another term.  This is useful when you want to call a variadic
term such as `getAll` with a set of arguments produced at runtime.

This is analogous to using **apply** in JavaScript.

__Example:__ Get Alice and Bob from the table `people`.

```js
r.table('people').getAll('Alice', 'Bob').run(conn, callback)
// or
r.table('people').getAll(r.args(['Alice', 'Bob'])).run(conn, callback)
```

## [binary](binary/) ##

{% apibody %}
r.binary(data) &rarr; binary
{% endapibody %}

Encapsulate binary data within a query.

__Example:__ Save an avatar image to a existing user record.

```js
var fs = require('fs');
fs.readFile('./defaultAvatar.png', function (err, avatarImage) {
    if (err) {
        // Handle error
    }
    else {
        r.table('users').get(100).update({
            avatar: avatarImage
        })
    }
});
```

[Read more about this command &rarr;](binary/)

## [do](do/) ##

{% apibody %}
any.do(function) &rarr; any
r.do([args]*, function) &rarr; any
any.do(expr) &rarr; any
r.do([args]*, expr) &rarr; any
{% endapibody %}

Call an anonymous function using return values from other ReQL commands or queries as arguments.

 __Example:__ Compute a golfer's net score for a game.

```js
r.table('players').get('f19b5f16-ef14-468f-bd48-e194761df255').do(
    function (player) {
        return player('gross_score').sub(player('course_handicap'));
    }
).run(conn, callback);
```

[Read more about this command &rarr;](do/)

## [branch](branch/) ##

{% apibody %}
r.branch(test, true_action[, test2, else_action, ...], false_action) &rarr; any
{% endapibody %}

Perform a branching conditional equivalent to `if-then-else`.

The `branch` command takes 2n+1 arguments: pairs of conditional expressions and commands to be executed if the conditionals return any value but `false` or `null` (i.e., "truthy" values), with a final "else" command to be evaluated if all of the conditionals are `false` or `null`.

__Example:__ Test the value of x.

```js
var x = 10;
r.branch(r.expr(x).gt(5), 'big', 'small').run(conn, callback);
// Result passed to callback
"big"
```

[Read more about this command &rarr;](branch/)

## [forEach](for_each/) ##

{% apibody %}
sequence.forEach(write_function) &rarr; object
{% endapibody %}

Loop over a sequence, evaluating the given write query for each element.

__Example:__ Now that our heroes have defeated their villains, we can safely remove them from the villain table.

```js
r.table('marvel').forEach(function(hero) {
    return r.table('villains').get(hero('villainDefeated')).delete()
}).run(conn, callback)
```

## [range](range/) ##

{% apibody %}
r.range() &rarr; stream
r.range([startValue, ]endValue) &rarr; stream
{% endapibody %}

Generate a stream of sequential integers in a specified range.

__Example:__ Return a four-element range of `[0, 1, 2, 3]`.

```js
> r.range(4).run(conn, callback)

[0, 1, 2, 3]
```


## [error](error/) ##

{% apibody %}
r.error(message) &rarr; error
{% endapibody %}

Throw a runtime error. If called with no arguments inside the second argument to `default`, re-throw the current error.

__Example:__ Iron Man can't possibly have lost a battle:

```js
r.table('marvel').get('IronMan').do(function(ironman) {
    return r.branch(ironman('victories').lt(ironman('battles')),
        r.error('impossible code path'),
        ironman)
}).run(conn, callback)
```

## [default](default/) ##

{% apibody %}
value.default(default_value | function) &rarr; any
sequence.default(default_value | function) &rarr; any
{% endapibody %}

Provide a default value in case of non-existence errors. The `default` command evaluates its first argument (the value it's chained to). If that argument returns `null` or a non-existence error is thrown in evaluation, then `default` returns its second argument. The second argument is usually a default value, but it can be a function that returns a value.

__Example:__ Retrieve the titles and authors of the table `posts`.
In the case where the author field is missing or `null`, we want to retrieve the string
`Anonymous`.

```js
r.table("posts").map(function (post) {
    return {
        title: post("title"),
        author: post("author").default("Anonymous")
    }
}).run(conn, callback);
```

[Read more about this command &rarr;](default/)

## [expr](expr/) ##

{% apibody %}
r.expr(value) &rarr; value
{% endapibody %}

Construct a ReQL JSON object from a native object.

__Example:__ Objects wrapped with `expr` can then be manipulated by ReQL API functions.

```js
r.expr({a:'b'}).merge({b:[1,2,3]}).run(conn, callback)
```

[Read more about this command &rarr;](expr/)

## [js](js/) ##

{% apibody %}
r.js(jsString[, {timeout: <number>}]) &rarr; value
{% endapibody %}

Create a javascript expression.

__Example:__ Concatenate two strings using JavaScript.

```js
r.js("'str1' + 'str2'").run(conn, callback)
```

[Read more about this command &rarr;](js/)

## [coerceTo](coerce_to/) ##

{% apibody %}
sequence.coerceTo('array') &rarr; array
value.coerceTo('string') &rarr; string
string.coerceTo('number') &rarr; number
array.coerceTo('object') &rarr; object
sequence.coerceTo('object') &rarr; object
object.coerceTo('array') &rarr; array
binary.coerceTo('string') &rarr; string
string.coerceTo('binary') &rarr; binary
{% endapibody %}

Convert a value of one type into another.

__Example:__ Coerce a stream to an array.

```js
r.table('posts').map(function (post) {
    return post.merge({ comments: r.table('comments').getAll(post('id'), {index: 'postId'}).coerceTo('array')});
}).run(conn, callback)
```

[Read more about this command &rarr;](coerce_to/)

## [typeOf](type_of/) ##

{% apibody %}
any.typeOf() &rarr; string
{% endapibody %}

Gets the type of a value.

__Example:__ Get the type of a string.

```js
r.expr("foo").typeOf().run(conn, callback)
```

## [info](info/) ##

{% apibody %}
any.info() &rarr; object
r.info(any) &rarr; object
{% endapibody %}

Get information about a ReQL value.

__Example:__ Get information about a table such as primary key, or cache size.

```js
r.table('marvel').info().run(conn, callback)
```

## [json](json/) ##

{% apibody %}
r.json(json_string) &rarr; value
{% endapibody %}

Parse a JSON string on the server.

__Example:__ Send an array to the server.

```js
r.json("[1,2,3]").run(conn, callback)
```

## [toJsonString, toJSON](to_json_string/) ##

{% apibody %}
value.toJsonString() &rarr; string
value.toJSON() &rarr; string
{% endapibody %}

Convert a ReQL value or object to a JSON string. You may use either `toJsonString` or `toJSON`.

__Example:__ Get a ReQL document as a JSON string.

```js
> r.table('hero').get(1).toJSON()
// result returned to callback
'{"id": 1, "name": "Batman", "city": "Gotham", "powers": ["martial arts", "cinematic entrances"]}'
```

## [http](http/) ##

{% apibody %}
r.http(url [, options]) &rarr; value
{% endapibody %}

Retrieve data from the specified URL over HTTP.  The return type depends on the `resultFormat` option, which checks the `Content-Type` of the response by default.

__Example:__ Perform a simple HTTP `GET` request, and store the result in a table.

```js
r.table('posts').insert(r.http('http://httpbin.org/get')).run(conn, callback)
```

[Read more about this command &rarr;](http/)

## [uuid](uuid/) ##

{% apibody %}
r.uuid([string]) &rarr; string
{% endapibody %}

Return a UUID (universally unique identifier), a string that can be used as a unique ID. If a string is passed to `uuid` as an argument, the UUID will be deterministic, derived from the string's SHA-1 hash.

__Example:__ Generate a UUID.

```js
> r.uuid().run(conn, callback)
// result returned to callback
"27961a0e-f4e8-4eb3-bf95-c5203e1d87b9"
```

[Read more about this command &rarr;](uuid/)

{% endapisection %}

{% apisection Geospatial commands %}

## [circle](circle/) ##

{% apibody %}
r.circle([longitude, latitude], radius[, {numVertices: 32, geoSystem: 'WGS84', unit: 'm', fill: true}]) &rarr; geometry
r.circle(point, radius[, {numVertices: 32, geoSystem: 'WGS84', unit: 'm', fill: true}]) &rarr; geometry
{% endapibody %}

Construct a circular line or polygon. A circle in RethinkDB is a polygon or line *approximating* a circle of a given radius around a given center, consisting of a specified number of vertices (default 32).

__Example:__ Define a circle.

```js
r.table('geo').insert({
    id: 300,
    name: 'Hayes Valley',
    neighborhood: r.circle([-122.423246,37.779388], 1000)
}).run(conn, callback);
```

[Read more about this command &rarr;](circle/)

## [distance](distance/) ##

{% apibody %}
geometry.distance(geometry[, {geoSystem: 'WGS84', unit: 'm'}]) &rarr; number
r.distance(geometry, geometry[, {geoSystem: 'WGS84', unit: 'm'}]) &rarr; number
{% endapibody %}

Compute the distance between a point and another geometry object. At least one of the geometry objects specified must be a point.

__Example:__ Compute the distance between two points on the Earth in kilometers.

```js
var point1 = r.point(-122.423246,37.779388);
var point2 = r.point(-117.220406,32.719464);
r.distance(point1, point2, {unit: 'km'}).run(conn, callback);
// result returned to callback 
734.1252496021841
```

[Read more about this command &rarr;](distance/)

## [fill](fill/) ##

{% apibody %}
line.fill() &rarr; polygon
{% endapibody %}

Convert a Line object into a Polygon object. If the last point does not specify the same coordinates as the first point, `polygon` will close the polygon by connecting them.

__Example:__ Create a line object and then convert it to a polygon.

```js
r.table('geo').insert({
    id: 201,
    rectangle: r.line(
        [-122.423246,37.779388],
        [-122.423246,37.329898],
        [-121.886420,37.329898],
        [-121.886420,37.779388]
    )
}).run(conn, callback);

r.table('geo').get(201).update({
    rectangle: r.row('rectangle').fill()
}, {nonAtomic: true}).run(conn, callback);
```

[Read more about this command &rarr;](fill/)

## [geojson](geojson/) ##

{% apibody %}
r.geojson(geojson) &rarr; geometry
{% endapibody %}

Convert a [GeoJSON][] object to a ReQL geometry object.

[GeoJSON]: http://geojson.org

__Example:__ Convert a GeoJSON object to a ReQL geometry object.

```js
var geoJson = {
    'type': 'Point',
    'coordinates': [ -122.423246, 37.779388 ]
};
r.table('geo').insert({
    id: 'sfo',
    name: 'San Francisco',
    location: r.geojson(geoJson)
}).run(conn, callback);
```

[Read more about this command &rarr;](geojson/)

## [toGeojson](to_geojson/) ##

{% apibody %}
geometry.toGeojson() &rarr; object
{% endapibody %}

Convert a ReQL geometry object to a [GeoJSON][] object.

__Example:__ Convert a ReQL geometry object to a GeoJSON object.

```js
r.table('geo').get('sfo')('location').toGeojson().run(conn, callback);
// result passed to callback
{
    'type': 'Point',
    'coordinates': [ -122.423246, 37.779388 ]
}
```

[Read more about this command &rarr;](to_geojson/)

## [getIntersecting](get_intersecting/) ##

{% apibody %}
table.getIntersecting(geometry, {index: 'indexname'}) &rarr; selection<stream>
{% endapibody %}

Get all documents where the given geometry object intersects the geometry object of the requested geospatial index.

__Example:__ Which of the locations in a list of parks intersect `circle1`?

```js
var circle1 = r.circle([-117.220406,32.719464], 10, {unit: 'mi'});
r.table('parks').getIntersecting(circle1, {index: 'area'}).run(conn, callback);
```

[Read more about this command &rarr;](get_intersecting/)

## [getNearest](get_nearest/) ##

{% apibody %}
table.getNearest(point, {index: 'indexname'[, maxResults: 100, maxDist: 100000, unit: 'm', geoSystem: 'WGS84']}) &rarr; selection<array>
{% endapibody %}

Get all documents where the specified geospatial index is within a certain distance of the specified point (default 100 kilometers).

__Example:__ Return a list of enemy hideouts within 5000 meters of the secret base.

```js
var secretBase = r.point(-122.422876,37.777128);
r.table('hideouts').getNearest(secretBase,
    {index: 'location', maxDist: 5000}
).run(conn, callback)
```

[Read more about this command &rarr;](get_nearest/)

## [includes](includes/) ##

{% apibody %}
sequence.includes(geometry) &rarr; sequence
geometry.includes(geometry) &rarr; bool
{% endapibody %}

Tests whether a geometry object is completely contained within another. When applied to a sequence of geometry objects, `includes` acts as a [filter](/api/javascript/filter), returning a sequence of objects from the sequence that include the argument.

__Example:__ Is `point2` included within a 2000-meter circle around `point1`?

```js
var point1 = r.point(-117.220406,32.719464);
var point2 = r.point(-117.206201,32.725186);
r.circle(point1, 2000).includes(point2).run(conn, callback);
// result returned to callback 
true
```

[Read more about this command &rarr;](includes/)

## [intersects](intersects/) ##

{% apibody %}
sequence.intersects(geometry) &rarr; sequence
geometry.intersects(geometry) &rarr; bool
r.intersects(sequence, geometry) &rarr; sequence
r.intersects(geometry, geometry) &rarr; bool
{% endapibody %}

Tests whether two geometry objects intersect with one another. When applied to a sequence of geometry objects, `intersects` acts as a [filter](/api/javascript/filter), returning a sequence of objects from the sequence that intersect with the argument.

__Example:__ Is `point2` within a 2000-meter circle around `point1`?

```js
var point1 = r.point(-117.220406,32.719464);
var point2 = r.point(-117.206201,32.725186);
r.circle(point1, 2000).intersects(point2).run(conn, callback);
// result returned to callback 
true
```

[Read more about this command &rarr;](intersects/)

## [line](line/) ##

{% apibody %}
r.line([lon1, lat1], [lon2, lat1], ...) &rarr; line
r.line(point1, point2, ...) &rarr; line
{% endapibody %}

Construct a geometry object of type Line. The line can be specified in one of two ways:

* Two or more two-item arrays, specifying longitude and latitude numbers of the line's vertices;
* Two or more [Point](/api/javascript/point) objects specifying the line's vertices.

__Example:__ Define a line.

```js
r.table('geo').insert({
    id: 101,
    route: r.line([-122.423246,37.779388], [-121.886420,37.329898])
}).run(conn, callback);
```

[Read more about this command &rarr;](line/)

## [point](point/) ##

{% apibody %}
r.point(longitude, latitude) &rarr; point
{% endapibody %}

Construct a geometry object of type Point. The point is specified by two floating point numbers, the longitude (&minus;180 to 180) and the latitude (&minus;90 to 90) of the point on a perfect sphere.

__Example:__ Define a point.

```js
r.table('geo').insert({
    id: 1,
    name: 'San Francisco',
    location: r.point(-122.423246,37.779388)
}).run(conn, callback);
```

[Read more about this command &rarr;](point/)

## [polygon](polygon/) ##

{% apibody %}
r.polygon([lon1, lat1], [lon2, lat2], [lon3, lat3], ...) &rarr; polygon
r.polygon(point1, point2, point3, ...) &rarr; polygon
{% endapibody %}

Construct a geometry object of type Polygon. The Polygon can be specified in one of two ways:

* Three or more two-item arrays, specifying longitude and latitude numbers of the polygon's vertices;
* Three or more [Point](/api/javascript/point) objects specifying the polygon's vertices.

__Example:__ Define a polygon.

```js
r.table('geo').insert({
    id: 101,
    rectangle: r.polygon(
        [-122.423246,37.779388],
        [-122.423246,37.329898],
        [-121.886420,37.329898],
        [-121.886420,37.779388]
    )
}).run(conn, callback);
```

[Read more about this command &rarr;](polygon/)

## [polygonSub](polygon_sub/) ##

{% apibody %}
polygon1.polygonSub(polygon2) &rarr; polygon
{% endapibody %}

Use `polygon2` to "punch out" a hole in `polygon1`. `polygon2` must be completely contained within `polygon1` and must have no holes itself (it must not be the output of `polygonSub` itself).


__Example:__ Define a polygon with a hole punched in it.

```js
var outerPolygon = r.polygon(
    [-122.4,37.7],
    [-122.4,37.3],
    [-121.8,37.3],
    [-121.8,37.7]
);
var innerPolygon = r.polygon(
    [-122.3,37.4],
    [-122.3,37.6],
    [-122.0,37.6],
    [-122.0,37.4]
);
outerPolygon.polygonSub(innerpolygon).run(conn, callback);
```

[Read more about this command &rarr;](polygon_sub/)

{% endapisection %}

{% apisection Administration %}

## [config](config/) ##

{% apibody %}
table.config() &rarr; selection&lt;object&gt;
database.config() &rarr; selection&lt;object&gt;
{% endapibody %}

Query (read and/or update) the configurations for individual tables or databases.

__Example:__ Get the configuration for the `users` table.

```js
> r.table('users').config().run(conn, callback);
```

[Read more about this command &rarr;](config/)

## [rebalance](rebalance/) ##

{% apibody %}
table.rebalance() &rarr; object
database.rebalance() &rarr; object
{% endapibody %}

Rebalances the shards of a table. When called on a database, all the tables in that database will be rebalanced.

__Example:__ Rebalance a table.

```js
> r.table('superheroes').rebalance().run(conn, callback);
```

[Read more about this command &rarr;](rebalance/)

## [reconfigure](reconfigure/) ##

{% apibody %}
table.reconfigure({shards: <s>, replicas: <r>[, primaryReplicaTag: <t>, dryRun: false}]) &rarr; object
database.reconfigure({shards: <s>, replicas: <r>[, primaryReplicaTag: <t>, dryRun: false}]) &rarr; object
{% endapibody %}

Reconfigure a table's sharding and replication.

__Example:__ Reconfigure a table.

```js
> r.table('superheroes').reconfigure({shards: 2, replicas: 1}).run(conn, callback);
```

[Read more about this command &rarr;](reconfigure/)

## [status](status/) ##

{% apibody %}
table.status() &rarr; selection&lt;object&gt;
{% endapibody %}

Return the status of a table.

__Example:__ Get a table's status.

```js
> r.table('superheroes').status().run(conn, callback);
```

[Read more about this command &rarr;](status/)

## [wait](wait/) ##

{% apibody %}
table.wait([{waitFor: 'ready_for_writes', timeout: <sec>}]) &rarr; object
database.wait([{waitFor: 'ready_for_writes', timeout: <sec>}]) &rarr; object
r.wait([{waitFor: 'ready_for_writes', timeout: <sec>}]) &rarr; object
{% endapibody %}

Wait for a table or all the tables in a database to be ready. A table may be temporarily unavailable after creation, rebalancing or reconfiguring. The `wait` command blocks until the given table (or database) is fully up to date.

__Example:__ Wait for a table to be ready.

```js
> r.table('superheroes').wait().run(conn, callback);
```

[Read more about this command &rarr;](wait/)

{% endapisection %}

