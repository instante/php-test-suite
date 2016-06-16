#!/usr/bin/env bash
pushd "$(dirname "$0")/.."

if [ -f "./tests/php-local.ini" ]; then
    cp ./tests/php-local.ini ./tests/php.ini
elif [ -f ./tests/php-unix.ini ]; then
    cp ./tests/php-unix.ini ./tests/php.ini
    echo "" >> ./tests/php.ini # empty line
    PHP_EXT=`php -r "echo ini_get('extension_dir');"`
    echo "extension_dir=$PHP_EXT" >> ./tests/php.ini
elif [ -f "./tests/php.ini" ]; then # remove old php.ini from tests
    rm "./tests/php.ini"
fi

rm -rf ./temp/*

./vendor/bin/tester ./tests/$1 -p php -c ./tests
EXITCODE=$?

popd

exit "$EXITCODE"
