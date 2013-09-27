#!/bin/bash
cd /usr/local/osa
find . -name *.pyc|xargs rm -f
find . -name *.pyo|xargs rm -f
find . -name *.swp|xargs rm -f
