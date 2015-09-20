#!/usr/bin/env bash

# update dependencies
PWD=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
BASEDIR=`dirname $PWD`

# db
export RDB_HOST=127.0.0.1
export RDB_PORT=28015
export RDB_DB=RQL_TEST_`date +%s`

php $BASEDIR/tests/TestHelpers/createDb.php

# run tests
phpunit -c $PWD/phpunit.xml "$@"
STATUS=$?

#remove db
php $BASEDIR/tests/TestHelpers/deleteDb.php

# exit
exit $STATUS
