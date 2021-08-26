#!/usr/bin/env bash

echo "Create dir tests/static/report/allure/$TRAVIS_BUILD_NUMBER";
mkdir tests/static/report/allure
mkdir tests/static/report/allure/$TRAVIS_BUILD_NUMBER
~/allure/allure-2.13.0/bin/allure generate -c tests/allure-report-data -o tests/static/report/allure/$TRAVIS_BUILD_NUMBER
